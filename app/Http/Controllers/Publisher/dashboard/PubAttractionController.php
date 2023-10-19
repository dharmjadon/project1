<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Events\MyEvent;
use App\Http\Controllers\Controller;
use App\Models\Amenitable;
use App\Models\Amenties;
use App\Models\AttractionImages;
use App\Models\Attraction;
use App\Models\Landmark;
use App\Models\Landmarkable;
use App\Models\MainCategory;
use App\Models\MajorCategory;
use App\Models\MoreInfo;
use App\Models\NotificationsInfo;
use App\Models\PopularPlacesTypes;
use App\Models\SubCategory;
use App\Models\WishListDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Helper;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Image;
class PubAttractionController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Admin|publisher-user-attraction', ['only' =>
            ['index', 'edit', 'update', 'destroy']]);
    }

    public function index(Request $request)
    {
        $main_category = MainCategory::where('major_category_id', '=', '10')->orderBy('name')->pluck('name', 'id');

        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');
        if ($request->ajax()) {
            $attractions = Attraction::with('get_subcat.mainCategory')->orderby('id','desc');
            $attractions = $attractions->where('created_by', Auth::id());
           
            if ($main_categories) { // && empty($keyword)
                $attractions->orWhereHas('get_subcat.mainCategory', function ($q) use ($main_categories) {
                    $q->whereIn('id', $main_categories);
                });
            }

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
                ->editColumn('status', function ($attraction) {
                    return $attraction->status == 0 ? 'InActive' : 'Active';
                })
                ->editColumn('is_draft', function ($attraction) {
                    return $attraction->is_draft == 0 ? 'No' : 'Yes';
                })
                ->addColumn('main_category', function ($attraction) {
                    return isset($attraction->get_subcat->mainCategory) ? $attraction->get_subcat->mainCategory->name : '';
                })
                ->addColumn('sub_category', function ($attraction) {
                    return isset($attraction->get_subcat) ? $attraction->get_subcat->name : '';
                })
                ->addColumn('action', function ($attraction) {
                    $btn = '<div class="btn-group" role="group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('publisher.attractions.edit', $attraction->id) . '">
                                        <i data-feather="edit"></i> Edit </a>
                                <a class="dropdown-item btn-icon modal-btn" data-href="' . route('admin.attractions.destroy', $attraction) . '" data-target="#remove" data-toggle="modal" type="button" href="javascript:void(0)">
                                                                <i data-feather="trash-2"></i> Delete </a>
                            </div>
                        </div>';
                    return $btn;
                })
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at, 'Asia/Dubai')->timestamp;
                })
                ->rawColumns(['', 'action'])
                ->make(true);
        }
        return view('publisher.attractions.index', get_defined_vars());
    }

    public function create()
    {
        $major_category = MajorCategory::find(10);
        if ($major_category && $major_category->amenities) {
            $amenties = Amenties::whereIn('id', explode(',', $major_category->amenities))->orderBy('description')->pluck('description', 'id')->toArray();
        } else {
            $amenties = Amenties::orderBy('description')->pluck('description', 'id')->toArray();
        }

        if ($major_category && $major_category->landmarks) {
            $landmarks = Landmark::whereIn('id', explode(',', $major_category->landmarks))->orderBy('name')->pluck('name', 'id')->toArray();
        } else {
            $landmarks = Landmark::orderBy('name')->pluck('name', 'id')->toArray();
        }
       
        $main_categories = MainCategory::where('major_category_id', '=', 10)->orderBy('name')->pluck('name', 'id')->toArray();
        
        return view('publisher.attractions.create', get_defined_vars());
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
            $validatedData['is_publisher'] = 1;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_featured'] = $validatedData['is_featured'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;
            $validatedData['youtube_img'] = $validatedData['youtube_img'] ?? '';

            if ($validatedData['youtube_img'] == 1) {
                $validatedData['video'] = $request->video;
            }

            $validatedData['amenties'] = $request->amenities ?? [];
            $validatedData['landmarks'] = isset($request->landmark[0]['name']) ? $request->landmark : [];
            $validatedData['is_draft'] = $request->is_draft;
            $validatedData['is_publisher'] = "1";

            $attraction =Attraction::create($validatedData);
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

                $response['error'] = false;
                $response['msg'] = 'Attraction "' . $validatedData['title'] . '" was created successfully!';
                $response['primary_id'] = $attraction->id;

                $user = Auth::user();
                if ($user->hasRole('publisher-user-attraction')) {

                } else {
                    $user->assignRole('publisher-user-attraction');
                }

                if ($request->is_draft == "0") {

                    $description_attraction = "Submitted by" . ' ' . Auth::user()->name;
                    $message_attraction = "Publisher submitted a new attraction";
                    $url_now = route('admin.attractions.edit', $attraction->id);

                    event(new MyEvent($message_attraction, $description_attraction, $url_now, "0"));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_attraction;
                    $notification->description = $description_attraction;
                    $notification->notification_for = 0;
                    $notification->url = $url_now;
                    $notification->save();
                }
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

    public function save_more_info(Request $request)
    {
        $id = $request->primary_id;

        $request->module_id = $id;
        $request->module_name = 'attraction';
        $request->user_type = 'publisher';
        
        $res = saveCommoncomponent($request);
        if ($res == false) {
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

    public function edit($id)
    {
        $attraction = Attraction::find($id);
        if (Auth::user()->user_type != 1 && Auth::id() != $attraction->created_by) {
            abort(403);
        }

        $more_info = MoreInfo::where(['module_id' => $attraction->id, 'module_name' => 'attraction'])->get();
        
        $amenties_attraction = $attraction->amenties;
        $landmark_attraction = $attraction->landmarks;

        $major_category = MajorCategory::find(10);
        if ($major_category && $major_category->amenities) {
            $amenties = Amenties::whereIn('id', explode(',', $major_category->amenities))->orderBy('description')->pluck('description', 'id')->toArray();
        } else {
            $amenties = Amenties::orderBy('description')->pluck('description', 'id')->toArray();
        }

        if ($major_category && $major_category->landmarks) {
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

        return view('publisher.attractions.edit', get_defined_vars());
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

            if ($validatedData['youtube_img'] == 1) {
                $validatedData['video'] = $request->video;
            }

            $validatedData['amenties'] = $request->amenities ?? [];
            $validatedData['landmarks'] = $request->landmark ?? [];
            $validatedData['is_publisher'] = 1;

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
                if ($attraction->is_draft == "1" && $request->is_draft == "0") {
                    $description_attraction = "Submitted by" . ' ' . Auth::user()->name;
                    $message_attraction = "Publisher submitted a new attraction";
                    $url_now = route('admin.attractions.edit', $attraction->id);
                    event(new MyEvent($message_attraction, $description_attraction, $url_now, "0"));
                    $notification = new NotificationsInfo();
                    $notification->title = $message_attraction;
                    $notification->description = $description_attraction;
                    $notification->notification_for = 0;
                    $notification->url = $url_now;
                    $notification->save();
                }

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
            $response['error'] = true;
            $response['msg'] = 'There was a problem while updating attraction. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
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
