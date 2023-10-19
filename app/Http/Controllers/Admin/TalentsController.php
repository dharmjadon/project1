<?php

namespace App\Http\Controllers\Admin;

use App\Events\MyEvent;
use App\Events\PublisherEvent;
use App\Models\TalentImages;
use App\Models\Talents;
use App\Models\MajorCategory;
use App\Models\SubCategory;
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

class TalentsController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Admin|talents-add', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Admin|talents-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|talents-update', ['only' => ['edit', 'update', 'update_status_talents']]);
    }

    public function index(Request $request)
    {
        $pagetitle = 'Manage Contact Requests';
        $activeTab = 'contact-requests';
        $activeSubTab = 'list-contact-requests';
        $main_category = MainCategory::where('major_category_id', '=', '17')->orderBy('name')->pluck('name', 'id');

        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');
        if ($request->ajax()) {
            $talents = Talents::with('get_subcat.mainCategory')->orderby('id', 'desc');
            $auth_user_type = Auth::user()->user_type;
            if ($auth_user_type != 1) {
                $talents = $talents->where('created_by', Auth::id());
            }
            if ($main_categories) {
                $talents->orWhereHas('get_subcat.mainCategory', function ($q) use ($main_categories) {
                    $q->whereIn('id', $main_categories);
                });
            }

            if ($keyword) {
                $talents->where(function ($query) use ($keyword) {
                    $query->where('talents.title', 'LIKE', '%' . $keyword . '%');
                });
            }
            if (!empty($date_from) && empty($keyword)) {
                $talents->where('talents.created_at', '>', $date_from . " 00:00:00");
            }

            if (!empty($date_from) && empty($keyword)) {
                $talents->where('talents.created_at', '<=', $date_to . " 23:59:59");
            }

            $talents = $talents->latest()->get();
            return DataTables::of($talents)
                ->addColumn('active', function ($talent) use ($auth_user_type) {
                    if ($auth_user_type === 1) {
                        return '<input type="checkbox" class="talent-status" data-toggle="switch" data-size="small"
                    data-on-color="green" data-on-text="ON" data-off-color="default" data-off-text="OFF"
                    data-id="' . $talent->id . '" value="' . $talent->status . '" ' . ($talent->status ? "checked" : "") . '>';
                    }
                    return '';
                })
                ->addColumn('main_category', function ($talent) {
                    return isset($talent->get_subcat->mainCategory) ? $talent->get_subcat->mainCategory->name : '';
                })
                ->addColumn('sub_category', function ($talent) {
                    return isset($talent->get_subcat) ? $talent->get_subcat->name : '';
                })
                ->addColumn('publisher_name', function ($talent) {
                    if ($talent->is_publisher == "1") {
                        return $talent->publish_by->name;
                    }
                    return 'N/A';
                })
                ->addColumn('action', function ($talent) {
                    $btn = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('admin.talents.edit', $talent->id) . '">
                                            <i data-feather="edit"></i> Edit </a>
                                    <a class="dropdown-item" href="' . route('admin.talents.show', $talent->id) . '">
                                        <i data-feather="eye"></i> Preview </a>
                                    <a class="dropdown-item btn-icon modal-btn" data-href="' . route('admin.talents.destroy', $talent) . '" data-target="#remove" data-toggle="modal" type="button" href="javascript:void(0)">
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
        return view('admin.talents.index', get_defined_vars());
    }

    public function create()
    {
        $array_social_name = Helper::get_social_name();
        $major_category = MajorCategory::find(17);
        $main_categories = MainCategory::where('major_category_id', '=', 17)->orderBy('name')->pluck('name', 'id')->toArray();

        return view('admin.talents.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'title' => 'required|unique:talents,title',
                'slug' => 'required|unique:talents,slug',
                'location' => 'required',
                'feature_image_ids' => 'required',
            ],
            [
                'feature_image_ids.required' => 'Feature/Profile image is required.',
                'sub_category_id.required' => 'Sub category is required.',
            ]);

        //social links
        $social_name_save_array = [];
        $social_array_name = $request->social_name_array;

        $social_array = explode(",", $social_array_name);
        foreach ($social_array as $ids) {
            $social_name = "social_name_" . $ids;
            $social_link = "social_link_" . $ids;

            if (isset($request->$social_name)) {
                $array_to_soacial_name = array(
                    'social_name' => $request->$social_name,
                    'social_link' => $request->$social_link,
                );
                $social_name_save_array [] = $array_to_soacial_name;
            }
        }



        //social links
        $youtube_name_save_array = [];
        $youtube_name_array = $request->youtube_name_array;

        $youtube_array = explode(",", $youtube_name_array);
        foreach ($youtube_array as $ids) {
            $youtube_url = "youtube_url_" . $ids;
            if (isset($request->$youtube_url)) {
                $array_to_youtube_name = array(
                    'youtube_url' => $request->$youtube_url,
                );
                $youtube_name_save_array [] = $array_to_youtube_name;
            }
        }
        //dd( $youtube_array, $youtube_name_save_array);
        try {
            $validatedData = $request->post();
            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['lng'] = $request->citylong;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['mobile'] = $validatedData['full_phone_contact'] ?? $validatedData['mobile'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('talent', $validatedData['slug']);

            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_featured'] = $validatedData['is_featured'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;
            $validatedData['social_links'] = $social_name_save_array;
            $validatedData['video'] = $youtube_name_save_array;

            $talent = Talents::create($validatedData);
            if ($talent) {
                $id = $talent->id;
                if ($request->feature_image_ids) {
                    $this->updateImages($request, $id, $request->feature_image_ids);
                }
                if ($request->logo_ids) {
                    $this->updateImages($request, $id, $request->logo_ids);
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
                $response['msg'] = 'Talent "' . $validatedData['title'] . '" was created successfully!';
                $response['primary_id'] = $talent->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add talent. Please try later.';
                return response()->json($response, 500);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding talent. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    public function edit(Talents $talent)
    {

        if (Auth::user()->user_type != 1 && Auth::id() != $talent->created_by) {
            abort(403);
        }
        $array_social_name = Helper::get_social_name();
        $social_links = $talent->social_links ?? [];
        $youtube_urls = $talent->video ?? [];

        //dd($social_links,$youtube_urls);
        $main_categories = MainCategory::where('major_category_id', '=', '17')->orderBy('name')->pluck('name', 'id')->toArray();

        $subcatgories = SubCategory::where('main_category_id', '=', isset($talent->get_subcat->mainCategory) ? $talent->get_subcat->mainCategory->id : '')->orderBy('name')->pluck('name', 'id')->toArray();

        $primary_id = $talent->id;
        $featureImage = $talent->featureImage;
        $logoImage = $talent->logoImage;
        $mainImage = $talent->mainImage;
        $mainImages = $talent->mainImages;
        $storyImages = $talent->storyImages;

        return view('admin.talents.edit', get_defined_vars());
    }

    public function update(Request $request, Talents $talent)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'title' => 'required|min:1|max:100|' . Rule::unique('talents')->ignore($talent),
                'slug' => 'required|' . Rule::unique('talents')->ignore($talent),
                'location' => 'required',
                //'feature_image_ids' => 'required',
            ],
            [
                //'feature_image_ids.required' => 'Feature/Profile image is required.',
                'sub_category_id.required' => 'Sub category is required.',
            ]);
        //social links
        $social_array_name = $request->social_name_array;
        $social_array = explode(",", $social_array_name);

        $social_name_save_array = array();

        foreach ($social_array as $ids) {
            $social_name = "social_name_" . $ids;
            $social_link = "social_link_" . $ids;

            if (isset($request->$social_name)) {
                $array_to_soacial_name = array(
                    'social_name' => $request->$social_name,
                    'social_link' => $request->$social_link,
                );
                $social_name_save_array[] = $array_to_soacial_name;
            }
        }

        

        //social links
        $youtube_array_name = $request->youtube_name_array;
        $youtube_array = explode(",", $youtube_array_name);
//dd( $youtube_array_name);
        $youtube_name_save_array = array();

        foreach ($youtube_array as $ids) {
            $youtube_url = "youtube_url_" . $ids;
           
            if (isset($request->$youtube_url)) {
                $array_to_youtube_name = array(
                    'youtube_url' => $request->$youtube_url,
                );
                $youtube_name_save_array[] = $array_to_youtube_name;
            }
        }
        //dd($youtube_name_save_array);
        try {
            $validatedData = $request->post();

            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['lng'] = $request->citylong;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['mobile'] = $validatedData['full_phone_contact'] ?? $validatedData['mobile'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('talent', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_featured'] = $validatedData['is_featured'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;
            $validatedData['youtube_img'] = $validatedData['youtube_img'] ?? '';
            if (isset($social_name_save_array)) {
                $validatedData['social_links'] = $social_name_save_array;
            }
            if (isset($youtube_name_save_array)) {
                $validatedData['video'] = $youtube_name_save_array;
            }

            //dd($validatedData);
            if ($talent->update($validatedData)) {
                $id = $talent->id;
                if ($request->feature_image_ids) {
                    $this->updateImages($request, $id, $request->feature_image_ids);
                }
                if ($request->logo_ids) {
                    $this->updateImages($request, $id, $request->logo_ids);
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

                $request->module_id = $id;
                $request->module_name = 'talent';
                $request->user_type = 'admin';

                $wishlist_notifications = WishListDetails::where('item_type', 'App\Models\Talents')
                    ->where('item_id', '=', $id)
                    ->where('status', '=', "1")
                    ->where('created_by', '!=', Auth::user()->id)
                    ->where('is_notification_need', '=', "1")
                    ->get();

                $title_msg = "Talent Updated";
                $description = $request->title;
                $route = route('talent', $talent->slug);
                Helper::send_notification_wishlist_guys($wishlist_notifications, $route, $title_msg, $description);

                $response['error'] = false;
                $response['msg'] = 'Talent "' . $validatedData['title'] . '" was updated successfully!';
                $response['primary_id'] = $talent->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to update talent. Please try later.';
                return response()->json($response, 500);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            $response['error'] = true;
            $response['msg'] = 'There was a problem while updating talent. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    public function show(Talents $talent)
    {
        $talent->load(['subCategory', 'city', 'featureImage', 'storyImages', 'mainImages', 'logoImage', 'mainImage']);

        return view('admin.talents.preview', get_defined_vars());
    }

    public function destroy(Talents $talent)
    {
        $talent->delete();

        $message = [
            'message' => 'Talent Deleted Successfully',
            'alert-type' => 'success'
        ];

        # Redirect
        return redirect()->back()->with($message);
    }

    public function ajax_render_subcategory(Request $request)
    {
        $sub_cat = SubCategory::where('main_category_id', '=', $request->select_v)->get();
        echo json_encode($sub_cat);
        exit;
    }

    public function update_status_talents(Request $request)
    {
        $response['error'] = false;
        abort_if(!$request->id, 400);
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $talent = Talents::find($id);
        try {
            abort_if(!$talent, 404);
            if ($talent->update([$field => $value])) {
                if ($talent->is_publisher == "1" && $field === 'status') {

                    if ($request->status == "1") {
                        $satuts_label = "Approved";
                    } else {
                        $satuts_label = "Rejected";
                    }

                    $description_talent = $satuts_label . " By Admin";
                    $message_talent = "Admin {$satuts_label} talent list";
                    $url_now = route('publisher.talents.edit', $talent->id);

                    event(new MyEvent($message_talent, $description_talent, $url_now, "1", $talent->created_by));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_talent;
                    $notification->description = $description_talent;
                    $notification->notification_for = 1;
                    $notification->url = $url_now;
                    $notification->notify_to = $talent->created_by;
                    $notification->save();
                }
                $response['msg'] = 'Talent updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating talent. Please try later.';
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
                $destinationPath = config('app.upload_talent_path') . $image_type;
                $imageName = $destinationPath . '/' . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($photo), 'public');
                $blog_photo = TalentImages::create([
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
            $image = TalentImages::findOrFail($request->id);
            $tmp_obj = $image;
            if ($image->delete()) {
                if (file_exists(public_path($tmp_obj->image))) {
                    @unlink(public_path($tmp_obj->image));
                }
                $destinationPath = config('app.upload_talent_path') . '/' . $image->image_type;
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
            $TalentImages = TalentImages::find($img_id);
            if ($TalentImages) {
                $alt_texts['en'] = $request->alt_text_en[$img_id] ?? '';
                $TalentImages->update([
                    'talent_id' => $id,
                    'alt_texts' => $alt_texts,
                ]);
            }
        }
    }
}
