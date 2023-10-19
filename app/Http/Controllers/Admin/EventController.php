<?php

namespace App\Http\Controllers\Admin;

use App\Events\MyEvent;
use App\Events\PublisherEvent;
use App\Models\Amenitable;
use App\Models\City;
use App\Models\EventImage;
use App\Models\Events;
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
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use App\Models\NotificationsInfo;
use App\Models\EventReservation;
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

class EventController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Admin|event-add', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Admin|event-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|event-update', ['only' => ['edit', 'update', 'update_status_event']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /* below code to fix existing event entries */
        $allevents = Events::get();
        foreach($allevents as $event) {
            $landmarks = $event->landmarks;
            $landmark = [];
            foreach($landmarks as $k => $lm) {
                if(isset($lm['landmark_icon'])) {
                    $landmark[$k]['name'] = $lm['landmark_icon'];
                    $landmark[$k]['description'] = $lm['landmark_desc'];
                }
            }
            if($landmark) {
                $event->update(['landmarks' => $landmark]);
            }
        }
        /***************************************/
        $pagetitle = 'Manage Contact Requests';
        $activeTab = 'contact-requests';
        $activeSubTab = 'list-contact-requests';
        $main_category = MainCategory::where('major_category_id', '=', '2')->orderBy('name')->pluck('name', 'id');

        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');
        if ($request->ajax()) {
            $events = Events::with('get_subcat.mainCategory')->orderby('id', 'desc');
            $auth_user_type = Auth::user()->user_type;
            if ($auth_user_type != 1) {
                $events = $events->where('created_by', Auth::id());
            }
            if ($main_categories) { // && empty($keyword)
                $events->orWhereHas('get_subcat.mainCategory', function($q) use ($main_categories) {
                    $q->whereIn('id', $main_categories);
                });
            }
            // Global search function
            if ($keyword) {
                $events->where(function ($query) use ($keyword) {
                    $query->where('events.title', 'LIKE', '%' . $keyword . '%')
                        /*->orWhere('events.email', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('events.description', 'LIKE', '%' . $keyword . '%')*/;
                });
                //"select filed_list from tbale where condition1 and condtion2 and (condition3 or condtion4 or condigiton5) and condition6 orderby column limit 20"
            }
            if (!empty($date_from) && empty($keyword)) {
                $events->where('events.created_at', '>', $date_from . " 00:00:00");
            }

            if (!empty($date_from) && empty($keyword)) {
                $events->where('events.created_at', '<=', $date_to . " 23:59:59");
            }
            //dd($events->toSql());
            $events = $events->latest()->get();
            return DataTables::of($events)
                ->addColumn('active', function($event) use ($auth_user_type) {
                    if ($auth_user_type === 1) {
                        return '<input type="checkbox" class="event-status" data-toggle="switch" data-size="small"
                    data-on-color="green" data-on-text="ON" data-off-color="default" data-off-text="OFF"
                    data-id="' . $event->id . '" value="' . $event->status . '" ' . ($event->status ? "checked" : "") . '>';
                    }
                    return '';
                })
                ->addColumn('main_category', function($event) {
                    return isset($event->get_subcat->mainCategory) ? $event->get_subcat->mainCategory->name : '';
                })
                ->addColumn('sub_category', function($event) {
                    return isset($event->get_subcat) ? $event->get_subcat->name : '';
                })
                ->addColumn('publisher_name', function($event) {
                    if($event->is_publisher=="1"){
                        return $event->publish_by->name;
                    }
                    return 'N/A';
                })
                ->addColumn('action', function ($event) {
                    $btn = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('admin.event.edit', $event->id) . '">
                                            <i data-feather="edit"></i> Edit </a>
                                    <a class="dropdown-item" href="' . route('admin.event.show', $event->id) . '">
                                        <i data-feather="eye"></i> Preview </a>
                                    <a class="dropdown-item btn-icon modal-btn" onclick="confirmDelete('.$event->id.')" type="button" href="javascript:void(0)">
                                                                <i data-feather="trash-2"></i> Delete </a>
                                </div>
                            </div>';

                    return $btn;
                })
                ->editColumn('created_at', function($data){
                    /*return [
                        'display' => e($data->created_at->tz('Asia/Kolkata')->format('Y/m/d h:i a')),
                        'timestamp' => $data->created_at->timestamp
                    ];*/
                    return Carbon::parse($data->created_at, 'Asia/Dubai')->timestamp;
                   // return $data->created_at->tz('Asia/Dubai')->timestamp;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
        }


        return view('admin.events.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $major_category = MajorCategory::find(2);
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
        $main_categories = MainCategory::where('major_category_id', '=', 2)->orderBy('name')->pluck('name', 'id')->toArray();
        //$dynamic_main_category = DynamicMainCategory::where('major_category_id', 1)->get();
        $popular_types = PopularPlacesTypes::orderBy('name')->pluck('name', 'id')->toArray();
        $prices = ['$' => '$', '$$' => '$$', '$$$' => '$$$', '$$$$' => '$$$$', '$$$$$' => '$$$$$'];
        $cuisine_names = ['Indian' => 'Indian', 'Oriental' => 'Oriental', 'Pakistani' => 'Pakistani', 'Arabian' => 'Arabian', 'Afghani' => 'Afghani',
            'Chinese' => 'Chinese', 'European' => 'European', 'Continental' => 'Continental'];


        return view('admin.events.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'title' => 'required|unique:events,title',
                'slug' => 'required|unique:events,slug',
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
            $validatedData['lng'] = $request->citylong;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['mobile'] = $validatedData['full_phone_contact'] ?? $validatedData['mobile'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('event', $validatedData['slug']);
            $validatedData['cuisine_name'] = isset($validatedData['cuisine_name']) ? implode(', ', $validatedData['cuisine_name']) : '';
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_featured'] = $validatedData['is_featured'] ?? 0;
            $validatedData['reservation'] = $validatedData['reservation'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['assign_weekly_suggestion'] = $validatedData['assign_weekly_suggestion'] ?? 0;
            $validatedData['youtube_img'] = $validatedData['youtube_img'] ?? '';

            if ($validatedData['youtube_img'] == 1) {
                $validatedData['video'] = $request->video;
            }/* elseif ($validatedData['youtube_img'] == 2) {
                $validatedData['video'] = $youtube_image_name;
            }*/

            if (isset($validatedData['is_popular']) && $validatedData['is_popular'] == "1") {
                $validatedData['popular_types'] = json_encode($validatedData['popular_type']);
            }
            $validatedData['amenties'] = $request->amenities ?? [];
            $validatedData['landmarks'] = isset($request->landmark[0]['name']) ? $request->landmark : [];
            //dd($validatedData);
            $event = Events::create($validatedData);
            if ($event) {
                $id = $event->id;
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
                $response['msg'] = 'Event "' . $validatedData['title'] . '" was created successfully!';
                $response['primary_id'] = $event->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add event. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding event. Please try later.';
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
        $request->module_name = 'event';
        $request->user_type = 'admin';
        /*dd($request->input());*/
        $res = saveCommoncomponent($request);
        if ($res == false) {
            // DB::rollback();

            $message = [
                'message' => "Event more info has some issues try again.!",
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
            'message' => "Event more info has been save successfully",
            'alert-type' => 'success',
        ];
        return redirect()->back()->with($message);
    }

    /**
     * Display the specified resource.
     *
     * @param Events $event
     * @return \Illuminate\Http\Response
     */
    public function show(Events $event)
    {
        $event->load(['subCategory', 'city', 'featureImage', 'storyImages', 'mainImages', 'logoImage', 'menuImage', 'mainImage']);
        //$data = Events::with('subCategory', 'city')->where('slug', $id)->first();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $event->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        $amenties_event = $event->amenties;
        $landmark_event = $event->landmarks;
        return view('admin.events.preview', get_defined_vars());
    }


    public function preview(Events $event)
    {
        $event->load(['subCategory', 'city', 'featureImage', 'storyImages', 'mainImages', 'logoImage', 'menuImage', 'mainImage']);
        //$data = Events::with('subCategory', 'city')->where('slug', $id)->first();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $event->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        $amenties_event = $event->amenties;
        $landmark_event = $event->landmarks;
        return view('admin.events.preview', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Events $event)
    {
        if (Auth::user()->user_type != 1 && Auth::id() != $event->created_by) {
            abort(403);
        }

        $more_info = MoreInfo::where(['module_id' => $event->id, 'module_name' => 'event'])->get();
        $amenties_event = $event->amenties ?? [];
        $landmark_event = $event->landmarks ?? [];
        //dd($amenties_event, $landmark_event);
        $major_category = MajorCategory::find(2);
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
        $main_categories = MainCategory::where('major_category_id', '=', '2')->orderBy('name')->pluck('name', 'id')->toArray();
        $subcatgories = SubCategory::where('main_category_id', '=', isset($event->get_subcat->mainCategory) ? $event->get_subcat->mainCategory->id : '')->orderBy('name')->pluck('name', 'id')->toArray();

        //$dynamic_main_category = DynamicMainCategory::where('major_category_id', 1)->get();
        //$main_category_ids = isset($event->dynamic_main_ids) ? json_decode($event->dynamic_main_ids) : [];
        //$dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id', $main_category_ids)->get();

        /*$popular_types = PopularPlacesTypes::all();*/
        $select_popular_types = [];
        if ($event->is_popular == "1") {
            $select_popular_types = json_decode($event->popular_types, true);
        }
        $primary_id = $event->id;
        if(isset($event->start_date_time) && isset($event->end_date_time)) {
            $datetimefilter = $event->datetimefilter = Carbon::parse($event->start_date_time)->format('d/m/Y g:i A') .' - '.Carbon::parse($event->end_date_time)->format('d/m/Y g:i A');
        } else {
            $datetimefilter = '';
        }
        $featureImage = $event->featureImage;
        $floorPlanImage = $event->floorPlanImage;
        $logoImage = $event->logoImage;
        $menuImage = $event->menuImage;
        $mainImage = $event->mainImage;
        $mainImages = $event->mainImages;
        $storyImages = $event->storyImages;

        return view('admin.events.edit', get_defined_vars());
        /*return view('admin.events.edit',compact('popular_types','select_popular_types','four_images','story_four_images','event','landmark_event','amenties','amenties_event','main_category','landmarks','cities','subcatgories','dynamic_main_category','dynamic_sub_category'));*/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Events $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Events $event)
    {
        $request->validate(
            [
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'title' => 'required|min:1|max:100|'.Rule::unique('events')->ignore($event),
                'slug' => 'required|'.Rule::unique('events')->ignore($event),
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
            //amenties work
            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['lng'] = $request->citylong;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['mobile'] = $validatedData['full_phone_contact'] ?? $validatedData['mobile'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('event', $validatedData['slug']);
            $validatedData['cuisine_name'] = isset($validatedData['cuisine_name']) ? implode(', ', $validatedData['cuisine_name']) : '';
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_featured'] = $validatedData['is_featured'] ?? 0;
            $validatedData['reservation'] = $validatedData['reservation'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['assign_weekly_suggestion'] = $validatedData['assign_weekly_suggestion'] ?? 0;
            $validatedData['youtube_img'] = $validatedData['youtube_img'] ?? '';

            if ($validatedData['youtube_img'] == 1) {
                $validatedData['video'] = $request->video;
            }/* elseif ($validatedData['youtube_img'] == 2) {
                $validatedData['video'] = $youtube_image_name;
            }*/

            if (isset($validatedData['is_popular']) && $validatedData['is_popular'] == "1") {
                $validatedData['popular_types'] = json_encode($validatedData['popular_type']);
            }
            $validatedData['amenties'] = $request->amenities ?? [];
            $validatedData['landmarks'] = $request->landmark ?? [];

            if ($event->update($validatedData)) {
                $id = $event->id;
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

                $request->module_id = $id;
                $request->module_name = 'event';
                $request->user_type = 'admin';

                //sending notfication to particular for wish lists
                $wishlist_notifications = WishListDetails::where('item_type', 'App\Models\Events')
                    ->where('item_id', '=', $id)
                    ->where('status', '=', "1")
                    ->where('created_by', '!=', Auth::user()->id)
                    ->where('is_notification_need', '=', "1")
                    ->get();

                $title_msg = "Events Updated";
                $description = $request->title;
                $route = route('event', $event->slug);
                Helper::send_notification_wishlist_guys($wishlist_notifications, $route, $title_msg, $description);
                //sending notfication to particular for wish lists

                $response['error'] = false;
                $response['msg'] = 'Event "' . $validatedData['title'] . '" was updated successfully!';
                $response['primary_id'] = $event->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to update event. Please try later.';
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
     *
     * @param Events $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Events $event)
    {
        $response = [];
        try {
            if($event->delete()){
                $response['error'] = false;
                $response['msg'] = 'Event deleted successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while deleting event. Please try later';
            }
        } catch(\Exception $ex){
            $response['error'] = true;
            $this->log()->error($ex->getTraceAsString());
            $response['msg'] = 'There was some problem while processing your request. Please try later.';

        }
        return json_encode($response);
    }

    public function ajax_render_subcategory(Request $request)
    {
        $sub_cat = SubCategory::where('main_category_id', '=', $request->select_v)->get();
        echo json_encode($sub_cat);
        exit;
    }

    public function update_status_event(Request $request)
    {
        $response['error'] = false;
        abort_if(!$request->id, 400);
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $event = Events::find($id);
        try {
            abort_if(!$event, 404);
            if ($event->update([$field => $value])) {
                if ($event->is_publisher == "1" && $field === 'status') {

                    if ($request->status == "1") {
                        $satuts_label = "Approved";
                    } else {
                        $satuts_label = "Rejected";
                    }

                    $description_event = $satuts_label . " By Admin";
                    $message_event = "Admin {$satuts_label} event list";
                    $url_now = route('publisher.event.edit', $event->id);

                    event(new MyEvent($message_event, $description_event, $url_now, "1", $event->created_by));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_event;
                    $notification->description = $description_event;
                    $notification->notification_for = 1;
                    $notification->url = $url_now;
                    $notification->notify_to = $event->created_by;
                    $notification->save();
                }
                $response['msg'] = 'Event updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating event. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return response()->json($response);
    }

    public function eventReservation(Request $request)
    {
        $datas = EventReservation::with('event')->get();
        return view('admin.events.event-reservation', compact('datas'));
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
                $destinationPath = config('app.upload_event_path') . $image_type;
                $imageName = $destinationPath . '/' . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($photo), 'public');
                $blog_photo = EventImage::create([
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
            $image = EventImage::findOrFail($request->id);
            $tmp_obj = $image;
            if ($image->delete()) {
                if (file_exists(public_path($tmp_obj->image))) {
                    @unlink(public_path($tmp_obj->image));
                }
                $destinationPath = config('app.upload_event_path') . '/' . $image->image_type;
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
            $eventImage = EventImage::find($img_id);
            if ($eventImage) {
                $alt_texts['en'] =  $request->alt_text_en[$img_id] ?? '';
                $eventImage->update([
                    'event_id' => $id,
                    'alt_texts' => $alt_texts,
                ]);
            }
        }
    }
}
