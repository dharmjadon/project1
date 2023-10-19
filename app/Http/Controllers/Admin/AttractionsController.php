<?php

namespace App\Http\Controllers\Admin;

use App\Events\MyEvent;
use App\Events\PublisherEvent;
use App\Models\Amenitable;
use App\Models\AttractionImages;
use App\Models\AttractionEvent;
use App\Models\Attraction;
use App\Models\Amenties;
use App\Models\Landmark;
use App\Models\Landmarkable;
use App\Models\MajorCategory;
use App\Models\SubCategory;
use App\Models\MoreInfo;
use App\Models\MainCategory;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NotificationsInfo;
use App\Models\WishListDetails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Helper;
use Illuminate\Validation\Rule;
use Image;
use DB;
use Yajra\DataTables\Facades\DataTables;

class AttractionsController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Admin|attractions-add', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Admin|attractions-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|attractions-update', ['only' => ['edit','update','update_status_attractions']]);
    }

    public function index(Request $request)
    {
        /* below code to fix existing attraction entries */
        $allattractions = Attraction::get();
        foreach($allattractions as $attraction) {
            $landmarks = $attraction->landmarks;
            $landmark = [];
            if(is_array($landmarks)) {
            foreach($landmarks as $k => $lm) {
                if(isset($lm['landmark_icon'])) {
                    $landmark[$k]['name'] = $lm['landmark_icon'];
                    $landmark[$k]['description'] = $lm['landmark_desc'];
                }
            }
            }
            if($landmark) {
                $attraction->update(['landmarks' => $landmark]);
            }
        }
        /***************************************/
        $pagetitle = 'Manage Contact Requests';
        $activeTab = 'contact-requests';
        $activeSubTab = 'list-contact-requests';
        $main_category = MainCategory::where('major_category_id', '=', '10')->orderBy('name')->pluck('name', 'id');

        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');
        if ($request->ajax()) {
            $attractions = Attraction::with('get_subcat.mainCategory')->orderby('id', 'desc');
            $auth_user_type = Auth::user()->user_type;
            if ($auth_user_type != 1) {
                $attractions = $attractions->where('created_by', Auth::id());
            }
            if ($main_categories) { // && empty($keyword)
                $attractions->orWhereHas('get_subcat.mainCategory', function($q) use ($main_categories) {
                    $q->whereIn('id', $main_categories);
                });
            }
            // Global search function
            if ($keyword) {
                $attractions->where(function ($query) use ($keyword) {
                    $query->where('attractions.title', 'LIKE', '%' . $keyword . '%');
                });
            }
            if (!empty($date_from) && empty($keyword)) {
                $attractions->where('attractions.created_at', '>', $date_from . " 00:00:00");
            }

            if (!empty($date_from) && empty($keyword)) {
                $attractions->where('attractions.created_at', '<=', $date_to . " 23:59:59");
            }
            
            $attractions = $attractions->latest()->get();
            return DataTables::of($attractions)
                ->addColumn('active', function($attraction) use ($auth_user_type) {
                    if ($auth_user_type === 1) {
                        return '<input type="checkbox" class="attraction-status" data-toggle="switch" data-size="small"
                    data-on-color="green" data-on-text="ON" data-off-color="default" data-off-text="OFF"
                    data-id="' . $attraction->id . '" value="' . $attraction->status . '" ' . ($attraction->status ? "checked" : "") . '>';
                    }
                    return '';
                })
                ->addColumn('main_category', function($attraction) {
                    return isset($attraction->get_subcat->mainCategory) ? $attraction->get_subcat->mainCategory->name : '';
                })
                ->addColumn('sub_category', function($attraction) {
                    return isset($attraction->get_subcat) ? $attraction->get_subcat->name : '';
                })
                ->addColumn('publisher_name', function($attraction) {
                    if($attraction->is_publisher=="1"){
                        return $attraction->publish_by->name;
                    }
                    return 'N/A';
                })
                ->addColumn('action', function ($attraction) {
                    $btn = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('admin.attractions.edit', $attraction->id) . '">
                                            <i data-feather="edit"></i> Edit </a>
                                    <a class="dropdown-item" href="' . route('admin.attractions.show', $attraction->id) . '">
                                        <i data-feather="eye"></i> Preview </a>
                                    <a class="dropdown-item btn-icon modal-btn" data-href="' . route('admin.attractions.destroy', $attraction) . '" data-target="#remove" data-toggle="modal" type="button" href="javascript:void(0)">
                                                                <i data-feather="trash-2"></i> Delete </a>
                                </div>
                            </div>';

                    return $btn;
                })
                ->editColumn('created_at', function($data){
                    return Carbon::parse($data->created_at, 'Asia/Dubai')->timestamp;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
        }
        return view('admin.attractions.index', get_defined_vars());
    }

    public function create()
    {
        $major_category = MajorCategory::find(10);
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
      
        $main_categories = MainCategory::where('major_category_id', '=', 10)->orderBy('name')->pluck('name', 'id')->toArray();
       
        return view('admin.attractions.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'title' => 'required|unique:attractions,title',
                'slug' => 'required|unique:attractions,slug',
                'location' => 'required',
                'feature_image_ids' => 'required',
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
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['mobile'] = $validatedData['full_phone_contact'] ?? $validatedData['mobile'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('attractions', $validatedData['slug']);
          
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_featured'] = $validatedData['is_featured'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;
            $validatedData['attraction_type '] = $validatedData['attraction_type '] ?? 1;
            $validatedData['youtube_img'] = $validatedData['youtube_img'] ?? '';

            if ($validatedData['youtube_img'] == 1) {
                $validatedData['video'] = $request->video;
            }

            $validatedData['amenties'] = $request->amenities ?? [];
            $validatedData['landmarks'] = isset($request->landmark[0]['name']) ? $request->landmark : [];
            
            $attraction = Attraction::create($validatedData);
            if ($attraction) {
                $id = $attraction->id;
                if ($request->feature_image_ids) {
                    $this->updateImages($request, $id, $request->feature_image_ids);
                }
                if ($request->logo_ids) {
                    $this->updateImages($request, $id, $request->logo_ids);
                }
                if ($request->menu_ids) {
                    $this->updateImages($request, $id, $request->menu_ids);
                }
                if ($request->floor_plan_ids) {
                    $this->updateImages($request, $id, $request->floor_plan_ids);
                }
                if ($request->main_image_ids) {
                    $this->updateImages($request, $id, $request->main_image_ids);
                }
                if ($request->images_ids) {
                    $this->updateImages($request, $id, $request->images_ids);
                }
                if ($request->stories_ids) {
                    $this->updateImages($request, $id, $request->stories_ids);
                }
                if ($request->event) {
                    foreach ($request->event as $key => $value) {
                        AttractionEvent::create([
                            'attraction_id' => $id,
                            'name' => $value['name'],
                            'datetime' => $value['date'],
                        ]);
                    }
                }

                $response['error'] = false;
                $response['msg'] = 'Attraction "' . $validatedData['title'] . '" was created successfully!';
                $response['primary_id'] = $attraction->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add attraction. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding attraction. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    public function edit(Attraction $attraction)
    {
        if (Auth::user()->user_type != 1 && Auth::id() != $attraction->created_by) {
            abort(403);
        }

        $more_info = MoreInfo::where(['module_id' => $attraction->id, 'module_name' => 'attraction'])->get();
        $amenties_attraction = $attraction->amenties ?? [];
        $landmark_attraction = $attraction->landmarks ?? [];
        
        $major_category = MajorCategory::find(10);
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

        $main_categories = MainCategory::where('major_category_id', '=', '10')->orderBy('name')->pluck('name', 'id')->toArray();

        $subcatgories = SubCategory::where('main_category_id', '=', isset($attraction->get_subcat->mainCategory) ? $attraction->get_subcat->mainCategory->id : '')->orderBy('name')->pluck('name', 'id')->toArray();

        $primary_id = $attraction->id;
      
        $featureImage = $attraction->featureImage;
        $logoImage = $attraction->logoImage;
        $mainImage = $attraction->mainImage;
        $mainImages = $attraction->mainImages;
        $storyImages = $attraction->storyImages;

        return view('admin.attractions.edit', get_defined_vars());
    }

    public function update(Request $request, Attraction $attraction)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'title' => 'required|min:1|max:100|'.Rule::unique('attractions')->ignore($attraction),
                'slug' => 'required|'.Rule::unique('attractions')->ignore($attraction),
                'location' => 'required',
                'feature_image_ids' => 'required',
                'amenities.*' => 'distinct',
            ],
            [
                'feature_image_ids.required' => 'Feature/Profile image is required.',
                'sub_category_id.required' => 'Sub category is required.',
            ]
        );

        try {

            $validatedData = $request->post();
            //amenties work
            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['lng'] = $request->citylong;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['mobile'] = $validatedData['full_phone_contact'] ?? $validatedData['mobile'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('attractions', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_featured'] = $validatedData['is_featured'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;
            $validatedData['youtube_img'] = $validatedData['youtube_img'] ?? '';
            $validatedData['attraction_type'] = $validatedData['attraction_type'] ?? 1;
            if ($validatedData['youtube_img'] == 1) {
                $validatedData['video'] = $request->video;
            }

            $validatedData['amenties'] = $request->amenities ?? [];
            $validatedData['landmarks'] = $request->landmark ?? [];

            if ($attraction->update($validatedData)) {
                $id = $attraction->id;
                if ($request->feature_image_ids) {
                    $this->updateImages($request, $id, $request->feature_image_ids);
                }
                if ($request->logo_ids) {
                    $this->updateImages($request, $id, $request->logo_ids);
                }
                if ($request->menu_ids) {
                    $this->updateImages($request, $id, $request->menu_ids);
                }
                if ($request->floor_plan_ids) {
                    $this->updateImages($request, $id, $request->floor_plan_ids);
                }
                if ($request->main_image_ids) {
                    $this->updateImages($request, $id, $request->main_image_ids);
                }
                if ($request->images_ids) {
                    $this->updateImages($request, $id, $request->images_ids);
                }
                if ($request->stories_ids) {
                    $this->updateImages($request, $id, $request->stories_ids);
                }

                AttractionEvent::where('attraction_id', $id)->delete();
                if ($request->event) {
                    foreach ($request->event as $key => $value) {
                        AttractionEvent::create([
                            'attraction_id' => $id,
                            'name' => $value['name'],
                            'datetime' => $value['date'],
                        ]);
                    }
                }

                $request->module_id = $id;
                $request->module_name = 'attraction';
                $request->user_type = 'admin';

                //sending notfication to particular for wish lists
                $wishlist_notifications = WishListDetails::where('item_type', 'App\Models\Attraction')
                    ->where('item_id', '=', $id)
                    ->where('status', '=', "1")
                    ->where('created_by', '!=', Auth::user()->id)
                    ->where('is_notification_need', '=', "1")
                    ->get();

                $title_msg = "Attraction Updated";
                $description = $request->title;
                $route = route('attractions', $attraction->slug);
                Helper::send_notification_wishlist_guys($wishlist_notifications, $route, $title_msg, $description);
                //sending notfication to particular for wish lists

                $response['error'] = false;
                $response['msg'] = 'Attraction "' . $validatedData['title'] . '" was updated successfully!';
                $response['primary_id'] = $attraction->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to update attraction. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            $response['error'] = true;
            $response['msg'] = 'There was a problem while updating attraction. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    public function show(Attraction $attraction)
    {
        $attraction->load(['subCategory', 'city', 'featureImage', 'storyImages', 'mainImages', 'logoImage', 'mainImage']);
        
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $attraction->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }

        $amenties_attraction = $attraction->amenties;
        $landmark_attraction = $attraction->landmarks;
        return view('admin.attractions.preview', get_defined_vars());
    }

    public function destroy(Attraction $attraction)
    {
        $attraction->delete();

        $message = [
            'message' => 'Attraction Deleted Successfully',
            'alert-type' => 'success'
        ];

        # Redirect
        return redirect()->back()->with($message);
    }

    public function save_more_info(Request $request)
    {
        $id = $request->primary_id;

        $request->module_id = $id;
        $request->module_name = 'attraction';
        $request->user_type = 'admin';
        /*dd($request->input());*/
        $res = saveCommoncomponent($request);
        if ($res == false) {
            // DB::rollback();

            $message = [
                'message' => "Attraction more info has some issues try again.!",
                'alert-type' => 'error',
            ];

            return redirect()->back()->with($message);

        }
        if (isset($request->delitems) && isset($request->delitems[0])) {
            $d = $request->delitems;
            $arr = explode(',', $d[0]);
            $delres = deleteMorinfo($arr);

        }

        $message = [
            'message' => "Attraction more info has been save successfully",
            'alert-type' => 'success',
        ];
        return redirect()->back()->with($message);
    }

    public function ajax_render_subcategory(Request $request)
    {
        $sub_cat = SubCategory::where('main_category_id', '=', $request->select_v)->get();
        echo json_encode($sub_cat);
        exit;
    }

    public function update_status_attractions(Request $request)
    {
        $response['error'] = false;
        abort_if(!$request->id, 400);
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $attraction = Attraction::find($id);
        try {
            abort_if(!$attraction, 404);
            if ($attraction->update([$field => $value])) {
                if ($attraction->is_publisher == "1" && $field === 'status') {

                    if ($request->status == "1") {
                        $satuts_label = "Approved";
                    } else {
                        $satuts_label = "Rejected";
                    }

                    $description_attraction = $satuts_label . " By Admin";
                    $message_attraction = "Admin {$satuts_label} attraction list";
                    $url_now = route('publisher.attractions.edit', $attraction->id);

                    event(new MyEvent($message_attraction, $description_attraction, $url_now, "1", $attraction->created_by));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_attraction;
                    $notification->description = $description_attraction;
                    $notification->notification_for = 1;
                    $notification->url = $url_now;
                    $notification->notify_to = $attraction->created_by;
                    $notification->save();
                }
                $response['msg'] = 'Attraction updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating attraction. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return response()->json($response);
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

            if ($image_type === 'floor_plan' && count($floor_plan_ids) >= 1) {
                $photo_object->errorMessage = 'Only one floor plan is allowed';
                $photos[] = $photo_object;
                return response()->json(array('files' => $photos));
            }
            if ($image_type === 'menu' && count($menu_ids) >= 1) {
                $photo_object->errorMessage = 'Only one menu is allowed';
                $photos[] = $photo_object;
                return response()->json(array('files' => $photos));
            }
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
            }
            if ($photo->getSize() < (1024 * 1024)) {
                $destinationPath = config('app.upload_attraction_path') . $image_type;
                $imageName = $destinationPath . '/' . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($photo), 'public');
                $blog_photo = AttractionImages::create([
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
            $image = AttractionImages::findOrFail($request->id);
            $tmp_obj = $image;
            if ($image->delete()) {
                if (file_exists(public_path($tmp_obj->image))) {
                    @unlink(public_path($tmp_obj->image));
                }
                $destinationPath = config('app.upload_attraction_path') . '/' . $image->image_type;
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

    public function updateImages(Request $request, $id, $imageIds)
    {
        foreach (explode(",", $imageIds) as $k => $img_id) {
            $AttractionImages = AttractionImages::find($img_id);
            if ($AttractionImages) {
                $alt_texts['en'] =  $request->alt_text_en[$img_id] ?? '';
                $AttractionImages->update([
                    'attraction_id' => $id,
                    'alt_texts' => $alt_texts,
                ]);
            }
        }
    }
}
