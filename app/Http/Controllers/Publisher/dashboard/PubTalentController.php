<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Events\MyEvent;
use App\Http\Controllers\Controller;
use App\Models\TalentImages;
use App\Models\Talents;
use App\Models\MainCategory;
use App\Models\MajorCategory;
use App\Models\NotificationsInfo;
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

class PubTalentController extends Controller
{
    function __construct()
    {
        // $this->middleware('role_or_permission:Admin|publisher-user-talent', ['only' =>
        //     ['index', 'edit', 'update', 'destroy']]);
    }

    public function index(Request $request)
    {
        $main_category = MainCategory::where('major_category_id', '=', '17')->orderBy('name')->pluck('name', 'id');

        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');
        if ($request->ajax()) {
            $talents = Talents::with('get_subcat.mainCategory')->orderby('id','desc');
            $talents = $talents->where('created_by', Auth::id());

            if ($main_categories) { // && empty($keyword)
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
                ->editColumn('status', function ($talent) {
                    return $talent->status == 0 ? 'InActive' : 'Active';
                })
                ->editColumn('is_draft', function ($talent) {
                    return $talent->is_draft == 0 ? 'No' : 'Yes';
                })
                ->addColumn('main_category', function ($talent) {
                    return isset($talent->get_subcat->mainCategory) ? $talent->get_subcat->mainCategory->name : '';
                })
                ->addColumn('sub_category', function ($talent) {
                    return isset($talent->get_subcat) ? $talent->get_subcat->name : '';
                })
                ->addColumn('action', function ($talent) {
                    $btn = '<div class="btn-group" role="group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('publisher.talents.edit', $talent->id) . '">
                                        <i data-feather="edit"></i> Edit </a>
                                <a class="dropdown-item btn-icon modal-btn" data-href="' . route('admin.talents.destroy', $talent) . '" data-target="#remove" data-toggle="modal" type="button" href="javascript:void(0)">
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
        return view('publisher.talents.index', get_defined_vars());
    }

    public function create()
    {
        $array_social_name = Helper::get_social_name();
        $major_category = MajorCategory::find(17);

        $main_categories = MainCategory::where('major_category_id', '=', 17)->orderBy('name')->pluck('name', 'id')->toArray();

        return view('publisher.talents.create', get_defined_vars());
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
            ]
        );
         //social links
        $socail_name_save_array = [];
        $socail_array_name = $request->social_name_array;

        $socail_array = explode(",",$socail_array_name);
        foreach($socail_array as $ids){
            $social_name = "social_name_".$ids;
            $social_link = "social_link_".$ids;

            if(isset($request->$social_name)){
            $array_to_soacial_name = array(
               'social_name' => $request->$social_name,
               'social_link' => $request->$social_link,
            );
            $socail_name_save_array [] = $array_to_soacial_name;
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
            $validatedData['is_draft'] = $validatedData['is_draft'] ?? 0;
            $validatedData['is_publisher'] = "1";
            $validatedData['social_links'] = $socail_name_save_array;
            $validatedData['video'] = $youtube_name_save_array;

            $talent =Talents::create($validatedData);
            if ($talent) {
                $id = $talent->id;
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
                $response['msg'] = 'Talent "' . $validatedData['title'] . '" was created successfully!';
                $response['primary_id'] = $talent->id;

                $user = Auth::user();
                // if ($user->hasRole('publisher-user-talent')) {
                // } else {
                //     $user->assignRole('publisher-user-talent');
                // }

                if ($request->is_draft == "0") {

                    $description_talent = "Submitted by" . ' ' . Auth::user()->name;
                    $message_talent = "Publisher submitted a new talent";
                    $url_now = route('admin.talents.edit', $talent->id);

                    event(new MyEvent($message_talent, $description_talent, $url_now, "0"));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_talent;
                    $notification->description = $description_talent;
                    $notification->notification_for = 0;
                    $notification->url = $url_now;
                    $notification->save();
                }
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

    public function edit($id)
    {
        $talent = Talents::find($id);
        if (Auth::user()->user_type != 1 && Auth::id() != $talent->created_by) {
            abort(403);
        }

        $major_category = MajorCategory::find(17);
        $array_social_name = Helper::get_social_name();
        $social_links = $talent->social_links ?? [];
        $youtube_urls = $talent->video ?? [];

        //dd($youtube_urls);
        $main_categories = MainCategory::where('major_category_id', '=', '17')->orderBy('name')->pluck('name', 'id')->toArray();

        $subcatgories = SubCategory::where('main_category_id', '=', isset($talent->get_subcat->mainCategory) ? $talent->get_subcat->mainCategory->id : '')->orderBy('name')->pluck('name', 'id')->toArray();

        $primary_id = $talent->id;

        $featureImage = $talent->featureImage;
        $logoImage = $talent->logoImage;
        $mainImage = $talent->mainImage;
        $mainImages = $talent->mainImages;
        $storyImages = $talent->storyImages;

        return view('publisher.talents.edit', get_defined_vars());
    }

    public function update(Request $request, Talents $talent)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'title' => 'required|min:1|max:100|'.Rule::unique('talents')->ignore($talent),
                'slug' => 'required|'.Rule::unique('talents')->ignore($talent),
                'location' => 'required',
                'feature_image_ids' => 'required',
            ],
            [
                'feature_image_ids.required' => 'Feature/Profile image is required.',
                'sub_category_id.required' => 'Sub category is required.',
            ]
        );
          //social links
          $socail_array_name = $request->social_name_array;
          $socail_array = explode(",",$socail_array_name);

          $socail_name_save_array = array();

          foreach($socail_array as $ids){
              $social_name = "social_name_".$ids;
              $social_link = "social_link_".$ids;

              if(isset($request->$social_name)){
                $array_to_soacial_name = array(
                    'social_name' => $request->$social_name,
                    'social_link' => $request->$social_link,
                );
                $socail_name_save_array [] = $array_to_soacial_name;
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

        try {

            $validatedData = $request->post();

            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['lng'] = $request->citylong;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['mobile'] = $validatedData['full_phone_contact'] ?? $validatedData['mobile'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('talents', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_featured'] = $validatedData['is_featured'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;
            $validatedData['is_draft'] = $validatedData['is_draft'] ?? 0;
            $validatedData['is_publisher'] = 1;
            if(isset($socail_name_save_array)){
                $validatedData['social_links'] = $socail_name_save_array;
            }
            if (isset($youtube_name_save_array)) {
                $validatedData['video'] = $youtube_name_save_array;
            }


            if ($talent->update($validatedData)) {
                $id = $talent->id;
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
                if ($talent->is_draft == "1" && $request->is_draft == "0") {
                    $description_talent = "Submitted by" . ' ' . Auth::user()->name;
                    $message_talent = "Publisher submitted a new talent";
                    $url_now = route('admin.talent.edit', $talent->id);
                    event(new MyEvent($message_talent, $description_talent, $url_now, "0"));
                    $notification = new NotificationsInfo();
                    $notification->title = $message_talent;
                    $notification->description = $description_talent;
                    $notification->notification_for = 0;
                    $notification->url = $url_now;
                    $notification->save();
                }

                //sending notfication to particular for wish lists
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
            $response['error'] = true;
            $response['msg'] = 'There was a problem while updating talent. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
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
                $alt_texts['en'] =  $request->alt_text_en[$img_id] ?? '';
                $TalentImages->update([
                    'talent_id' => $id,
                    'alt_texts' => $alt_texts,
                ]);
            }
        }
    }
}
