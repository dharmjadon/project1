<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Events\MyEvent;
use App\Models\Influencer;
use App\Models\InfluencerImage;
use App\Models\SubCategory;
use App\Models\MainCategory;
use App\Models\MajorCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NotificationsInfo;
use App\Models\WishListDetails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Helper;
use DB;
use Image;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class PubInfluencerController extends Controller
{
    function __construct()
    {
        //$this->middleware('role_or_permission:Admin|publisher-user-infuencers', ['only' =>['index','edit','update','destroy']]);
    }

    /**
    * Display a listing of the resource.
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $pagetitle = 'Manage Contact Requests';
        $activeTab = 'contact-requests';
        $activeSubTab = 'list-contact-requests';
        $main_category = MainCategory::where('major_category_id', '=', '6')->orderBy('name')->pluck('name', 'id');

        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');
        if ($request->ajax()) {
            $influencer = Influencer::with('get_subcat.mainCategory')->orderby('id', 'desc');
            $auth_user_type = Auth::user()->user_type;

            if ($auth_user_type != 1) {
                $influencer = $influencer->where('created_by', Auth::id());
            }

            if ($main_categories) { // && empty($keyword)
                $influencer->orWhereHas('get_subcat.mainCategory', function($q) use ($main_categories) {
                    $q->whereIn('id', $main_categories);
                });
            }
            
            if ($keyword) {
                $influencer->where(function ($query) use ($keyword) {
                    $query->where('influencers.name', 'LIKE', '%' . $keyword . '%');
                });
            }

            if (!empty($date_from) && empty($keyword)) {
                $influencer->where('influencers.created_at', '>', $date_from . " 00:00:00");
            }

            if (!empty($date_from) && empty($keyword)) {
                $influencer->where('influencers.created_at', '<=', $date_to . " 23:59:59");
            }
            
            $influencer = $influencer->latest()->get();
            return DataTables::of($influencer)
                ->addColumn('main_category', function($influencer) {
                    return isset($influencer->get_subcat->mainCategory) ? $influencer->get_subcat->mainCategory->name : '';
                })
                ->addColumn('sub_category', function($influencer) {
                    return isset($influencer->get_subcat) ? $influencer->get_subcat->name : '';
                })
                ->addColumn('action', function ($influencer) {
                    $btn = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('publisher.influencers.edit', $influencer->id) . '">
                                            <i data-feather="edit"></i> Edit </a>
                                   <a class="dropdown-item btn-icon modal-btn" data-href="' . route('publisher.influencers.destroy', $influencer) . '" data-target="#remove" data-toggle="modal" type="button" href="javascript:void(0)"><i data-feather="trash-2"></i> Delete </a>
                                </div>
                            </div>';

                    return $btn;
                })
                ->addColumn('created_at', function($influencer){
                    return Carbon::parse($influencer->created_at, 'Asia/Dubai')->timestamp;
                })
                ->editColumn('created_at', function($data){
                    return Carbon::parse($data->created_at, 'Asia/Dubai')->timestamp;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
        }


        return view('publisher.influencers.index', get_defined_vars());
    }

    /**
    * Show the form for creating a new resource.
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $array_social_name = Helper::get_social_name();
        $major_category = MajorCategory::find(6);
        $main_categories = MainCategory::where('major_category_id', '=', '6')->orderBy('name')->pluck('name', 'id')->toArray();
        return view('publisher.influencers.create', get_defined_vars());
    }

    /**
    * Store a newly created resource in storage.
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'name' => 'required|unique:influencers,name',
                'slug' => 'required|unique:influencers,slug',
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
       //dd(json_encode($socail_name_save_array,JSON_UNESCAPED_SLASHES));
        
        try {
            $validatedData = $request->post();
            DB::beginTransaction();

            $validatedData['lat'] = $request->citylat;
            $validatedData['lng'] = $request->citylong;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['phone'] = $validatedData['full_phone_contact'] ?? $validatedData['phone'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('influencer', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_deals'] = $validatedData['is_deals'] ?? 0;
            $validatedData['is_verified'] = $validatedData['is_verified'] ?? 0;
            $validatedData['featured'] = $validatedData['featured'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;
            $validatedData['social_links'] = json_encode($socail_name_save_array,JSON_UNESCAPED_SLASHES);

            $influencer = Influencer::create($validatedData);
            if ($influencer) {
                $id = $influencer->id;
                if ($request->feature_image_ids) {
                    $this->updateInfluencerImages($request, $id, $request->feature_image_ids, 'feature_image');
                }
                if ($request->images_ids) {
                    $this->updateInfluencerImages($request, $id, $request->images_ids, 'images');
                }
                if ($request->stories_ids) {
                    $this->updateInfluencerImages($request, $id, $request->stories_ids, 'stories');
                }

                $response['error'] = false;
                $response['msg'] = 'Influencer "' . $validatedData['name'] . '" was created successfully!';
                $response['primary_id'] = $influencer->id;
                if ($request->is_draft == "0") {

                    $description_event = "Submitted by" . ' ' . Auth::user()->name;
                    $message_event = 'Publisher submitted a new Influencer';
                    $url_now = route('publisher.influencers.edit', $id);

                    event(new MyEvent($message_event, $description_event, $url_now, "0"));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_event;
                    $notification->description = $description_event;
                    $notification->notification_for = 0;
                    $notification->url = $url_now;
                    $notification->save();

                }
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add Influencer. Please try later.';
                return response()->json($response, 500);
            }
            DB::commit();
        } catch (\Exception $e) {
          
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding Influencer. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    public function edit(Influencer $influencer)
    {
        if (Auth::user()->user_type != 1 && Auth::id() != $influencer->created_by) {
            abort(403);
        }
        
        $major_category = MajorCategory::find(6);

        $main_categories = MainCategory::where('major_category_id', '=', '6')->orderBy('name')->pluck('name', 'id')->toArray();

        $subcatgories = SubCategory::where('main_category_id', '=', isset($influencer->get_subcat->mainCategory) ? $influencer->get_subcat->mainCategory->id : '')->orderBy('name')->pluck('name', 'id')->toArray();

        $array_social_name  = Helper::get_social_name();
        $socail_links = isset($influencer->social_links) ? json_decode($influencer->social_links) : [];

        $primary_id = $influencer->id;
       
        $featureImage = $influencer->featureImage;
        $mainImages = $influencer->mainImages;
        $storyImages = $influencer->storyImages;

        return view('publisher.influencers.edit', get_defined_vars());
    }


     public function update(Request $request, Influencer $influencer)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'name' => 'required|min:1|max:100|' . Rule::unique('influencers')->ignore($influencer),
                'slug' => 'required|' . Rule::unique('influencers')->ignore($influencer),
                'location' => 'required',
                'feature_image_ids' => 'required'
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
        try {
            $validatedData = $request->post();

            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['lng'] = $request->citylong;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['phone'] = $validatedData['full_phone_contact'] ?? $validatedData['phone'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            #$validatedData['canonical_url'] = route('influencers', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_deals'] = $validatedData['is_deals'] ?? 0;
            $validatedData['is_verified'] = $validatedData['is_verified'] ?? 0;
            $validatedData['featured'] = $validatedData['featured'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;
            if(isset($socail_name_save_array)){
                $validatedData['social_links'] = json_encode($socail_name_save_array,JSON_UNESCAPED_SLASHES);
            }

            if ($influencer->update($validatedData)) {
                $id = $influencer->id;
                if ($request->feature_image_ids) {
                    $this->updateInfluencerImages($request, $id, $request->feature_image_ids, 'feature_image');
                }
                if ($request->logo_ids) {
                    $this->updateInfluencerImages($request, $id, $request->logo_ids, 'logo');
                }
                if ($request->main_image_ids) {
                    $this->updateInfluencerImages($request, $id, $request->main_image_ids, 'main_image');
                }
                if ($request->images_ids) {
                    $this->updateInfluencerImages($request, $id, $request->images_ids, 'images');
                }
                if ($request->stories_ids) {
                    $this->updateInfluencerImages($request, $id, $request->stories_ids, 'stories');
                }

                $request->module_id = $id;
                $request->module_name = 'Influencer';
                $request->user_type = 'publisher';

                if ($influencer->is_draft == "1" && $request->is_draft == "0") {

                    $description_event = "Submitted by" . ' ' . Auth::user()->name;
                    $message_event = 'Publisher submitted a new venue';
                    $url_now = route('publisher.influencers.edit', $influencer->id);

                    event(new MyEvent('Publisher submitted a new influencer', $description_event, $url_now, "0"));


                    $notification = new NotificationsInfo();
                    $notification->title = $message_event;
                    $notification->description = $description_event;
                    $notification->notification_for = 0;
                    $notification->url = $url_now;
                    $notification->save();

                }

                //sending notfication to particular for wish lists
                $wishlist_notifications = WishListDetails::where('item_type', 'App\Models\Influencer')
                    ->where('item_id', '=', $id)
                    ->where('status', '=', "1")
                    ->where('created_by', '!=', Auth::user()->id)
                    ->where('is_notification_need', '=', "1")
                    ->get();

                $title_msg = "Influencer Updated";
                $description = $request->name;
                $route = route('influencer', $influencer->slug);
                Helper::send_notification_wishlist_guys($wishlist_notifications, $route, $title_msg, $description);
                //sending notfication to particular for wish lists

                $response['error'] = false;
                $response['msg'] = 'Influencer "' . $validatedData['name'] . '" was updated successfully!';
                $response['primary_id'] = $influencer->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add Influencer. Please try later.';
                return response()->json($response, 500);
            }
            DB::commit();
        } catch (\Exception $e) {
            
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding Influencer. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    /**
    * Remove the specified resource from storage.
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Influencer $influencer)
    {
        $influencer->delete();

        $message = [
            'message' => 'Influencer Deleted Successfully',
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
            }
            if ($photo->getSize() < (1024 * 1024)) {
                $destinationPath = config('app.upload_influencer_path') . $image_type;
                $imageName = $destinationPath . '/' . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($photo), 'public');
                $blog_photo = InfluencerImage::create([
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
            $image = InfluencerImage::findOrFail($request->id);
            $tmp_obj = $image;
            if ($image->delete()) {
                if (file_exists(public_path($tmp_obj->image))) {
                    @unlink(public_path($tmp_obj->image));
                }
                $destinationPath = config('app.upload_influencer_path') . '/' . $image->image_type;
                $imageName = $destinationPath . '/' . $tmp_obj->image;
                if (config('app.env') === 'production') {
                    Storage::disk('s3')->delete($imageName);
                }
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

    public function updateInfluencerImages(Request $request, $id, $imageIds, $img_type)
    {
        foreach (explode(",", $imageIds) as $k => $img_id) {
            $InfluencerImage = InfluencerImage::find($img_id);
            if ($InfluencerImage) {
                $alt_texts['en'] = $request->alt_text_en[$img_id] ?? '';
                $InfluencerImage->update([
                    'influencer_id' => $id,
                    'alt_texts' => $alt_texts,
                ]);
            }
        }
    }
}
