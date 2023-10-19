<?php

namespace App\Http\Controllers\Admin;

use App\Events\MyEvent;
use App\Events\PublisherEvent;
use App\Models\MotorEvent;
use App\Models\Amenitable;
use App\Models\City;
use App\Models\Landmarkable;
use App\Models\MajorCategory;
use App\Models\Motors;
use App\Models\Amenties;
use App\Models\Landmark;
use App\Models\SubCategory;
use App\Models\MoreInfo;
use App\Models\MainCategory;
use App\Models\MotorImage;
use App\Models\Manufacturer;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use App\Models\NotificationsInfo;
use App\Models\PopularPlacesTypes;
use App\Models\WishListDetails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Helper;
use Illuminate\Validation\Rule;
use Image;
use DB;
use Yajra\DataTables\Facades\DataTables;

class MotorsController extends Controller
{
    function __construct()
    {
      $this->middleware('role_or_permission:Admin|motor-add', ['only' => ['create', 'store']]);
      $this->middleware('role_or_permission:Admin|motor-view', ['only' => ['index']]);
      $this->middleware('role_or_permission:Admin|motor-update', ['only' => ['edit', 'update', 'update_status_motor']]);
    }

    public function index(Request $request)
    {
      $pagetitle = 'Manage Contact Requests';
      $activeTab = 'contact-requests';
      $activeSubTab = 'list-contact-requests';

      $main_category = MainCategory::where('major_category_id', '13')->orderBy('name')->pluck('name', 'id');
      $main_categories = $request->get('main_category', []);

      $date_from = $request->get('date_from');
      $date_to = $request->get('date_to');
      $keyword = $request->get('search');
      if ($request->ajax()) {
          $motors = Motors::with('get_subcat.mainCategory');
          $auth_user_type = Auth::user()->user_type;
          if ($auth_user_type != 1) {
              $motors = $motors->where('created_by', Auth::id());
          }
          if ($main_categories) { // && empty($keyword)
              $motors->orWhereHas('get_subcat.mainCategory', function ($q) use ($main_categories) {
                  $q->whereIn('id', $main_categories);
              });
          }
         
          if ($keyword) {
              $motors->where(function ($query) use ($keyword) {
                  $query->where('motors.title', 'LIKE', '%' . $keyword . '%');
              });
          }
          if (!empty($date_from) && empty($keyword)) {
              $motors->where('motors.created_at', '>', $date_from . " 00:00:00");
          }

          if (!empty($date_from) && empty($keyword)) {
              $motors->where('motors.created_at', '<=', $date_to . " 23:59:59");
          }
         
          $motors = $motors->latest()->get();
          return DataTables::of($motors)
              ->addColumn('active', function ($motor) use ($auth_user_type) {
                  if ($auth_user_type === 1) {
                      return '<input type="checkbox" class="motor-status" data-toggle="switch" data-size="small"
                  data-on-color="green" data-on-text="ON" data-off-color="default" data-off-text="OFF"
                  data-id="' . $motor->id . '" value="' . $motor->status . '" ' . ($motor->status ? "checked" : "") . '>';
                  }
                  return '';
              })
              ->addColumn('main_category', function ($motors) {
                  return isset($motors->get_subcat->mainCategory) ? $motors->get_subcat->mainCategory->name : '';
              })
              ->addColumn('sub_category', function ($motors) {
                  return isset($motors->get_subcat) ? $motors->get_subcat->name : '';
              })
              ->addColumn('publisher_name', function ($motors) {
                  if ($motors->is_publisher == "1") {
                      return $motors->publish_by->name;
                  }
                  return 'N/A';
              })
              ->addColumn('action', function ($motors) {
                  $btn = '<div class="btn-group" role="group">
                              <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                              <div class="dropdown-menu">
                                  <a class="dropdown-item" href="' . route('admin.motors.edit', $motors) . '">
                                          <i data-feather="edit"></i> Edit </a>
                                  <a class="dropdown-item" href="' . route('admin.motors.show', $motors->id) . '">
                                      <i data-feather="eye"></i> Preview </a>
                                  <a class="dropdown-item btn-icon modal-btn" data-href="' . route('admin.motors.destroy', $motors) . '" data-target="#remove" data-toggle="modal" type="button" href="javascript:void(0)">
                                                              <i data-feather="trash-2"></i> Delete </a>
                              </div>
                          </div>';

                  return $btn;
              })
              ->editColumn('created_at', function ($data) {
                  return Carbon::parse($data->created_at, 'Asia/Dubai')->timestamp;
              })
              ->rawColumns(['active', 'action'])
              ->make(true);
      }
      return view('admin.motors.index', get_defined_vars());
    }

