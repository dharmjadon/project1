<?php

namespace App\Http\Controllers\Admin;

use App\Events\MyEvent;
use App\Events\PublisherEvent;
use App\Models\CryptoEvent;
use App\Models\Amenitable;
use App\Models\City;
use App\Models\Landmarkable;
use App\Models\MajorCategory;
use App\Models\Crypto;
use App\Models\Amenties;
use App\Models\Landmark;
use App\Models\SubCategory;
use App\Models\MoreInfo;
use App\Models\MainCategory;
use App\Models\CryptoImage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use App\Models\NotificationsInfo;
use App\Models\CryptoReservation;
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

class CryptoController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|crypto-add', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Admin|crypto-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|crypto-update', ['only' => ['edit', 'update', 'update_status_crypto']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagetitle = 'Manage Contact Requests';
        $activeTab = 'contact-requests';
        $activeSubTab = 'list-contact-requests';
        $main_category = MainCategory::where('major_category_id', '=', '16')->orderBy('name')->pluck('name', 'id');

        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');



        if ($request->ajax()) {

            $crypto = Crypto::with('get_subcat.mainCategory');
            $auth_user_type = Auth::user()->user_type;
            if ($auth_user_type != 1) {
                $crypto = $crypto->where('created_by', Auth::id());
            }
            if ($main_categories) { // && empty($keyword)
                $crypto->orWhereHas('get_subcat.mainCategory', function ($q) use ($main_categories) {
                    $q->whereIn('id', $main_categories);
                });
            }
            // Global search function
            if ($keyword) {
                $crypto->where(function ($query) use ($keyword) {
                    $query->where('coins.title', 'LIKE', '%' . $keyword . '%');
                });
            }
            if (!empty($date_from) && empty($keyword)) {
                $crypto->where('coins.created_at', '>', $date_from . " 00:00:00");
            }

            if (!empty($date_from) && empty($keyword)) {
                $crypto->where('coins.created_at', '<=', $date_to . " 23:59:59");
            }
            $crypto = $crypto->latest()->get();
            return DataTables::of($crypto)
                ->addColumn('active', function ($crypto) use ($auth_user_type) {
                    if ($auth_user_type === 1) {
                        return '<input type="checkbox" class="crypto-status" data-toggle="switch" data-size="small"
                    data-on-color="green" data-on-text="ON" data-off-color="default" data-off-text="OFF"
                    data-id="' . $crypto->id . '" value="' . $crypto->status . '" ' . ($crypto->status ? "checked" : "") . '>';
                    }
                    return '';
                })
                ->addColumn('main_category', function ($crypto) {
                    return isset($crypto->get_subcat->mainCategory) ? $crypto->get_subcat->mainCategory->name : '';
                })
                ->addColumn('sub_category', function ($crypto) {
                    return isset($crypto->get_subcat) ? $crypto->get_subcat->name : '';
                })
                ->addColumn('publisher_name', function ($crypto) {
                    if ($crypto->is_publisher == "1") {
                        return $crypto->publish_by->name;
                    }
                    return 'N/A';
                })
                ->addColumn('action', function ($crypto) {
                    $btn = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('admin.crypto.edit', $crypto) . '">
                                            <i data-feather="edit"></i> Edit </a>
                                    <a class="dropdown-item" href="' . route('admin.crypto.show', $crypto->id) . '">
                                        <i data-feather="eye"></i> Preview </a>
                                    <a class="dropdown-item btn-icon modal-btn" data-href="' . route('admin.crypto.destroy', $crypto) . '" data-target="#remove" data-toggle="modal" type="button" href="javascript:void(0)">
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


        return view('admin.crypto.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $major_category = MajorCategory::find(16);
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
        //$cities = City::all();
        $main_categories = MainCategory::where('major_category_id', '=', '16')->orderBy('name')->pluck('name', 'id')->toArray();
        //$dynamic_main_category = DynamicMainCategory::where('major_category_id', 16)->get();
        $popular_types = PopularPlacesTypes::orderBy('name')->pluck('name', 'id')->toArray();
        $prices = ['$' => '$', '$$' => '$$', '$$$' => '$$$', '$$$$' => '$$$$', '$$$$$' => '$$$$$'];
        $cuisine_names = ['Indian' => 'Indian', 'Oriental' => 'Oriental', 'Pakistani' => 'Pakistani', 'Arabian' => 'Arabian', 'Afghani' => 'Afghani',
            'Chinese' => 'Chinese', 'European' => 'European', 'Continental' => 'Continental'];
        return view('admin.crypto.create', get_defined_vars());
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
                'title' => 'required|unique:coins,title',
                'slug' => 'required|unique:coins,slug',
                'location' => 'required',
                'feature_image_ids' => 'required',
                'popular_type' => 'required_if:is_popular,1',
                'amenities.*' => 'distinct',
            ],
            [
                'feature_image_ids.required' => 'Feature/Profile image is required.',
                'sub_category_id.required' => 'Sub category is required.',
                'popular_type.required_if' => 'Popular type is required.',
            ]
        );
        try {

            $validatedData = $request->post();

            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['long'] = $request->citylong;
            $validatedData['amenity_id'] = $request->amenities ?? [];
            $validatedData['landmark_id'] = isset($request->landmark[0]['name']) ? $request->landmark : [];
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['contact'] = $validatedData['full_phone_contact'] ?? $validatedData['contact'];

            $validatedData['reservation'] = $validatedData['reservation'] ?? 0;
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('cryptocoin-detail', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['assign_featured'] = $validatedData['assign_featured'] ?? 0;

            if (isset($validatedData['is_popular']) && $validatedData['is_popular'] == "1") {
                $validatedData['popular_types'] = json_encode($validatedData['popular_type']);
            }

            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $crypto = Crypto::create($validatedData);
            if ($crypto) {
                $id = $crypto->id;
                if ($request->feature_image_ids) {
                    $this->updateCryptoImages($request, $id, $request->feature_image_ids);
                }
                if ($request->logo_ids) {
                    $this->updateCryptoImages($request, $id, $request->logo_ids);
                }
                if ($request->menu_ids) {
                    $this->updateCryptoImages($request, $id, $request->menu_ids);
                }
                if ($request->floor_plan_ids) {
                    $this->updateCryptoImages($request, $id, $request->floor_plan_ids);
                }
                if ($request->main_image_ids) {
                    $this->updateCryptoImages($request, $id, $request->main_image_ids);
                }
                if ($request->images_ids) {
                    $this->updateCryptoImages($request, $id, $request->images_ids);
                }
                if ($request->stories_ids) {
                    $this->updateCryptoImages($request, $id, $request->stories_ids);
                }
                if ($request->event) {
                    foreach ($request->event as $key => $value) {
                        CryptoEvent::create([
                            'crypto_id' => $id,
                            'name' => $value['name'],
                            'datetime' => $value['date'],
                        ]);
                    }
                }
                $response['error'] = false;
                $response['msg'] = 'Crypto "' . $validatedData['title'] . '" was created successfully!';
                $response['primary_id'] = $crypto->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add crypto. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding crypto. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param Crypto $crypto
     * @return \Illuminate\Http\Response
     */
    public function show(Crypto $crypto)
    {
        $crypto->load(['subCategory', 'featureImage', 'storyImages', 'mainImages', 'logoImage', 'menuImage', 'mainImage']);

        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $crypto->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        $amenties_crypto = $crypto->amenity_id ? $crypto->amenity_id : [];
        $landmark_crypto = $crypto->landmark_id ? $crypto->landmark_id : [];
        return view('admin.crypto.show', compact('crypto', 'youtube', 'amenties_crypto', 'landmark_crypto'));
    }

    public function preview($id)
    {
        $data = Crypto::with('subCategory')->where('slug', $id)->first();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        $amenties_crypto = $data->amenity_id ? $data->amenity_id : [];
        $landmark_crypto = $data->landmark_id ? $data->landmark_id : [];
        return view('admin.crypto.preview', compact('data', 'youtube', 'amenties_crypto', 'landmark_crypto'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Crypto $crypto)
    {
        if (Auth::user()->user_type != 1 && Auth::id() != $crypto->created_by) {
            abort(403);
        }

        $more_info = MoreInfo::where(['module_id' => $crypto->id, 'module_name' => 'crypto'])->get();

        $amenties_crypto = $crypto->amenity_id;
        $landmark_crypto = $crypto->landmark_id;

        $major_category = MajorCategory::find(16);
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


        $popular_types = PopularPlacesTypes::orderBy('name')->pluck('name', 'id')->toArray();
        $main_categories = MainCategory::where('major_category_id', '=', '16')->orderBy('name')->pluck('name', 'id')->toArray();
        $subcatgories = SubCategory::where('main_category_id', '=', isset($crypto->get_subcat->mainCategory) ? $crypto->get_subcat->mainCategory->id : '')->orderBy('name')->pluck('name', 'id')->toArray();

        $select_popular_types = [];
        if ($crypto->is_popular == "1") {
            $select_popular_types = json_decode($crypto->popular_types, true);
        }
        $primary_id = $crypto->id;
        if(isset($crypto->start_date_time) && isset($crypto->end_date_time)) {
            $datetimefilter = $crypto->datetimefilter = Carbon::parse($crypto->start_date_time)->format('d/m/Y g:i A') .' - '.Carbon::parse($crypto->end_date_time)->format('d/m/Y g:i A');
        } else {
            $datetimefilter = '';
        }
        $featureImage = $crypto->featureImage;
        $floorPlanImage = $crypto->floorPlanImage;
        $logoImage = $crypto->logoImage;
        $menuImage = $crypto->menuImage;
        $mainImage = $crypto->mainImage;
        $mainImages = $crypto->mainImages;
        $storyImages = $crypto->storyImages;

        return view('admin.crypto.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Crypto $crypto
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Crypto $crypto)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'title' => 'required|min:1|max:100|'.Rule::unique('coins')->ignore($crypto),
                'slug' => 'required|'.Rule::unique('coins')->ignore($crypto),
                'location' => 'required',
                'feature_image_ids' => 'required',
                'popular_type' => 'required_if:is_popular,1',
                'amenities.*' => 'distinct',
            ],
            [
                'feature_image_ids.required' => 'Feature/Profile image is required.',
                'sub_category_id.required' => 'Sub category is required.',
                'popular_type.required_if' => 'Popular type is required.',
            ]
        );
        try {

            $validatedData = $request->post();

            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['long'] = $request->citylong;
            $validatedData['amenity_id'] = $request->amenities ?? [];
            $validatedData['landmark_id'] = isset($request->landmark[0]['name']) ? $request->landmark : [];
            $validatedData['prices'] = $request->prices;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['contact'] = $validatedData['full_phone_contact'] ?? $validatedData['contact'];

            $validatedData['reservation'] = $validatedData['reservation'] ?? 0;
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('cryptocoin-detail', $validatedData['slug']);
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['assign_featured'] = $validatedData['assign_featured'] ?? 0;

            if (isset($validatedData['is_popular']) && $validatedData['is_popular'] == "1") {
                $validatedData['popular_types'] = json_encode($validatedData['popular_type']);
            }

            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            if ($crypto->update($validatedData)) {
                $id = $crypto->id;
                if ($request->feature_image_ids) {
                    $this->updateCryptoImages($request, $id, $request->feature_image_ids);
                }
                if ($request->logo_ids) {
                    $this->updateCryptoImages($request, $id, $request->logo_ids);
                }
                if ($request->menu_ids) {
                    $this->updateCryptoImages($request, $id, $request->menu_ids);
                }
                if ($request->floor_plan_ids) {
                    $this->updateCryptoImages($request, $id, $request->floor_plan_ids);
                }
                if ($request->main_image_ids) {
                    $this->updateCryptoImages($request, $id, $request->main_image_ids);
                }
                if ($request->images_ids) {
                    $this->updateCryptoImages($request, $id, $request->images_ids);
                }
                if ($request->stories_ids) {
                    $this->updateCryptoImages($request, $id, $request->stories_ids);
                }

                CryptoEvent::where('crypto_id', $id)->delete();
                if ($request->event) {
                    foreach ($request->event as $key => $value) {
                        CryptoEvent::create([
                            'crypto_id' => $id,
                            'name' => $value['name'],
                            'datetime' => $value['date'],
                        ]);
                    }
                }
                $request->module_id = $id;
                $request->module_name = 'crypto';
                $request->user_type = 'admin';

                //sending notfication to particular for wish lists
                $wishlist_notifications = WishListDetails::where('item_type', 'App\Models\Crypto')
                    ->where('item_id', '=', $id)
                    ->where('status', '=', "1")
                    ->where('created_by', '!=', Auth::user()->id)
                    ->where('is_notification_need', '=', "1")
                    ->get();

                $title_msg = "Crypto Updated";
                $description = $request->title;
                $route = route('cryptocoin-detail', $crypto->slug);
                Helper::send_notification_wishlist_guys($wishlist_notifications, $route, $title_msg, $description);
                //sending notfication to particular for wish lists

                $response['error'] = false;
                $response['msg'] = 'Crypto "' . $validatedData['title'] . '" was updated successfully!';
                $response['primary_id'] = $crypto->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to update crypto. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while updating crypto. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Crypto $crypto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Crypto $crypto)
    {
        $crypto->delete();

        $message = [
            'message' => 'Crypto Deleted Successfully',
            'alert-type' => 'success'
        ];

        # Redirect
        return redirect()->back()->with($message);
    }

    public function update_status_crypto(Request $request)
    {
        $response['error'] = false;
        abort_if(!$request->id, 400);
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $crypto = Crypto::find($id);
        try {
            abort_if(!$crypto, 404);
            if ($crypto->update([$field => $value])) {
                if ($crypto->is_publisher == "1" && $field === 'status') {

                    if ($value == "1") {
                        $satuts_label = "Approved";
                    } else {
                        $satuts_label = "Rejected";
                    }

                    $description_event = $satuts_label . " By Admin";
                    $message_event = "Admin {$satuts_label} crypto list";
                    $url_now = route('publisher.crypto.edit', $crypto->id);

                    event(new MyEvent($message_event, $description_event, $url_now, "1", $crypto->created_by));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_event;
                    $notification->description = $description_event;
                    $notification->notification_for = 1;
                    $notification->url = $url_now;
                    $notification->notify_to = $crypto->created_by;
                    $notification->save();
                }
                $response['msg'] = 'Crypto updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating crypto. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return response()->json($response);
    }

    public function cryptoReservation(Request $request)
    {
        $datas = CryptoReservation::with('crypto')->get();
        return view('admin.crypto.crypto-reservation', compact('datas'));
    }

    public function save_more_info(Request $request)
    {

        $id = $request->primary_id;

        $request->module_id = $id;
        $request->module_name = 'crypto';
        $request->user_type = 'admin';
        $res = saveCommoncomponent($request);
        if ($res == false) {
            //    Log::info('Edit Venu Faild ID: '.$id);

            $message = [
                'message' => 'Crypto More Info has some issue try again.!',
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
            'message' => 'Crypto More Info Successfully',
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
                $destinationPath = config('app.upload_crypto_path') . $image_type;
                $imageName = $destinationPath . '/' . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($photo), 'public');
                $blog_photo = CryptoImage::create([
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
            $image = CryptoImage::findOrFail($request->id);
            $tmp_obj = $image;
            if ($image->delete()) {
                if (file_exists(public_path($tmp_obj->image))) {
                    @unlink(public_path($tmp_obj->image));
                }
                $destinationPath = config('app.upload_crypto_path') . '/' . $image->image_type;
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

    public function updateCryptoImages(Request $request, $id, $imageIds)
    {
        foreach (explode(",", $imageIds) as $k => $img_id) {
            $CryptoImage = CryptoImage::find($img_id);
            if ($CryptoImage) {
                $alt_texts['en'] =  $request->alt_text_en[$img_id] ?? '';
                $CryptoImage->update([
                    'crypto_id' => $id,
                    'alt_texts' => $alt_texts,
                ]);
            }
        }
    }

    public function removeAmenity(Request $request)
    {
        $response = [];
        try {
            abort_if(!$request->id && $request->crypto_id, 400);
            $crypto = Crypto::find($request->crypto_id);
            abort_if(!$crypto, 404);
            if ($crypto->amenities()->whereId($request->id)->delete()) {
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
                abort_if(!$request->id && $request->crypto_id, 400);
                $crypto = Crypto::find($request->crypto_id);
                abort_if(!$crypto, 404);
                if ($crypto->landmarks()->whereId($request->id)->delete()) {
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
