<?php

namespace App\Http\Controllers\Admin;

use App\Events\MyEvent;
use App\Models\Directory;
use App\Models\DirectoryImage;
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
use DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Image;

class DirectoriesController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Admin|directory-add', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Admin|directory-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|directory-update', ['only' => ['edit', 'update', 'update_status_directories']]);
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

        $main_category = MainCategory::where('major_category_id', 4)->orderBy('name')->pluck('name', 'id');
        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');

        if ($request->ajax()) {
            $directories = Directory::with('subCategory.mainCategory')->orderby('id', 'desc');
            $auth_user_type = Auth::user()->user_type;
            if ($auth_user_type != 1) {
                $directories = $directories->where('created_by', Auth::id());
            }
            if ($main_categories) { // && empty($keyword)
                $directories->orWhereHas('subCategory.mainCategory', function ($q) use ($main_categories) {
                    $q->whereIn('id', $main_categories);
                });
            }
            // Global search function
            if ($keyword) {
                $directories->where(function ($query) use ($keyword) {
                    $query->where('directories.title', 'LIKE', '%' . $keyword . '%');
                });
            }
            if (!empty($date_from) && empty($keyword)) {
                $directories->where('directories.created_at', '>', $date_from . " 00:00:00");
            }

            if (!empty($date_from) && empty($keyword)) {
                $directories->where('directories.created_at', '<=', $date_to . " 23:59:59");
            }
            //dd($directories->toSql());
            $directories = $directories->latest()->get();
            return DataTables::of($directories)
                ->addColumn('active', function($directory) use ($auth_user_type) {
                    if ($auth_user_type === 1) {
                        return '<input type="checkbox" class="directory-status" data-toggle="switch" data-size="small"
                    data-on-color="green" data-on-text="ON" data-off-color="default" data-off-text="OFF"
                    data-id="' . $directory->id . '" value="' . $directory->status . '" ' . ($directory->status ? "checked" : "") . '>';
                    }
                    return '';
                })
                ->addColumn('main_category', function ($directory) {
                    return isset($directory->SubCategory->mainCategory) ? $directory->SubCategory->mainCategory->name : '';
                })
                ->addColumn('sub_category', function ($directory) {
                    return isset($directory->SubCategory) ? $directory->SubCategory->name : '';
                })
                ->addColumn('publisher_name', function ($directory) {
                    if ($directory->is_publisher == "1") {
                        return $directory->publish_by->name;
                    }
                    return 'N/A';
                })
                ->addColumn('action', function ($directory) {
                    $btn = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="'.route('admin.directories.edit', $directory->id).'">
                                        <i data-feather="edit"></i>Edit
                                    </a>
                                    <a class="dropdown-item" href="'.route('admin.directories.preview', $directory->id).'">
                                        <i data-feather="eye"></i>Preview
                                    </a>
                                    <a class="dropdown-item btn-icon modal-btn" data-href="'.route('admin.directories.destroy', $directory).'" data-target="#remove" data-toggle="modal" type="button" href="javascript:void(0)">
                                        <i data-feather="trash-2"></i>Delete
                                    </a>
                                </div>
                            </div>';
                    return $btn;
                })
                ->editColumn('created_at', function($data) {
                    return Carbon::parse($data->created_at, 'Asia/Dubai')->timestamp;
                })
                ->editColumn('founded_date', function($data) {
                    return Carbon::parse($data->founded_date, 'Asia/Dubai')->timestamp;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
        }
        return view('admin.directories.index', get_defined_vars());
    }

    /**
    * Show the form for creating a new resource.
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $main_categories = MainCategory::where('major_category_id', '=', 4)->orderBy('name')->pluck('name', 'id')->toArray();
        return view('admin.directories.create', compact('main_categories'));
    }

    /**
    * Store a newly created resource in storage.
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function store(Request $request)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'title' => 'required|unique:directories,title',
                'slug' => 'required|unique:directories,slug',
                'location' => 'required',
                'feature_image_ids' => 'required',
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
            $validatedData['long'] = $request->citylong;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['quick_contacts'] = $validatedData['full_phone_contact'] ?? $validatedData['quick_contacts'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('directory', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_feature'] = $validatedData['is_feature'] ?? 0;
            $validatedData['reservation'] = $validatedData['reservation'] ?? 0;
            $validatedData['youtube_img'] = $validatedData['youtube_img'] ?? '';
            $validatedData['social_links'] = $validatedData['social_links'] ?? [];
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;
            if ($validatedData['youtube_img'] == 1) {
                $validatedData['video'] = $request->video;
            }

            $directory = Directory::create($validatedData);
            if ($directory) {
                $id = $directory->id;
                if ($request->feature_image_ids) {
                    $this->updateImages($request, $id, $request->feature_image_ids);
                }
                if ($request->logo_ids) {
                    $this->updateImages($request, $id, $request->logo_ids);
                }
                if ($request->qr_code_ids) {
                    $this->updateImages($request, $id, $request->qr_code_ids);
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
                $response['msg'] = 'Directory "' . $validatedData['title'] . '" was created successfully!';
                $response['primary_id'] = $directory->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add directory. Please try later.';
                return response()->json($response);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding directory. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response);
        }
        return response()->json($response);
    }

    public function save_more_info(Request $request)
    {
        $id = $request->primary_id;

        $request->module_id = $id;
        $request->module_name = 'directory';
        $request->user_type = 'admin';

        $res = saveCommoncomponent($request);
        if ($res == false) {
            $message = [
                'message' => 'Directory more info has some issue try again.!',
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
            'message' => 'Directory more info has been saved successfully',
            'alert-type' => 'success',
        ];
        return redirect()->back()->with($message);
    }

    /**
    * Display the specified resource.
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        //
    }

    public function preview($id)
    {
        $data = Directory::where('id', $id)->first();
        $data->load(['subCategory', 'featureImage', 'logoImage', 'qrCodeImage', 'mainImage', 'mainImages', 'storyImages']);
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        return view('admin.directories.preview', compact('data', 'youtube'));
    }

    /**
    * Show the form for editing the specified resource.
    * @param  Directory $directory
    * @return \Illuminate\Http\Response
    */
    public function edit(Directory $directory)
    {
        if (Auth::user()->user_type != 1 && Auth::id() != $directory->created_by) {
            abort(403);
        }
        $directory->load(['subCategory', 'featureImage', 'logoImage', 'qrCodeImage', 'mainImage', 'mainImages', 'storyImages']);
        $more_info = MoreInfo::where(['module_id' => $directory->id, 'module_name' => 'directory'])->get();
        $main_categories = MainCategory::where('major_category_id', '=', '4')->orderBy('name')->pluck('name', 'id')->toArray();
        $subcatgories = SubCategory::where('main_category_id', '=', isset($directory->subCategory->mainCategory) ? $directory->subCategory->mainCategory->id : '')->orderBy('name')->pluck('name', 'id')->toArray();

        $primary_id = $directory->id; //used for more info model
        $featureImage = $directory->featureImage;
        $logoImage = $directory->logoImage;
        $qrCodeImage = $directory->qrCodeImage;
        $mainImage = $directory->mainImage;
        $mainImages = $directory->mainImages;
        $storyImages = $directory->storyImages;
        $founded_date = "";
        if($directory->founded_date){
            $founded_date = date('Y-m-d', strtotime($directory->founded_date));
        }
        return view('admin.directories.edit', get_defined_vars());
    }

    /**
    * Update the specified resource in storage.
    * @param \Illuminate\Http\Request $request
    * @param Directory $directory
    * @return \Illuminate\Http\JsonResponse
    */
    public function update(Request $request, Directory $directory)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'title' => 'required|min:1|max:100|'.Rule::unique('directories')->ignore($directory),
                'slug' => 'required|'.Rule::unique('directories')->ignore($directory),
                'location' => 'required',
                'feature_image_ids' => 'required',
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
            $validatedData['long'] = $request->citylong;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['quick_contacts'] = $validatedData['full_phone_contact'] ?? $validatedData['quick_contacts'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('directory', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_feature'] = $validatedData['is_feature'] ?? 0;
            $validatedData['reservation'] = $validatedData['reservation'] ?? 0;
            $validatedData['youtube_img'] = $validatedData['youtube_img'] ?? '';
            $validatedData['social_links'] = $validatedData['social_links'] ?? [];
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;

            if ($validatedData['youtube_img'] == 1) {
                $validatedData['video'] = $request->video;
            }

            if ($directory->update($validatedData)) {
                $id = $directory->id;
                if ($request->feature_image_ids) {
                    $this->updateImages($request, $id, $request->feature_image_ids);
                }
                if ($request->logo_ids) {
                    $this->updateImages($request, $id, $request->logo_ids);
                }
                if ($request->qr_code_ids) {
                    $this->updateImages($request, $id, $request->qr_code_ids);
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
                $request->module_name = 'directory';
                $request->user_type = 'admin';

                //sending notfication to particular for wish lists
                $wishlist_notifications = WishListDetails::where('item_type', 'App\Models\Directory')
                    ->where('item_id', '=', $id)
                    ->where('status', '=', "1")
                    ->where('created_by', '!=', Auth::user()->id)
                    ->where('is_notification_need', '=', "1")
                    ->get();

                $title_msg = "Events Updated";
                $description = $request->title;
                $route = route('directory', $directory->slug);
                Helper::send_notification_wishlist_guys($wishlist_notifications, $route, $title_msg, $description);
                //sending notfication to particular for wish lists

                $response['error'] = false;
                $response['msg'] = 'Directory "' . $validatedData['title'] . '" was updated successfully!';
                $response['primary_id'] = $directory->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to update directory. Please try later.';
                return response()->json($response, 500);
            }
            DB::commit();
        } catch (\Exception $e) {
           
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while updating event. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }


    /**
    * Remove the specified resource from storage.
    * @param  Directory  $directory
    * @return \Illuminate\Http\JsonResponse
    */
    public function destroy(Directory $directory)
    {
        $directory->delete();

        $message = [
            'message' => 'Directory Deleted Successfully',
            'alert-type' => 'success'
        ];

        # Redirect
        return redirect()->back()->with($message);
    }

    public function update_status_directories(Request $request)
    {
        $response['error'] = false;
        abort_if(!$request->id, 400);
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $directory = Directory::find($id);
        try {
            abort_if(!$directory, 404);
            if ($directory->update([$field => $value])) {
                if ($directory->is_publisher == "1" && $field === 'status') {

                    if ($request->status == '1') {
                        $satuts_label = 'Approved';
                    } else {
                        $satuts_label = 'Rejected';
                    }

                    $description_event = $satuts_label . ' By Admin';
                    $message_event = "Admin {$satuts_label} job list";
                    $url_now = route('publisher.directories.edit', $directory->id);

                    event(new MyEvent($message_event, $description_event, $url_now, '1', $directory->created_by));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_event;
                    $notification->description = $description_event;
                    $notification->notification_for = 1;
                    $notification->url = $url_now;
                    $notification->notify_to = $directory->created_by;
                    $notification->save();
                }
                $response['msg'] = 'Directory updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating directory. Please try later.';
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
        $qr_code_ids = isset($request->qr_code_ids) ? explode(', ', $request->qr_code_ids) : [];
        $main_image_ids = isset($request->main_image_ids) ? explode(', ', $request->main_image_ids) : [];
        foreach ($images as $key => $photo) {
            $filename = time() . '-' . $photo->getClientOriginalName();
            $photo_object = new \stdClass();
            $photo_object->name = $photo->getClientOriginalName();

            if ($image_type === 'qr_code' && count($qr_code_ids) >= 1) {
                $photo_object->errorMessage = 'Only one QR Code is allowed';
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
                $destinationPath = config('app.upload_directory_path') . $image_type;
                $imageName = $destinationPath . '/' . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($photo), 'public');
                $blog_photo = DirectoryImage::create([
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
            $image = DirectoryImage::findOrFail($request->id);
            $tmp_obj = $image;
            if ($image->delete()) {
                if (file_exists(public_path($tmp_obj->image))) {
                    @unlink(public_path($tmp_obj->image));
                }
                $destinationPath = config('app.upload_directory_path') . '/' . $image->image_type;
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
            $directoryImage = DirectoryImage::find($img_id);
            if ($directoryImage) {
                $alt_texts['en'] =  $request->alt_text_en[$img_id] ?? '';
                $directoryImage->update([
                    'directory_id' => $id,
                    'alt_texts' => $alt_texts,
                ]);
            }
        }
    }
}