    public function create()
    {
      $major_category = MajorCategory::find(13);
      $arr_moter_type = Helper::get_motor_type();
      $manufacturer = Manufacturer::where('status', 1)->orderBy('title')->pluck('title', 'id')->toArray();
      if($major_category && $major_category->amenities){
          $amenties = Amenties::whereIn('id', explode(',', $major_category->amenities))->orderBy('description')->pluck('description', 'id')->toArray();
      } else {
          $amenties = Amenties::orderBy('description')->pluck('description', 'id')->toArray();
      }

      if($major_category && $major_category->landmarks){
          $landmarks = Landmark::whereIn('id', explode(',', $major_category->landmarks))->orderBy('name')->pluck('name', 'id')->toArray();
      } else {
          $landmarks = Landmark::orderBy('name')->pluck('name', 'id')->toArray();
      }
      
      $main_categories = MainCategory::where('major_category_id', '13')->orderBy('name')->pluck('name', 'id')->toArray();

      $main_category_ids = [];
      $dynamic_main_category = DynamicMainCategory::where('major_category_id', 13)->get();
      $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id',$main_category_ids)->get();

      return view('admin.motors.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'manufacturer_id' => 'required',
                'title' => 'required|unique:motors,title',
                'slug' => 'required|unique:motors,slug',
                'location' => 'required',
                'feature_image_ids' => 'required',
                'accommodation_type' => 'required',
                'amenities.*' => 'distinct',
            ],
            [
                'feature_image_ids.required' => 'Feature/Profile image is required.',
                'sub_category_id.required' => 'Sub category is required.',
            ]
        );
        try {

            $validatedData = $request->post();

            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['lng'] = $request->citylong;
            $validatedData['amenity_id'] = $request->amenities ?? [];
            $validatedData['landmark_id'] = isset($request->landmark[0]['name']) ? $request->landmark : [];
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['contact'] = $validatedData['full_phone_contact'] ?? $validatedData['contact'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('motor-detail', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['assign_featured'] = $validatedData['assign_featured'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;
            $validatedData['youtube_img'] = $validatedData['youtube_img'] ?? '';

            if ($validatedData['youtube_img'] == 1) {
                $validatedData['video'] = $request->video;
            }

            $validatedData['amenties'] = $request->amenities ?? [];
            $validatedData['landmarks'] = isset($request->landmark[0]['name']) ? $request->landmark : [];
            $motor = Motors::create($validatedData);
            if ($motor) {
                $id = $motor->id;
                if ($request->feature_image_ids) {
                    $this->updateMotorImages($request, $id, $request->feature_image_ids);
                }
                if ($request->logo_ids) {
                    $this->updateMotorImages($request, $id, $request->logo_ids);
                }
                if ($request->main_image_ids) {
                    $this->updateMotorImages($request, $id, $request->main_image_ids);
                }
                if ($request->images_ids) {
                    $this->updateMotorImages($request, $id, $request->images_ids);
                }
                if ($request->stories_ids) {
                    $this->updateMotorImages($request, $id, $request->stories_ids);
                }
                if ($request->event) {
                    foreach ($request->event as $key => $value) {
                        MotorEvent::create([
                            'motor_id' => $id,
                            'name' => $value['name'],
                            'datetime' => $value['date'],
                        ]);
                    }
                }
                $response['error'] = false;
                $response['msg'] = 'Motor "' . $validatedData['title'] . '" was created successfully!';
                $response['primary_id'] = $motor->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add motor. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
          dd($e->getMessage());
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding motor. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    public function show(Motors $motor)
    {
        $motor->load(['subCategory', 'manufacturer', 'city', 'featureImage', 'storyImages', 'mainImages', 'logoImage', 'mainImage']);
        
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $motor->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }

        $amenties_motor = $motor->amenity_id ? $motor->amenity_id : [];
        $landmark_motor = $motor->landmark_id ? $motor->landmark_id : [];
        return view('admin.motors.show', compact('motor', 'youtube', 'amenties_motor', 'landmark_motor'));
    }

    public function edit(Motors $motor)
    {
        if (Auth::user()->user_type != 1 && Auth::id() != $motor->created_by) {
            abort(403);
        }
        $arr_moter_type = Helper::get_motor_type();
        $manufacturer = Manufacturer::where('status', 1)->orderBy('title')->pluck('title', 'id')->toArray();

        $more_info = MoreInfo::where(['module_id' => $motor->id, 'module_name' => 'motor'])->get();

        $amenties_motor = $motor->amenity_id;
        $landmark_motor = $motor->landmark_id;

        $major_category = MajorCategory::find(13);
        if($major_category && $major_category->amenities){
            $amenties = Amenties::whereIn('id', explode(',', $major_category->amenities))->orderBy('description')->pluck('description', 'id')->toArray();
        } else {
            $amenties = Amenties::orderBy('description')->pluck('description', 'id')->toArray();
        }

        if($major_category && $major_category->landmarks){
            $landmarks = Landmark::whereIn('id', explode(',', $major_category->landmarks))->orderBy('name')->pluck('name', 'id')->toArray();
        } else {
            $landmarks = Landmark::orderBy('name')->pluck('name', 'id')->toArray();
        }

        $main_categories = MainCategory::where('major_category_id', '=', '13')->orderBy('name')->pluck('name', 'id')->toArray();

        $subcatgories = SubCategory::where('main_category_id', '=', isset($motor->get_subcat->mainCategory) ? $motor->get_subcat->mainCategory->id : '')->orderBy('name')->pluck('name', 'id')->toArray();

        $primary_id = $motor->id;
      
        $featureImage = $motor->featureImage;
        $logoImage = $motor->logoImage;
        $mainImage = $motor->mainImage;
        $mainImages = $motor->mainImages;
        $storyImages = $motor->storyImages;

        return view('admin.motors.edit', get_defined_vars());
    }

    public function update(Request $request, Motors $motor)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'manufacturer_id' => 'required',
                'title' => 'required|min:1|max:100|'.Rule::unique('motors')->ignore($motor),
                'slug' => 'required|'.Rule::unique('motors')->ignore($motor),
                'location' => 'required',
                'feature_image_ids' => 'required',
                'accommodation_type' => 'required',
                'amenities.*' => 'distinct',
            ],
            [
                'feature_image_ids.required' => 'Feature/Profile image is required.',
                'sub_category_id.required' => 'Sub category is required.',
               
            ]
        );
        try {


            $validatedData = $request->post();

            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['lng'] = $request->citylong;
            $validatedData['amenity_id'] = $request->amenities ?? [];
            $validatedData['landmark_id'] = isset($request->landmark[0]['name']) ? $request->landmark : [];
            $validatedData['prices'] = $request->prices;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['contact'] = $validatedData['full_phone_contact'] ?? $validatedData['contact'];
            $validatedData['reservation'] = $validatedData['reservation'] ?? 0;
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('motor-detail', $validatedData['slug']);
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['assign_featured'] = $validatedData['assign_featured'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;
            $validatedData['youtube_img'] = $validatedData['youtube_img'] ?? '';

            if ($validatedData['youtube_img'] == 1) {
                $validatedData['video'] = $request->video;
            }
            if ($motor->update($validatedData)) {
                $id = $motor->id;
                if ($request->feature_image_ids) {
                    $this->updateMotorImages($request, $id, $request->feature_image_ids);
                }
                if ($request->logo_ids) {
                    $this->updateMotorImages($request, $id, $request->logo_ids);
                }
                if ($request->menu_ids) {
                    $this->updateMotorImages($request, $id, $request->menu_ids);
                }
                if ($request->floor_plan_ids) {
                    $this->updateMotorImages($request, $id, $request->floor_plan_ids);
                }
                if ($request->main_image_ids) {
                    $this->updateMotorImages($request, $id, $request->main_image_ids);
                }
                if ($request->images_ids) {
                    $this->updateMotorImages($request, $id, $request->images_ids);
                }
                if ($request->stories_ids) {
                    $this->updateMotorImages($request, $id, $request->stories_ids);
                }

                MotorEvent::where('motor_id', $id)->delete();
               // dd($request->event);
                if ($request->event) {
                    foreach ($request->event as $key => $value) {
                        MotorEvent::create([
                            'motor_id' => $id,
                            'name' => $value['name'],
                            'datetime' => $value['date'],
                        ]);
                    }
                }
                $request->module_id = $id;
                $request->module_name = 'motor';
                $request->user_type = 'admin';

                //sending notfication to particular for wish lists
                $wishlist_notifications = WishListDetails::where('item_type', 'App\Models\Motors')
                    ->where('item_id', '=', $id)
                    ->where('status', '=', "1")
                    ->where('created_by', '!=', Auth::user()->id)
                    ->where('is_notification_need', '=', "1")
                    ->get();

                $title_msg = "Motors Updated";
                $description = $request->title;
                $route = route('motor-detail', $motor->slug);
                Helper::send_notification_wishlist_guys($wishlist_notifications, $route, $title_msg, $description);
                //sending notfication to particular for wish lists

                $response['error'] = false;
                $response['msg'] = 'Motor "' . $validatedData['title'] . '" was updated successfully!';
                $response['primary_id'] = $motor->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to update motor. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
          dd($e->getMessage());
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while updating motor. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    public function destroy(Motors $motor)
    {
        $motor->delete();
       
        $message = [
            'message' => 'Motors Deleted Successfully',
            'alert-type' => 'success'
        ];

        # Redirect
        return redirect()->back()->with($message);
    }

    public function update_status_motor(Request $request)
    {
        $response['error'] = false;
        abort_if(!$request->id, 400);
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $motor = Motors::find($id);
        try {
            abort_if(!$motor, 404);
            if ($motor->update([$field => $value])) {
                if ($motor->is_publisher == "1" && $field === 'status') {

                    if ($value == "1") {
                        $satuts_label = "Approved";
                    } else {
                        $satuts_label = "Rejected";
                    }

                    $description_event = $satuts_label . " By Admin";
                    $message_event = "Admin {$satuts_label} motor list";
                    $url_now = route('publisher.motors.edit', $motor->id);

                    event(new MyEvent($message_event, $description_event, $url_now, "1", $motor->created_by));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_event;
                    $notification->description = $description_event;
                    $notification->notification_for = 1;
                    $notification->url = $url_now;
                    $notification->notify_to = $motor->created_by;
                    $notification->save();
                }
                $response['msg'] = 'Motor updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating motor. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return response()->json($response);
    }

    public function venueReservation(Request $request)
    {
        $datas = VenueReservation::with('venue')->get();
        return view('admin.venue.venue-reservation', compact('datas'));
    }

    public function save_more_info(Request $request)
    {

        $id = $request->primary_id;

        /*dd($request->videoLink);*/
        $request->module_id = $id;
        $request->module_name = 'motor';
        $request->user_type = 'admin';
        /*dd($request->input());*/
        $res = saveCommoncomponent($request);
        if ($res == false) {
            //    Log::info('Edit Venu Faild ID: '.$id);

            $message = [
                'message' => 'Venue More Info has some issue try again.!',
                'alert-type' => 'success'
            ];

            return redirect()->back()->with($message);
        }
        if (isset($request->delitems) && isset($request->delitems[0])) {
            $d = $request->delitems;
            $arr = explode(',', $d[0]);
            $delres = deleteMorinfo($arr);
        }

        $message = [
            'message' => 'Venue More Info Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function uploadPhotos(Request $request)
    {
        $photos = [];
        $image_type = $request->image_type;
        $images = $request->{$image_type};
        $feature_image_ids = isset($request->feature_image_ids) ? explode(', ', $request->feature_image_ids) : [];
        $logo_ids = isset($request->logo_ids) ? explode(', ', $request->logo_ids) : [];
        $images_ids = isset($request->images_ids) ? explode(', ', $request->images_ids) : [];
        $floor_plan_ids = isset($request->floor_plan_ids) ? explode(', ', $request->floor_plan_ids) : [];
        $menu_ids = isset($request->menu_ids) ? explode(', ', $request->menu_ids) : [];
        $main_image_ids = isset($request->main_image_ids) ? explode(', ', $request->main_image_ids) : [];
        foreach ($images as $key => $photo) {
            $filename = time() . '-' . $photo->getClientOriginalName();
            $photo_object = new \stdClass();
            $photo_object->name = $photo->getClientOriginalName();

            if ($image_type === 'main_image' && count($main_image_ids) >= 1) {
                $photo_object->errorMessage = 'Only one main image is allowed';
                $photos[] = $photo_object;
                return response()->json(array('files' => $photos));
            }
            if ($image_type === 'feature_image' && count($feature_image_ids) >= 1) {
                $photo_object->errorMessage = 'Only one feature image is allowed';
                $photos[] = $photo_object;
                return response()->json(array('files' => $photos));
            }
            if ($image_type === 'logo' && count($logo_ids) >= 1) {
                $photo_object->errorMessage = 'Only one logo image is allowed';
                $photos[] = $photo_object;
                return response()->json(array('files' => $photos));
            }
            if (in_array($image_type, ['feature_image', 'logo', 'stories', 'images', 'main_image'])) {
                if ($image_type === 'images' && count($images_ids) >= 4) {
                    $photo_object->errorMessage = 'Cannot upload more than 4 images';
                    $photos[] = $photo_object;
                    return response()->json(array('files' => $photos));
                }
                $height = Image::make($photo)->height();
                $width = Image::make($photo)->width();
                /*if ($image_type === 'logo' && ($width < 240 || $height < 200)) {
                    $photo_object->errorMessage = 'Required minimum image dimensions 240x200';
                    $photos[] = $photo_object;
                    return response()->json(array('files' => $photos));
                }

                if ($image_type !== 'logo' && ($width < 800 || $height < 475)) {
                    $photo_object->errorMessage = 'Required minimum image dimensions 800x475';
                    $photos[] = $photo_object;
                    return response()->json(array('files' => $photos));
                }*/

            }
            if ($photo->getSize() < (1024 * 1024)) {
                $destinationPath = config('app.upload_motor_path') . $image_type;
                $imageName = $destinationPath . '/' . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($photo), 'public');
                $blog_photo = MotorImage::create([
                    'image_type' => $image_type,
                    'image' => $filename
                ]);
                $photo_object->path = config('app.cloudfront_url') . $imageName;
                //$photo_object->size = $size;
                $photo_object->fileID = $blog_photo->id;
            } else {
                $photo_object->errorMessage = 'Required minimum file size < 1MB';
            }
            $photos[] = $photo_object;
        }
        return response()->json(array('files' => $photos));
    }

    public function deletePhoto(Request $request)
    {
        $response = [];
        try {
            abort_if(!$request->id, 404);
            $image = MotorImage::findOrFail($request->id);
            $tmp_obj = $image;
            if ($image->delete()) {
                if (file_exists(public_path($tmp_obj->image))) {
                    @unlink(public_path($tmp_obj->image));
                }
                $destinationPath = config('app.upload_motor_path') . '/' . $image->image_type;
                $imageName = $destinationPath . '/' . $tmp_obj->image;
                Storage::disk('s3')->delete($imageName);
                $response['error'] = false;
                $response['msg'] = 'Image deleted successfully!';
            } else {
                $response['error'] = false;
                $response['msg'] = 'There was a problem while deleting image. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return json_encode($response);
    }

    public function updateMotorImages(Request $request, $id, $imageIds)
    {
        foreach (explode(",", $imageIds) as $k => $img_id) {
            $venueImage = MotorImage::find($img_id);
            if ($venueImage) {
                $alt_texts['en'] =  $request->alt_text_en[$img_id] ?? '';
                $venueImage->update([
                    'motor_id' => $id,
                    'alt_texts' => $alt_texts,
                ]);
            }
        }
    }

    public function removeAmenity(Request $request)
    {
        $response = [];
        try {
            abort_if(!$request->id && $request->venue_id, 400);
            $venue = Venue::find($request->venue_id);
            abort_if(!$venue, 404);
            if ($venue->amenities()->whereId($request->id)->delete()) {
                $response['error'] = false;
                $response['msg'] = 'Amenity removed successfully!';
            } else {
                $response['error'] = false;
                $response['msg'] = 'There was a problem while removing amenity. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return json_encode($response);
    }

    public function removeLandmark(Request $request)
    {
        $response = [];
        try {
            $response = [];
            try {
                abort_if(!$request->id && $request->venue_id, 400);
                $venue = Venue::find($request->venue_id);
                abort_if(!$venue, 404);
                if ($venue->landmarks()->whereId($request->id)->delete()) {
                    $response['error'] = false;
                    $response['msg'] = 'Landmarks removed successfully!';
                } else {
                    $response['error'] = false;
                    $response['msg'] = 'There was a problem while removing landmark. Please try later.';
                }

            } catch (Exception $ex) {
                $response['error'] = true;
                $response['msg'] = $ex->getMessage();
            }
            return json_encode($response);

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return json_encode($response);
    }
}
