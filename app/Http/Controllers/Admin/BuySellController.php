<?php

namespace App\Http\Controllers\Admin;

use App\Events\MyEvent;
use App\Events\PublisherEvent;
use App\Models\City;
use App\Models\DynamicLink;
use App\Models\MajorCategory;
use App\Models\BuySell;
use App\Models\SubCategory;
use App\Models\MoreInfo;
use App\Models\MainCategory;
use App\Models\BuysellImage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NotificationsInfo;
use App\Models\BuySellReservation;
use App\Models\WishListDetails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Helper;
use Illuminate\Validation\Rule;
use Image;
use DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Brand;

class BuySellController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Admin|buysell-add', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Admin|buysell-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|buysell-update', ['only' => ['edit', 'update', 'buysell_status']]);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $main_category = MainCategory::where('major_category_id', '=', '3')->orderBy('name')->pluck('name', 'id');

        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');
        if ($request->ajax()) {
            $buysells = BuySell::with('get_subcat.mainCategory')->orderby('id', 'desc');
            $auth_user_type = Auth::user()->user_type;

            if ($auth_user_type != 1) {
                $buysells = $buysells->where('created_by', Auth::id());
            }

            if ($main_categories) { // && empty($keyword)
                $buysells->orWhereHas('get_subcat.mainCategory', function ($q) use ($main_categories) {
                    $q->whereIn('id', $main_categories);
                });
            }

            if ($keyword) {
                $buysells->where(function ($query) use ($keyword) {
                    $query->where('buy_sells.product_name', 'LIKE', '%' . $keyword . '%');
                });
            }

            if (!empty($date_from) && empty($keyword)) {
                $buysells->where('buy_sells.created_at', '>', $date_from . " 00:00:00");
            }

            if (!empty($date_from) && empty($keyword)) {
                $buysells->where('buy_sells.created_at', '<=', $date_to . " 23:59:59");
            }

            $buysells = $buysells->latest()->get();
            return DataTables::of($buysells)
                ->addColumn('active', function ($buysells) use ($auth_user_type) {
                    if ($auth_user_type === 1) {
                        return '<input type="checkbox" class="buysell-status" data-toggle="switch" data-size="small" data-on-color="green" data-on-text="ON" data-off-color="default" data-off-text="OFF" data-id="' . $buysells->id . '" value="' . $buysells->status . '" ' . ($buysells->status ? "checked" : "") . '>';
                    }
                    return '';
                })
                ->addColumn('main_category', function ($buysells) {
                    return isset($buysells->get_subcat->mainCategory) ? $buysells->get_subcat->mainCategory->name : '';
                })
                ->addColumn('sub_category', function ($buysells) {
                    return isset($buysells->get_subcat) ? $buysells->get_subcat->name : '';
                })
                ->addColumn('publisher_name', function ($buysells) {
                    if ($buysells->is_publisher == "1") {
                        return $buysells->publish_by->name;
                    }
                    return 'N/A';
                })
                ->addColumn('action', function ($buysells) {
                    $btn = '<div class="btn-group" role="group"><button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button><div class="dropdown-menu"><a class="dropdown-item" href="' . route(
                        'admin.buy-sell.edit',
                        $buysells
                    ) . '"><i data-feather="edit"></i> Edit </a><a class="dropdown-item" href="' . route('admin.buy-sell.show', $buysells->id) . '"><i data-feather="eye"></i> Preview </a><a class="dropdown-item btn-icon modal-btn" data-href="' . route('admin.buy-sell.destroy', $buysells) . '" data-target="#remove" data-toggle="modal" type="button" href="javascript:void(0)"><i data-feather="trash-2"></i> Delete </a></div></div>';

                    return $btn;
                })
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at, 'Asia/Dubai')->timestamp;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
        }
        return view('admin.buysell.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $major_category = MajorCategory::find(3);
        $brands = Brand::where('status', 1)->orderBy('title')->pluck('title', 'id')->toArray();
        $main_categories = MainCategory::where('major_category_id', '=', '3')->orderBy('name')->pluck('name', 'id')->toArray();

        $array_type_sell = Helper::get_type_sell();
        $how_old_array = Helper::get_how_old_array();

        return view('admin.buysell.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'product_name' => 'required|unique:buy_sells,product_name',
                'slug' => 'required|unique:buy_sells,slug',
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
            $validatedData['location'] = $request->location;

            $validatedData['lat'] = $request->citylat;
            $validatedData['lng'] = $request->citylong;
            $validatedData['sub_category_id'] = $request->sub_category_id;

            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['contact'] = $validatedData['full_phone_contact'] ?? $validatedData['contact'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('buy-and-sell', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_deals'] = $validatedData['is_deals'] ?? 0;
            $validatedData['is_verified'] = $validatedData['is_verified'] ?? 0;
            $validatedData['is_feature'] = $validatedData['is_feature'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;
            // return $validatedData;
            $buySell = BuySell::create($validatedData);
            if ($buySell) {
                $id = $buySell->id;
                if ($request->feature_image_ids) {
                    $this->updateBuySellImages($request, $id, $request->feature_image_ids, 'feature_image');
                }
                if ($request->logo_ids) {
                    $this->updateBuySellImages($request, $id, $request->logo_ids, 'logo');
                }
                if ($request->menu_ids) {
                    $this->updateBuySellImages($request, $id, $request->menu_ids, 'menu');
                }
                if ($request->floor_plan_ids) {
                    $this->updateBuySellImages($request, $id, $request->floor_plan_ids, 'floor_plan');
                }
                if ($request->main_image_ids) {
                    $this->updateBuySellImages($request, $id, $request->main_image_ids, 'main_image');
                }
                if ($request->images_ids) {
                    $this->updateBuySellImages($request, $id, $request->images_ids, 'images');
                }
                if ($request->stories_ids) {
                    $this->updateBuySellImages($request, $id, $request->stories_ids, 'stories');
                }

                $response['error'] = false;
                $response['msg'] = 'BuySell "' . $validatedData['product_name'] . '" was created successfully!';
                $response['primary_id'] = $buySell->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add BuySell. Please try later.';
                return response()->json($response, 500);
            }
            DB::commit();
        } catch (\Exception $e) {
           
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding Buy and Sell. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param BuySell $BuySell
     * @return \Illuminate\Http\Response
     */
    public function show(BuySell $buySell)
    {
        $buySell->load(['subCategory', 'city', 'featureImage', 'storyImages', 'mainImages', 'logoImage', 'menuImage', 'mainImage']);

        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $buySell->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        return view('admin.buysell.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BuySell $buySell)
    {
        if (Auth::user()->user_type != 1 && Auth::id() != $buysell->created_by) {
            abort(403);
        }
        $brands = Brand::where('status', 1)->orderBy('title')->pluck('title', 'id')->toArray();

        $array_type_sell = Helper::get_type_sell();
        $how_old_array = Helper::get_how_old_array();

        $more_info = MoreInfo::where(['module_id' => $buySell->id, 'module_name' => 'BuySell'])->get();

        $major_category = MajorCategory::find(3);

        $main_categories = MainCategory::where('major_category_id', '=', '3')->orderBy('name')->pluck('name', 'id')->toArray();

        $subcatgories = SubCategory::where('main_category_id', '=', isset($buySell->get_subcat->mainCategory) ? $buySell->get_subcat->mainCategory->id : '')->orderBy('name')->pluck('name', 'id')->toArray();

        $primary_id = $buySell->id;
        if (isset($buySell->date_and_time)) {
            $date_and_time = $buySell->date_and_time = Carbon::parse($buySell->date_and_time)->format('d/m/Y g:i A');
        } else {
            $date_and_time = '';
        }
        $featureImage = $buySell->featureImage;
        $mainImage = $buySell->mainImage;
        $mainImages = $buySell->mainImages;
        $storyImages = $buySell->storyImages;

        return view('admin.buysell.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param BuySell $BuySell
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, BuySell $buySell)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'product_name' => 'required|min:1|max:100|' . Rule::unique('buy_sells')->ignore($buySell),
                'slug' => 'required|' . Rule::unique('buy_sells')->ignore($buySell),
                'location' => 'required',
                'feature_image_ids' => 'required'
            ],
            [
                'feature_image_ids.required' => 'Feature/Profile image is required.',
                'sub_category_id.required' => 'Sub category is required.',
            ]
        );
        try {

            $validatedData = $request->post();

            DB::beginTransaction();
            $validatedData['location'] = $request->location;

            $validatedData['lat'] = $request->citylat;
            $validatedData['lng'] = $request->citylong;

            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['contact'] = $validatedData['full_phone_contact'] ?? $validatedData['contact'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('buy-and-sell', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_deals'] = $validatedData['is_deals'] ?? 0;
            $validatedData['is_verified'] = $validatedData['is_verified'] ?? 0;
            $validatedData['is_feature'] = $validatedData['is_feature'] ?? 0;
            $validatedData['youtube_img'] = $validatedData['youtube_img'] ?? '';
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;
            if ($validatedData['youtube_img'] == 1) {
                $validatedData['video'] = $request->video;
            }

            if ($buySell->update($validatedData)) {
                $id = $buySell->id;
                if ($request->feature_image_ids) {
                    $this->updateBuySellImages($request, $id, $request->feature_image_ids, 'feature_image');
                }
                if ($request->logo_ids) {
                    $this->updateBuySellImages($request, $id, $request->logo_ids, 'logo');
                }
                if ($request->main_image_ids) {
                    $this->updateBuySellImages($request, $id, $request->main_image_ids, 'main_image');
                }
                if ($request->images_ids) {
                    $this->updateBuySellImages($request, $id, $request->images_ids, 'images');
                }
                if ($request->stories_ids) {
                    $this->updateBuySellImages($request, $id, $request->stories_ids, 'stories');
                }

                $request->module_id = $id;
                $request->module_name = 'BuySell';
                $request->user_type = 'admin';

                //sending notfication to particular for wish lists
                $wishlist_notifications = WishListDetails::where('item_type', 'App\Models\BuySell')
                    ->where('item_id', '=', $id)
                    ->where('status', '=', "1")
                    ->where('created_by', '!=', Auth::user()->id)
                    ->where('is_notification_need', '=', "1")
                    ->get();

                $title_msg = "Buy Sell Updated";
                $description = $request->product_name;
                $route = route('buy-and-sell', $buySell->slug);
                Helper::send_notification_wishlist_guys($wishlist_notifications, $route, $title_msg, $description);
                //sending notfication to particular for wish lists

                $response['error'] = false;
                $response['msg'] = 'Buy Sell "' . $validatedData['product_name'] . '" was updated successfully!';
                $response['primary_id'] = $buySell->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add BuySell. Please try later.';
                return response()->json($response, 500);
            }
            DB::commit();
        } catch (\Exception $e) {

            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding BuySell. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BuySell $BuySell
     * @return \Illuminate\Http\Response
     */
    public function destroy(BuySell $buySell)
    {
        $buySell->delete();

        $message = [
            'message' => 'Buy Sell Deleted Successfully',
            'alert-type' => 'success'
        ];

        # Redirect
        return redirect()->back()->with($message);
    }

    public function update_status_buysell(Request $request)
    {
        $response['error'] = false;
        abort_if(!$request->id, 400);
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $buysell = BuySell::find($id);
        try {
            abort_if(!$buysell, 404);
            if ($buysell->update([$field => $value])) {
                if ($buysell->is_publisher == "1" && $field === 'status') {

                    if ($value == "1") {
                        $satuts_label = "Approved";
                    } else {
                        $satuts_label = "Rejected";
                    }

                    $description_event = $satuts_label . " By Admin";
                    $message_event = "Admin {$satuts_label} Buy Sell list";
                    $url_now = route('publisher.buy-sell.edit', $buysell->id);

                    event(new MyEvent($message_event, $description_event, $url_now, "1", $buysell->created_by));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_event;
                    $notification->description = $description_event;
                    $notification->notification_for = 1;
                    $notification->url = $url_now;
                    $notification->notify_to = $buysell->created_by;
                    $notification->save();
                }
                $response['msg'] = 'Buy Sell updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating BuySell. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return response()->json($response);
    }

    public function save_more_info(Request $request)
    {
        $id = $request->primary_id;

        $request->module_id = $id;
        $request->module_name = 'BuySell';
        $request->user_type = 'admin';

        $res = saveCommoncomponent($request);
        if ($res == false) {
            $message = [
                'message' => 'Buy Sell More Info has some issue try again.!',
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
            'message' => 'Buy Sell More Info Successfully',
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
                $destinationPath = config('app.upload_buysell_path') . $image_type;
                $imageName = $destinationPath . '/' . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($photo), 'public');
                $blog_photo = BuySellImage::create([
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
            $image = BuySellImage::findOrFail($request->id);
            $tmp_obj = $image;
            if ($image->delete()) {
                if (file_exists(public_path($tmp_obj->image))) {
                    @unlink(public_path($tmp_obj->image));
                }
                $destinationPath = config('app.upload_buysell_path') . '/' . $image->image_type;
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

    public function updateBuySellImages(Request $request, $id, $imageIds, $img_type)
    {
        foreach (explode(",", $imageIds) as $k => $img_id) {
            $BuySellImage = BuySellImage::find($img_id);
            if ($BuySellImage) {
                $alt_texts['en'] = $request->alt_text_en[$img_id] ?? '';
                $BuySellImage->update([
                    'buysell_id' => $id,
                    'alt_texts' => $alt_texts,
                ]);
            }
        }
    }
}
