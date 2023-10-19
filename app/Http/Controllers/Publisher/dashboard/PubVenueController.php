<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Events\MyEvent;
use App\Events\PublisherEvent;
use App\Models\Amenitable;
use App\Models\City;
use App\Models\Landmarkable;
use App\Models\Venue;
use App\Models\Amenties;
use App\Models\Landmark;
use App\Models\SubCategory;
use App\Models\MoreInfo;
use App\Models\MainCategory;
use App\Models\VenueImage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use App\Models\NotificationsInfo;
use App\Models\VenueReservation;
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

class PubVenueController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|publisher-user-venue', ['only' =>
            ['index', 'edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $main_category = MainCategory::where('major_category_id', 1)->orderBy('name')->pluck('name', 'id');

        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');
        if ($request->ajax()) {
            $venues = Venue::with('get_subcat.mainCategory');
            $venues = $venues->where('created_by', Auth::id());

            if ($main_categories) { // && empty($keyword)
                $venues->orWhereHas('get_subcat.mainCategory', function ($q) use ($main_categories) {
                    $q->whereIn('id', $main_categories);
                });
            }

            if ($keyword) {
                $venues->where(function ($query) use ($keyword) {
                    $query->where('venues.title', 'LIKE', '%' . $keyword . '%');
                });
            }

            if (!empty($date_from) && empty($keyword)) {
                $venues->where('venues.created_at', '>', $date_from . " 00:00:00");
            }

            if (!empty($date_from) && empty($keyword)) {
                $venues->where('venues.created_at', '<=', $date_to . " 23:59:59");
            }
            //dd($venues->toSql());
            $venues = $venues->latest()->get();
            return DataTables::of($venues)
                ->editColumn('status', function ($venues) {
                    return $venues->status == 0 ? 'InActive' : 'Active';
                })
                ->editColumn('is_draft', function ($venues) {
                    return $venues->is_draft == 0 ? 'No' : 'Yes';
                })
                ->addColumn('main_category', function ($venues) {
                    return isset($venues->get_subcat->mainCategory) ? $venues->get_subcat->mainCategory->name : '';
                })
                ->addColumn('sub_category', function ($venues) {
                    return isset($venues->get_subcat) ? $venues->get_subcat->name : '';
                })
                ->addColumn('action', function ($venues) {
                    $btn = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('publisher.venue.edit', $venues->id) . '">
                                            <i data-feather="edit"></i> Edit </a>
                                    <a class="dropdown-item btn-icon modal-btn" data-target="#remove" data-toggle="modal" type="button" data-href="' . route('publisher.venue.destroy', $venues->id) . '" >
                                <i data-feather="trash-2"></i> Delete
                            </a>
                                </div>
                            </div>';

                    return $btn;
                })
                ->editColumn('created_at', function ($venues) {
                    return Carbon::parse($venues->created_at, 'Asia/Dubai')->timestamp;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('publisher.venue.index', get_defined_vars());


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $amenties = Amenties::orderBy('description')->pluck('description', 'id')->toArray();
        $landmarks = Landmark::orderBy('name')->pluck('name', 'id')->toArray();
        //$cities = City::all();
        $main_categories = MainCategory::where('major_category_id', '=', '1')->orderBy('name')->pluck('name', 'id')->toArray();
        //$dynamic_main_category = DynamicMainCategory::where('major_category_id', 1)->get();
        $popular_types = PopularPlacesTypes::orderBy('name')->pluck('name', 'id')->toArray();
        $prices = ['$' => '$', '$$' => '$$', '$$$' => '$$$', '$$$$' => '$$$$', '$$$$$' => '$$$$$'];
        $cuisine_names = ['Indian' => 'Indian', 'Oriental' => 'Oriental', 'Pakistani' => 'Pakistani', 'Arabian' => 'Arabian', 'Afghani' => 'Afghani',
            'Chinese' => 'Chinese', 'European' => 'European', 'Continental' => 'Continental'];
        return view('publisher.venue.create', compact('popular_types', 'main_categories', 'landmarks', 'amenties', 'prices', 'cuisine_names'));
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
                'title' => 'required|unique:venues,title',
                'slug' => 'required|unique:venues,slug',
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
            $validatedData['assign_featured'] = $validatedData['is_feature'] ?? 0;
            $validatedData['reservation'] = $validatedData['reservation'] ?? 0;
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('venue-detail', $validatedData['slug']);
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['assign_featured'] = $validatedData['assign_featured'] ?? 0;
            $validatedData['cusine_name'] = isset($validatedData['cusine_name']) ? implode(', ', $validatedData['cusine_name']) : '';

            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_publisher'] = 1;
            if (isset($validatedData['is_popular']) && $validatedData['is_popular'] == "1") {
                $validatedData['popular_types'] = json_encode($validatedData['popular_type']);
            }


            $venue = Venue::create($validatedData);
            if ($venue) {
                $id = $venue->id;
                if ($request->feature_image_ids) {
                    $this->updateVenueImages($request, $id, $request->feature_image_ids);
                }
                if ($request->logo_ids) {
                    $this->updateVenueImages($request, $id, $request->logo_ids);
                }
                if ($request->menu_ids) {
                    $this->updateVenueImages($request, $id, $request->menu_ids);
                }
                if ($request->floor_plan_ids) {
                    $this->updateVenueImages($request, $id, $request->floor_plan_ids);
                }
                if ($request->main_image_ids) {
                    $this->updateVenueImages($request, $id, $request->main_image_ids);
                }
                if ($request->images_ids) {
                    $this->updateVenueImages($request, $id, $request->images_ids);
                }
                if ($request->stories_ids) {
                    $this->updateVenueImages($request, $id, $request->stories_ids);
                }

                $response['error'] = false;
                $response['primary_id'] = $venue->id;
                $response['msg'] = 'Venue "' . $validatedData['title'] . '" was created successfully!';
                if ($request->is_draft == "0") {

                    $description_event = "Submitted by" . ' ' . Auth::user()->name;
                    $message_event = 'Publisher submitted a new venue';
                    $url_now = route('admin.venue.edit', $id);

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
                $response['msg'] = 'Unable to add venue. Please try later.';
                return response()->json($response);
            }

            DB::commit();

        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding venue. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response);
        }
        return response()->json($response);
    }

    public function save_more_info(Request $request)
    {

        $id = $request->primary_id;

        /*dd($request->videoLink);*/
        $request->module_id = $id;
        $request->module_name = 'venue';
        $request->user_type = 'publisher';
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

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function edit(Venue $venue)
    {
        $data = $venue;
        if (Auth::user()->user_type != 1 && Auth::id() != $data->created_by) {
            abort(403);
        }
        //$amenties_counter = $venue->amenities()->count();
        //$landmarks_counter = $venue->landmarks()->count();

        $amenties_venue = $data->amenity_id;
        $landmark_venue = $data->landmark_id;
        //dd($amenties_venue,$landmark_venue);
        $venueMainCategory = SubCategory::where('id', $data->sub_category_id)->value('main_category_id');
        $categories = MainCategory::where('major_category_id', 1)->get();
        $subCategory = SubCategory::where('main_category_id', $venueMainCategory)->get();

        $more_info = MoreInfo::where(['module_id' => $data->id, 'module_name' => 'venue'])->get();

        $cities = City::all();
        /*$amenties = Amenties::all();
        $landmarks = Landmark::all();
        $four_images = json_decode($data->images);
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',1)->get();
        $main_category_ids = isset($data->dynamic_main_ids) ? json_decode($data->dynamic_main_ids) : [];
        $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id',$main_category_ids)->get();*/
        $amenties = Amenties::orderBy('description')->pluck('description', 'id')->toArray();
        $landmarks = Landmark::orderBy('name')->pluck('name', 'id')->toArray();

        //$cities = City::all();
        $popular_types = PopularPlacesTypes::orderBy('name')->pluck('name', 'id')->toArray();
        $prices = ['$' => '$', '$$' => '$$', '$$$' => '$$$', '$$$$' => '$$$$', '$$$$$' => '$$$$$'];
        $cuisine_names = ['Indian' => 'Indian', 'Oriental' => 'Oriental', 'Pakistani' => 'Pakistani', 'Arabian' => 'Arabian', 'Afghani' => 'Afghani',
            'Chinese' => 'Chinese', 'European' => 'European', 'Continental' => 'Continental'];
        $main_categories = MainCategory::where('major_category_id', '=', '1')->orderBy('name')->pluck('name', 'id')->toArray();
        $subcatgories = SubCategory::where('main_category_id', '=', isset($venue->get_subcat->mainCategory) ? $venue->get_subcat->mainCategory->id : '')->orderBy('name')->pluck('name', 'id')->toArray();
        $select_popular_types = [];
        if ($venue->is_popular == "1") {
            $select_popular_types = json_decode($venue->popular_types, true);
        }
        $primary_id = $venue->id;
        if (isset($venue->start_date_time) && isset($venue->end_date_time)) {
            $datetimefilter = $venue->datetimefilter = Carbon::parse($venue->start_date_time)->format('d/m/Y g:i A') . ' - ' . Carbon::parse($venue->end_date_time)->format('d/m/Y g:i A');
        } else {
            $datetimefilter = '';
        }
        $featureImage = $venue->featureImage;
        $floorPlanImage = $venue->floorPlanImage;
        $logoImage = $venue->logoImage;
        $menuImage = $venue->menuImage;
        $mainImage = $venue->mainImage;
        $mainImages = $venue->mainImages;
        $storyImages = $venue->storyImages;

        return view('publisher.venue.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Venue $venue
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Venue $venue)
    {
        //dd($venue);
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'title' => 'required|min:1|max:100|' . Rule::unique('venues')->ignore($venue),
                'slug' => 'required|' . Rule::unique('venues')->ignore($venue),
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
            //dd($validatedData);

            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['long'] = $request->citylong;
            $validatedData['amenity_id'] = $request->amenities ?? [];
            $validatedData['landmark_id'] = isset($request->landmark[0]['name']) ? $request->landmark : [];
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['contact'] = $validatedData['full_phone_contact'] ?? $validatedData['contact'];
            $validatedData['assign_featured'] = $validatedData['is_feature'] ?? 0;
            $validatedData['reservation'] = $validatedData['reservation'] ?? 0;
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('venue-detail', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['assign_featured'] = $validatedData['assign_featured'] ?? 0;
            $validatedData['cusine_name'] = isset($validatedData['cusine_name']) ? implode(', ', $validatedData['cusine_name']) : '';

            if (isset($validatedData['is_popular']) && $validatedData['is_popular'] == "1") {
                $validatedData['popular_types'] = json_encode($validatedData['popular_type']);
            }
            $validatedData['is_draft'] = $request->is_draft;
            $validatedData['is_publisher'] = "1";
            if ($venue->update($validatedData)) {
                $id = $venue->id;
                if ($request->feature_image_ids) {
                    $this->updateVenueImages($request, $id, $request->feature_image_ids);
                }
                if ($request->logo_ids) {
                    $this->updateVenueImages($request, $id, $request->logo_ids);
                }
                if ($request->menu_ids) {
                    $this->updateVenueImages($request, $id, $request->menu_ids);
                }
                if ($request->floor_plan_ids) {
                    $this->updateVenueImages($request, $id, $request->floor_plan_ids);
                }
                if ($request->main_image_ids) {
                    $this->updateVenueImages($request, $id, $request->main_image_ids);
                }
                if ($request->images_ids) {
                    $this->updateVenueImages($request, $id, $request->images_ids);
                }
                if ($request->stories_ids) {
                    $this->updateVenueImages($request, $id, $request->stories_ids);
                }
                $request->module_id = $id;
                $request->module_name = 'venue';
                $request->user_type = 'publisher';

                if ($venue->is_draft == "1" && $request->is_draft == "0") {

                    $description_event = "Submitted by" . ' ' . Auth::user()->name;
                    $message_event = 'Publisher submitted a new venue';
                    $url_now = route('admin.venue.edit', $venue->id);

                    event(new MyEvent('Publisher submitted a new venue', $description_event, $url_now, "0"));


                    $notification = new NotificationsInfo();
                    $notification->title = $message_event;
                    $notification->description = $description_event;
                    $notification->notification_for = 0;
                    $notification->url = $url_now;
                    $notification->save();

                }

                $wishlist_notifications = WishListDetails::where('item_type', 'App\Models\Venue')
                    ->where('item_id', '=', $id)
                    ->where('status', '=', "1")
                    ->where('created_by', '!=', Auth::user()->id)
                    ->where('is_notification_need', '=', "1")
                    ->get();

                $title_msg = "Venue Updated";
                $description = $request->name;
                $route = route('admin.venue.edit', $venue->id);
                Helper::send_notification_wishlist_guys($wishlist_notifications, $route, $title_msg, $description);
                //sending notfication to particular for wish lists

                $response['error'] = false;
                $response['msg'] = 'Venue "' . $validatedData['title'] . '" was updated successfully!';
                $response['primary_id'] = $venue->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add venue. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding venue. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateOld(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'main_category' => 'required',
            'sub_category_id' => 'required',
            'name' => 'required',
            'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            $validate = $validator->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];
            return back()->with($message);
        }

        if ($request->featured_img) {
            $featured = rand(100, 100000) . '.' . time() . '.' . $request->featured_img->extension();
            $featuredPath = config('app.upload_venue_path') . $featured;
            Storage::disk('s3')->put($featuredPath, file_get_contents($request->featured_img));

        }

        if ($request->icon) {
            $icon = rand(100, 100000) . '.' . time() . '.' . $request->icon->extension();
            $iconPath = config('app.upload_venue_path') . $icon;
            Storage::disk('s3')->put($iconPath, file_get_contents($request->icon));
        }

        if ($request->floor) {

            $floor = rand(100, 100000) . '.' . time() . '.' . $request->floor->extension();
            $floorPath = config('app.upload_venue_path') . $floor;
            Storage::disk('s3')->put($floorPath, file_get_contents($request->floor));

        }

        if ($request->menu) {
            $menu = rand(100, 100000) . '.' . time() . '.' . $request->menu->extension();
            $menuPath = config('app.upload_venue_path') . $menu;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->menu));
        }

        if ($request->youtube_image) {

            $youtube_image = rand(100, 100000) . '.' . time() . '.' . $request->youtube_image->extension();
            $youtubeImagePath = config('app.upload_venue_path') . $youtube_image;
            Storage::disk('s3')->put($youtubeImagePath, file_get_contents($request->youtube_image));
        }

        if ($request->images) {
            foreach ($request->images as $file) {
                $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $nameimagePath = config('app.upload_venue_path') . $name;
                Storage::disk('s3')->put($nameimagePath, file_get_contents($file));

                $data[] = $name;
            }
        }
        if ($request->old_images) {
            foreach ($request->old_images as $image) {
                $data[] = $image;
            }
        }

        $amenties_ids = $request->amentie_array;
        $now_array = explode(",", $amenties_ids);
        $amentie_save_array = array();

        foreach ($now_array as $ids) {
            $amentie_name = "amentie_icon_" . $ids;
            if (isset($request->$amentie_name)) {
                $amentie_save_array [] = $request->$amentie_name;
            }
        }

        //landmark
        $landmark_ids = $request->landmark_array;
        $land_array = explode(",", $landmark_ids);

        $lanmark_save_array = array();

        foreach ($land_array as $ids) {
            $land_mark_icon = "landmarkicon_" . $ids;
            $landmark_heading = "landmarkheading_" . $ids;
            $landmark_desc = "landmarkdesc_" . $ids;

            if (isset($request->$land_mark_icon)) {
                $array_to_landmark = array(
                    'landmark_icon' => $request->$land_mark_icon,
                    'landmark_heading' => $request->$landmark_heading,
                    'landmark_desc' => $request->$landmark_desc,
                );
                $lanmark_save_array [] = $array_to_landmark;
            }
        }

        //dd($lanmark_save_array, $amentie_save_array);

        $obj = Venue::find($id);
        $obj->sub_category_id = $request->sub_category_id;
        $obj->title = $request->name;
        $obj->status_text = $request->status_text;
        $obj->description = $request->description;
        $obj->lat = $request->citylat;
        $obj->long = $request->citylong;
        $obj->map_review = $request->map_review;
        $obj->map_rating = $request->map_rating;
        $obj->city_id = $request->city_id;
        $obj->youtube_img = $request->youtube_img;
        if ($request->youtube_img == 1) {
            $obj->video = $request->video;
        } elseif ($request->youtube_img == 2) {
            if ($request->file('youtube_image')) {
                $obj->video = $youtube_image;
            }
        }
        $obj->amenity_id = json_encode($amentie_save_array, JSON_UNESCAPED_SLASHES);
        $obj->landmark_id = json_encode($lanmark_save_array, JSON_UNESCAPED_SLASHES);

        $obj->cusine_name = $request->cuisine;
        $obj->prices = $request->price;
        if ($request->file('floor')) {
            $obj->view_floor_plan = $floor;
        }
        if ($request->file('menu')) {
            $obj->view_menu = $menu;
        }
        if (isset($data)) {
            $obj->images = json_encode($data);
        }
        if ($request->file('featured_img')) {
            $obj->feature_image = $featured;
        }
        if ($request->file('icon')) {
            $obj->icon = $icon;
        }
        $obj->whatsapp = $request->whatsapp;
        $obj->contact = $request->contact;
        $obj->email = $request->email;
        if (isset($request->dynamic_main_category)) {
            $obj->dynamic_main_ids = json_encode($request->dynamic_main_category);
        } else {
            $obj->dynamic_main_ids = null;
        }
        if (isset($request->dynamic_sub_category)) {
            $obj->dynamic_sub_ids = json_encode($request->dynamic_sub_category);
        } else {
            $obj->dynamic_sub_ids = null;
        }
        if (isset($request->assign_featured)) {
            $obj->assign_featured = $request->assign_featured;
        } else {
            $obj->assign_featured = 0;
        }

        if (isset($request->reservation)) {
            $obj->reservation = $request->reservation;
        } else {
            $obj->reservation = 0;
        }

        if ($obj->is_draft == "1" && $request->is_draft == "0") {


            $description_event = "Submitted by" . ' ' . Auth::user()->name;
            $message_event = 'Publisher submitted a new venue';
            $url_now = route('admin.venue.edit', $obj->slug);

            event(new MyEvent('Publisher submitted a new venue', $description_event, $url_now, "0"));


            $notification = new NotificationsInfo();
            $notification->title = $message_event;
            $notification->description = $description_event;
            $notification->notification_for = 0;
            $notification->url = $url_now;
            $notification->save();

        }

        $wishlist_notifications = WishListDetails::where('item_type', 'App\Models\Venue')
            ->where('item_id', '=', $id)
            ->where('status', '=', "1")
            ->where('created_by', '!=', Auth::user()->id)
            ->where('is_notification_need', '=', "1")
            ->get();

        $title_msg = "Venue Updated";
        $description = $request->name;
        $route = route('admin.venue.edit', $obj->slug);
        Helper::send_notification_wishlist_guys($wishlist_notifications, $route, $title_msg, $description);
        //sending notfication to particular for wish lists


        $obj->is_draft = $request->is_draft;
        $obj->is_publisher = "1";

        $obj->save();
        $id = $obj->id;


        $message = [
            'message' => 'Venue Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venue $venue)
    {
        $venue->delete();

        $message = [
            'message' => 'Venue Deleted Successfully',
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
                $destinationPath = config('app.upload_venue_path') . $image_type;
                $imageName = $destinationPath . '/' . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($photo), 'public');
                $blog_photo = VenueImage::create([
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
            $image = VenueImage::findOrFail($request->id);
            $tmp_obj = $image;
            if ($image->delete()) {
                if (file_exists(public_path($tmp_obj->image))) {
                    @unlink(public_path($tmp_obj->image));
                }
                $destinationPath = config('app.upload_venue_path') . '/' . $image->image_type;
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

    public function updateVenueImages(Request $request, $id, $imageIds)
    {
        foreach (explode(",", $imageIds) as $k => $img_id) {
            $venueImage = VenueImage::find($img_id);
            if ($venueImage) {
                $alt_texts['en'] = $request->alt_text_en[$img_id] ?? '';
                $venueImage->update([
                    'venue_id' => $id,
                    'alt_texts' => $alt_texts,
                ]);
            }
        }
    }
}
