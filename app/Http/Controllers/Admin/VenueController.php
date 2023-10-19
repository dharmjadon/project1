<?php

namespace App\Http\Controllers\Admin;

use App\Events\MyEvent;
use App\Events\PublisherEvent;
use App\Models\VenueEvent;
use App\Models\Amenitable;
use App\Models\City;
use App\Models\Landmarkable;
use App\Models\MajorCategory;
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

class VenueController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|venue-add', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Admin|venue-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|venue-update', ['only' => ['edit', 'update', 'update_status_venue']]);
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
        $main_category = MainCategory::where('major_category_id', '=', '1')->orderBy('name')->pluck('name', 'id');

        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');
        if ($request->ajax()) {
            $venues = Venue::with('get_subcat.mainCategory');
            $auth_user_type = Auth::user()->user_type;
            if ($auth_user_type != 1) {
                $venues = $venues->where('created_by', Auth::id());
            }
            if ($main_categories) { // && empty($keyword)
                $venues->orWhereHas('get_subcat.mainCategory', function ($q) use ($main_categories) {
                    $q->whereIn('id', $main_categories);
                });
            }
            // Global search function
            if ($keyword) {
                $venues->where(function ($query) use ($keyword) {
                    $query->where('venues.title', 'LIKE', '%' . $keyword . '%')/*->orWhere('events.email', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('events.description', 'LIKE', '%' . $keyword . '%')*/
                    ;
                });
                //"select filed_list from tbale where condition1 and condtion2 and (condition3 or condtion4 or condigiton5) and condition6 orderby column limit 20"
            }
            if (!empty($date_from) && empty($keyword)) {
                $venues->where('venues.created_at', '>', $date_from . " 00:00:00");
            }

            if (!empty($date_from) && empty($keyword)) {
                $venues->where('venues.created_at', '<=', $date_to . " 23:59:59");
            }
            //dd($events->toSql());
            $venues = $venues->latest()->get();
            return DataTables::of($venues)
                ->addColumn('active', function ($venue) use ($auth_user_type) {
                    if ($auth_user_type === 1) {
                        return '<input type="checkbox" class="venue-status" data-toggle="switch" data-size="small"
                    data-on-color="green" data-on-text="ON" data-off-color="default" data-off-text="OFF"
                    data-id="' . $venue->id . '" value="' . $venue->status . '" ' . ($venue->status ? "checked" : "") . '>';
                    }
                    return '';
                })
                ->addColumn('main_category', function ($venues) {
                    return isset($venues->get_subcat->mainCategory) ? $venues->get_subcat->mainCategory->name : '';
                })
                ->addColumn('sub_category', function ($venues) {
                    return isset($venues->get_subcat) ? $venues->get_subcat->name : '';
                })
                ->addColumn('publisher_name', function ($venues) {
                    if ($venues->is_publisher == "1") {
                        return $venues->publish_by->name;
                    }
                    return 'N/A';
                })
                ->addColumn('action', function ($venues) {
                    $btn = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('admin.venue.edit', $venues) . '">
                                            <i data-feather="edit"></i> Edit </a>
                                    <a class="dropdown-item" href="' . route('admin.venue.show', $venues->id) . '">
                                        <i data-feather="eye"></i> Preview </a>
                                    <a class="dropdown-item btn-icon modal-btn" data-href="' . route('admin.venue.destroy', $venues) . '" data-target="#remove" data-toggle="modal" type="button" href="javascript:void(0)">
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


        return view('admin.venue.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $major_category = MajorCategory::find(1);
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
        $main_categories = MainCategory::where('major_category_id', '=', '1')->orderBy('name')->pluck('name', 'id')->toArray();
        //$dynamic_main_category = DynamicMainCategory::where('major_category_id', 1)->get();
        $popular_types = PopularPlacesTypes::orderBy('name')->pluck('name', 'id')->toArray();
        $prices = ['$' => '$', '$$' => '$$', '$$$' => '$$$', '$$$$' => '$$$$', '$$$$$' => '$$$$$'];
        $cuisine_names = ['Indian' => 'Indian', 'Oriental' => 'Oriental', 'Pakistani' => 'Pakistani', 'Arabian' => 'Arabian', 'Afghani' => 'Afghani',
            'Chinese' => 'Chinese', 'European' => 'European', 'Continental' => 'Continental'];
        return view('admin.venue.create', get_defined_vars());
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
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['assign_featured'] = $validatedData['assign_featured'] ?? 0;
            $validatedData['cusine_name'] = $validatedData['cusine_name'] ? implode(', ', $validatedData['cusine_name']) : '';

            if (isset($validatedData['is_popular']) && $validatedData['is_popular'] == "1") {
                $validatedData['popular_types'] = json_encode($validatedData['popular_type']);
            }

            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
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
                if ($request->event) {
                    foreach ($request->event as $key => $value) {
                        VenueEvent::create([
                            'venue_id' => $id,
                            'name' => $value['name'],
                            'datetime' => $value['date'],
                        ]);
                    }
                }
                $response['error'] = false;
                $response['msg'] = 'Venue "' . $validatedData['title'] . '" was created successfully!';
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
     * Display the specified resource.
     *
     * @param Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function show(Venue $venue)
    {
        $venue->load(['subCategory', 'city', 'featureImage', 'storyImages', 'mainImages', 'logoImage', 'menuImage', 'mainImage']);
        //$data = Venue::with('subCategory', 'city')->where('slug', $id)->first();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $venue->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        $amenties_venue = $venue->amenity_id ? $venue->amenity_id : [];
        $landmark_venue = $venue->landmark_id ? $venue->landmark_id : [];
        return view('admin.venue.show', compact('venue', 'youtube', 'amenties_venue', 'landmark_venue'));
    }

    public function preview($id)
    {
        $data = Venue::with('subCategory', 'city')->where('slug', $id)->first();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        $amenties_venue = $data->amenity_id ? $data->amenity_id : [];
        $landmark_venue = $data->landmark_id ? $data->landmark_id : [];
        return view('admin.venue.preview', compact('data', 'youtube', 'amenties_venue', 'landmark_venue'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Venue $venue)
    {
        if (Auth::user()->user_type != 1 && Auth::id() != $venue->created_by) {
            abort(403);
        }

        $more_info = MoreInfo::where(['module_id' => $venue->id, 'module_name' => 'venue'])->get();

        //$amenties_venue = $venue->amenities()->pluck('amenity_id')->toArray();
        //$landmark_venue = $venue->landmarks()->pluck('landmark_id')->toArray();

        $amenties_venue = $venue->amenity_id;
        $landmark_venue = $venue->landmark_id;

        $major_category = MajorCategory::find(1);
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
        if(isset($venue->start_date_time) && isset($venue->end_date_time)) {
            $datetimefilter = $venue->datetimefilter = Carbon::parse($venue->start_date_time)->format('d/m/Y g:i A') .' - '.Carbon::parse($venue->end_date_time)->format('d/m/Y g:i A');
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

        return view('admin.venue.edit', get_defined_vars());
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
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'title' => 'required|min:1|max:100|'.Rule::unique('venues')->ignore($venue),
                'slug' => 'required|'.Rule::unique('venues')->ignore($venue),
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
            $validatedData['prices'] = $request->price;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['contact'] = $validatedData['full_phone_contact'] ?? $validatedData['contact'];
            $validatedData['assign_featured'] = $validatedData['is_feature'] ?? 0;
            $validatedData['reservation'] = $validatedData['reservation'] ?? 0;
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('venue-detail', $validatedData['slug']);
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['assign_featured'] = $validatedData['assign_featured'] ?? 0;
            $validatedData['cusine_name'] = isset($validatedData['cusine_name']) ? implode(', ', $validatedData['cusine_name']) : '';

            if (isset($validatedData['is_popular']) && $validatedData['is_popular'] == "1") {
                $validatedData['popular_types'] = json_encode($validatedData['popular_type']);
            }

            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
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

                VenueEvent::where('venue_id', $id)->delete();
                if ($request->event) {
                    foreach ($request->event as $key => $value) {
                        VenueEvent::create([
                            'venue_id' => $id,
                            'name' => $value['name'],
                            'datetime' => $value['date'],
                        ]);
                    }
                }
                $request->module_id = $id;
                $request->module_name = 'venue';
                $request->user_type = 'admin';

                //sending notfication to particular for wish lists
                $wishlist_notifications = WishListDetails::where('item_type', 'App\Models\Venue')
                    ->where('item_id', '=', $id)
                    ->where('status', '=', "1")
                    ->where('created_by', '!=', Auth::user()->id)
                    ->where('is_notification_need', '=', "1")
                    ->get();

                $title_msg = "Venues Updated";
                $description = $request->title;
                $route = route('venue-detail', $venue->slug);
                Helper::send_notification_wishlist_guys($wishlist_notifications, $route, $title_msg, $description);
                //sending notfication to particular for wish lists

                $response['error'] = false;
                $response['msg'] = 'Venue "' . $validatedData['title'] . '" was updated successfully!';
                $response['primary_id'] = $venue->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to update venue. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while updating venue. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
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

        # Redirect
        return redirect()->back()->with($message);
    }

    public function update_status_venue_old(Request $request)
    {
        $obj = Venue::find($request->id);
        $obj->status = $request->status;
        $obj->save();


        if ($obj->is_publisher == "1") {

            if ($request->status == "1") {
                $satuts_label = "Approved";
            } else {
                $satuts_label = "Rejected";
            }

            $description_event = $satuts_label . " By Admin";
            $message_event = "Admin {$satuts_label} venue list";
            $url_now = route('publisher.venue.edit', $obj->id);

            event(new MyEvent($message_event, $description_event, $url_now, "1", $obj->created_by));

            $notification = new NotificationsInfo();
            $notification->title = $message_event;
            $notification->description = $description_event;
            $notification->notification_for = 1;
            $notification->url = $url_now;
            $notification->notify_to = $obj->created_by;
            $notification->save();
        }
    }

    public function update_status_venue(Request $request)
    {
        $response['error'] = false;
        abort_if(!$request->id, 400);
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $venue = Venue::find($id);
        try {
            abort_if(!$venue, 404);
            if ($venue->update([$field => $value])) {
                if ($venue->is_publisher == "1" && $field === 'status') {

                    if ($value == "1") {
                        $satuts_label = "Approved";
                    } else {
                        $satuts_label = "Rejected";
                    }

                    $description_event = $satuts_label . " By Admin";
                    $message_event = "Admin {$satuts_label} venue list";
                    $url_now = route('publisher.venue.edit', $venue->id);

                    event(new MyEvent($message_event, $description_event, $url_now, "1", $venue->created_by));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_event;
                    $notification->description = $description_event;
                    $notification->notification_for = 1;
                    $notification->url = $url_now;
                    $notification->notify_to = $venue->created_by;
                    $notification->save();
                }
                $response['msg'] = 'Venue updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating venue. Please try later.';
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
        $request->module_name = 'venue';
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

    public function updateVenueImages(Request $request, $id, $imageIds)
    {
        foreach (explode(",", $imageIds) as $k => $img_id) {
            $venueImage = VenueImage::find($img_id);
            if ($venueImage) {
                $alt_texts['en'] =  $request->alt_text_en[$img_id] ?? '';
                $venueImage->update([
                    'venue_id' => $id,
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
