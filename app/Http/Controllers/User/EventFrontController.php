<?php

namespace App\Http\Controllers\User;

use App\Models\City;
use App\Models\DynamicLink;
use App\Models\News;
use App\Models\Recommendation;
use App\Models\State;
use App\Models\Venue;
use App\Models\Events;
use App\Models\MoreInfo;
use App\Models\AlertNews;
use App\Models\EnquireForm;
use App\Models\SliderImage;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\MajorCategory;
use App\Models\HomeTrendBanner;
use App\Models\EventReservation;
use App\Models\InfluencerReview;
use App\Mail\ConciergeEnquiryMail;
use App\Models\DynamicSubCategory;
use App\Models\ItemRecommendation;
use Illuminate\Support\Facades\DB;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class EventFrontController extends Controller
{
    //

    public function event(Request $request, $category_slug = '')
    {

        // return $request->all();

        $all_main_category = MainCategory::where('major_category_id', '=', '2')->pluck('id')->toArray();

        $dynamic_sub_categories = DynamicSubCategory::all();

        $dynamic_categories = DynamicMainCategory::where('major_category_id', '=', '2')->get();

        //$cities = City::all();
        $states = State::all();
        $cities = Events::where('city', '!=', '')->pluck('city', 'city')->toArray();

        $result_array = $this->search_event($request, $category_slug);


        $all_events = $result_array['all_events'];
        $total_count = $result_array['total_count'];
        $event_category = $result_array['event_category'];
        $feature_events = $result_array['feature_events'];
        $popular_events = $result_array['popular_events'];
        $nextPageUrl = $result_array['nextPageUrl'];

        $justJoin = $all_events->take($request->get('limit', 10))->sortByDesc('created_at');

        $sub_cat_search = array();

        if (isset($_GET['sub_category'])) {
            if ($_GET['sub_category'] != "all") {
                $sub_cat_search = SubCategory::where('main_category_id', '=', $_GET['main_cat'])->get();
            }
        }

        $main_category = MainCategory::where('major_category_id', '=', '2')->get();
        $dynamic_mains = DynamicMainCategory::where('major_category_id', '=', '2')->where('status', 1)->get();
        $banner = SliderImage::where('major_category_id', 2)->get();
        $major_category = MajorCategory::find(2);
        $major_category->load(['searchLinksTop', 'searchLinksBottom', 'statistics', 'bannerLinksTop', 'bannerLinksRight', 'bannerLinksBottom', 'bannerLinksLeft']);
        $categories = MainCategory::where('major_category_id', 2)->withCount('event')->get();
        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();

        $categoryForSidebar = MainCategory::with('subCategory.event')->withCount('event')->where('major_category_id', 2)->orderBy('event_count', 'DESC')->get();
        $allCategory = MajorCategory::with(['mainCategory', 'mainCategory.subCategory']);

        $quick_search = $request->quick_search ?? '';
        $main_cat = $request->main_cat ?? '';
        $sub_category = $request->sub_category ?? '';
        $sub_category_id = $request->sub_category_id ?? '';
        $city_search = $request->city ?? '';
        $location_search = $request->location ?? '';
        $area = $request->area ?? '';
        $distance = $request->distance ?? '';
        $date_from = $request->date_from ?? '';
        $date_to = $request->date_to ?? '';
        $sort_by = $request->sort_by ?? '';

        if ($request->ajax()) {
            return $view = view('user.event.list', get_defined_vars())->render();
        }

        if(!empty($category_slug) || !empty($request->main_cat)) {
            if($sub_category_id) {
                $cat_details = SubCategory::find($sub_category_id);
            } else if(!empty($main_cat) && $main_cat != 'all') {
                $cat_details = MainCategory::find($main_cat);
            } else {
                $cat_details = null;
            }

            return view('user.event.event-listing', get_defined_vars());
        }
        $dynamic_links = DynamicLink::where('major_category_id', 2)->whereNotIn('slug', ['popular-events', 'trending-events', 'hot-events'])->get();
        $popular_links = $major_category->popularDynamicLinks()->first();
        $trending_links = $major_category->trendingDynamicLinks()->first();
        $hot_links = $major_category->hotDynamicLinks()->first();
        $top_searches = MainCategory::with('topSearch')
            ->withCount('topSearch')
            ->where('major_category_id', 2)
            ->orderBy('top_search_count', 'DESC')
            ->get();
        $popular_searches = MainCategory::with('popularSearch')
            ->withCount('popularSearch')
            ->where('major_category_id', 2)
            ->orderBy('popular_search_count', 'DESC')
            ->get();
        return view('user.event.index', get_defined_vars());
    }

    public function search_event($request, $category_slug)
    {
        $result_array = array();
        if (!empty($category_slug)) {
            if($category_slug === 'all') {
                $request->main_cat = 'all';
            } else {
                $main_category = MainCategory::where('slug', $category_slug)->first();
                if ($main_category) {
                    $request->main_cat = $main_category->id;
                }
            }
        }

        $quick_search = $request->quick_search ?? '';
        $main_cat = $request->main_cat ?? '';
        $sub_category = $request->sub_category ?? '';
        $sub_category_id = $request->sub_category_id ?? '';
        $city_search = $request->city ?? '';
        $location_search = $request->location ?? '';
        $area = $request->area ?? '';
        $distance = $request->distance ?? '';
        $sort_by = $request->sort_by ?? '';
        $date_from = $request->date_from ?? '';
        $date_to = $request->date_to ?? '';
        $pageNo = $request->get('page', 1);
        $perPage = 10;
        $totalItems = Events::select(['id'])->active()->count();
        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/events?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
            $nextPageUrl .= "&main_cat=".$main_cat;
            $nextPageUrl .= "&sub_category=".$sub_category;
            $nextPageUrl .= "&sub_category_id=".$sub_category_id;
            $nextPageUrl .= "&city=".urlencode($city_search);
            $nextPageUrl .= "&location=".urlencode($location_search);
            $nextPageUrl .= "&area=".$area;
            $nextPageUrl .= "&distance=".$distance;
            $nextPageUrl .= "&date_from=".$date_from;
            $nextPageUrl .= "&date_to=".$date_to;
            $nextPageUrl .= "&sort_by=".urlencode($sort_by);
        }
        else {
            $nextPageUrl = '';
        }
        $all_events = Events::with(['get_subcat', 'featureImage', 'approvedReviews'])->where('status', '=', '1');

        $event_category = Events::with(['get_subcat'])->where('status', '=', '1')
            ->select('title', 'sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        $feature_events = Events::with(['get_subcat', 'featureImage'])->where('status', '=', '1')->where('is_featured', '=', '1');

        $popular_events = Events::with(['get_subcat', 'featureImage'])->where('status', 1)->where('is_popular', 1);


        if (isset($request)) {
            if (isset($request->sort_by)) {
                if ($request->sort_by == "1") {  // newest to oldest
                    $all_events = $all_events->orderBy('created_at', 'desc');
                    $feature_events = $feature_events ? $feature_events->orderBy('created_at', 'desc') : null;
                    $popular_events = $popular_events ? $popular_events->orderBy('created_at', 'desc') : null;
                } elseif ($request->sort_by == "2") { //oldest to newest

                    $all_events = $all_events->orderBy('created_at', 'asc');
                    $feature_events = $feature_events ? $feature_events->orderBy('created_at', 'asc') : null;
                    $popular_events = $popular_events ? $popular_events->orderBy('created_at', 'asc') : null;
                } elseif ($request->sort_by == "3") { // A to Z
                    // $all_events =   $all_events->orderBy('title', 'desc');
                    // $feature_events =  $feature_events->orderBy('title', 'desc');

                    $all_events = $all_events->orderBy('title', 'asc');
                    $feature_events = $feature_events ? $feature_events->orderBy('title', 'asc') : null;
                    $popular_events = $popular_events ? $popular_events->orderBy('title', 'asc') : null;
                } elseif ($request->sort_by == "4") {  // Z to A


                    $all_events = $all_events->orderBy('title', 'desc');
                    $feature_events = $feature_events ? $feature_events->orderBy('title', 'desc') : null;
                    $popular_events = $popular_events ? $popular_events->orderBy('title', 'desc') : null;
                }
            } else {
                $all_events = $all_events->orderBy('created_at', 'desc');
                $feature_events = $feature_events ? $feature_events->orderBy('created_at', 'desc') : null;
                $popular_events = $popular_events ? $popular_events->orderBy('created_at', 'desc') : null;
            }

            if (isset($request->quick_search)) {   //quick search

                $search = $request->quick_search;

                $main_cat_ids = MainCategory::where('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();

                $sub_cat_ids = SubCategory::where('name', 'LIKE', '%' . $search . '%')
                    ->orWhereIn('main_category_id', $main_cat_ids)
                    ->pluck('id')
                    ->toArray();

                $all_events = Events::with('get_subcat', 'featureImage')
                    ->where(function ($query) use ($search, $sub_cat_ids) {
                        $query->orWhere('title', 'LIKE', '%' . $search . '%')
                            ->orWhere('description', 'LIKE', '%' . $search . '%')
                            ->orWhereIn('sub_category_id', $sub_cat_ids);
                    })
                    ->where('status', '=', '1');
            }


            if (isset($request->event_name)) {  //event name

                $attribute = $request->event_name;
                $all_events = $all_events->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });

                $event_category = $event_category->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });
            }

            if ($request->main_cat != "all" && isset($request->main_cat)) { //main cat

                $sub_cat_ids = SubCategory::where('main_category_id', '=', $request->main_cat)->pluck('id')->toArray();

                $all_events = $all_events->whereIn('sub_category_id', $sub_cat_ids);

                $event_category = $event_category->whereIn('sub_category_id', $sub_cat_ids);
            }

            if ($request->sub_category != "all" && isset($request->sub_category)) {  // sub cat

                $all_events = $all_events->where('sub_category_id', $request->sub_category);

                $event_category = $event_category->whereIn('sub_category_id', $request->sub_category);
            }

            if ($request->location != "all" && isset($request->location)) { //location means city id

                $all_events = $all_events->where('city', $request->location);
                $event_category = $event_category->where('city', $request->location);
                //$feature_events = $popular_events = [];
            }

            if (isset($request->sub_category_id)) {
                $all_events = $all_events->where('sub_category_id', $request->sub_category_id);
            }

            if (isset($request->sub_cate_id)) {  //event name

                $attribute = $request->sub_cate_id;
                $all_events = $all_events->filter(function ($item) use ($attribute) {
                    return strpos($item->sub_category_id, $attribute) !== false;
                });

                //$feature_events = $popular_events = [];
            }
            if (isset($request->date_from)) {
                $all_events = $all_events->where('date_time', '>', $request->date_from . " 00:00:00");
            }

            if (isset($request->date_to)) {
                $all_events = $all_events->where('date_time', '<=', $request->date_to . " 23:59:59");
            }
        }

        $feature_events = $feature_events->get();
        $popular_events = $popular_events->get();
        $total_count = 0;
        if (isset($all_events)) {
            $total_count = $all_events->count();
        }

        $result_array = array(
            'all_events' => $all_events->paginate($perPage),
            'total_count' => $total_count,
            'event_category' => $event_category,
            'feature_events' => $feature_events,
            'popular_events' => $popular_events,
            'nextPageUrl' => $nextPageUrl,
        );

        return $result_array;
    }

    public function event_detail($slug)
    {
        $event = Events::where('slug', '=', $slug)->first();
        $event->load(['approvedReviews', 'featureImage', 'logoImage', 'floorPlanImage', 'menuImage', 'mainImage', 'mainImages', 'storyImages']);
        $data = $event;
        $type = 'event';
        $more_info = NULL;
        if (isset($data->id)) {
            $more_info = MoreInfo::where(['module_id' => $data->id, 'module_name' => 'event'])->get();
        }
        $similar = Events::with('get_subcat', 'featureImage', 'approvedReviews')->where('status', 1)->where('sub_category_id', $event->sub_category_id)->where('id', '!=', $event->id)->get();
        $popular = Events::with('get_subcat', 'featureImage', 'approvedReviews')->where('status', 1)->where('is_popular', 1)->get();
        $event_category = Events::where('status', '=', '1')
            ->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $event->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        // avg rating
        //$reviews = Events::where('slug', $slug)->first();
        $avg_rating = round($event->approvedReviews->avg('rating'), 1);

        $amenties_event = $event->amenties;
        $landmark_event = $event->landmarks;
        if ($event->lat && $event->lng) {
            $nearby = Events::with(['featureImage', 'approvedReviews'])->select(
                    "events.id",
                    DB::raw("6371 * acos(cos(radians(" . $event->lat . "))
                * cos(radians(events.lat))
                * cos(radians(events.lng) - radians(" . $event->lng . "))
                + sin(radians(" . $event->lat . "))
                * sin(radians(events.lat))) AS distance"),
                    "events.*",
                    "sub_categories.name as sub_category"
                )
                // ->with('subCategory')
                ->join('sub_categories', 'sub_categories.id', 'events.sub_category_id')
                ->where('events.status', 1)
                ->groupBy("events.id")
                ->orderBy('distance', 'ASC')
                ->get();
        } else {
            $nearby = [];
        }
        $shareIcons = createSocialShareIcons($event, 'event');
        //$recommended = ItemRecommendation::whereHasMorph('item', [Events::class])->where('status', 1)->get();
        $recommended = Recommendation::where(['module_type' => 'events', 'module_id' => $event->id])->count();
        $count = $event->views + 1;
        $event->views = $count;
        $event->timestamps = false;
        $event->save();

        $alert = AlertNews::first();

        $firstStoryImage = $event->storyImages()->first();
        $whatsapp = MajorCategory::where('id', 2)->first();
        $breadcrumbUrl = route('event-list', ['category_slug' => $event->subCategory->mainCategory->slug]);
        if($event->mainImage) {
            $og_image = $event-> getStoredImage($event->mainImage->image, 'main_image');
        } else if($event->featureImage) {
            $og_image = $event-> getStoredImage($event->featureImage->image, 'feature_image');
        } else {
            $og_image = '/v2/images/image-placeholder.jpeg';
        }

        return view('user.event.event-detail', get_defined_vars());
        /*return view('user.event.event-detail',compact('event','four_images','amenties_event','landmark_event','similar','event_category',
            'nearby', 'avg_rating','youtube','alert', 'whatsapp', 'recommended'));*/
    }

    public function eventsByType(Request $request, $type)
    {
        $main_category = MainCategory::where('major_category_id', 2)->pluck('id')->toArray();
        $cat_details = DynamicLink::where('major_category_id', 2)->where('slug', $type)->first();
        $dynamic_links = DynamicLink::where('major_category_id', 2)->pluck('link_title', 'slug')->toArray();
        $quick_search = $request->quick_search ?? '';
        $type = $request->type ?? $type;
        $sort_by = $request->sort_by ?? 1;
        $pageNo = $request->get('page', 1);
        $perPage = 10;
        if($cat_details->related_items) {
            $totalItems = Events::select(['id'])->active()->whereIn('id', $cat_details->related_items);
        } else {
            $totalItems = Events::select(['id'])->active();
        }
        $totalItems = $totalItems->count();
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $totalItems = $totalItems->{$scope}()->count();
        } else {
            $totalItems = $totalItems->count();
        }*/

        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/events/view-listings/".$type."?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
            $nextPageUrl .= "&sort_by=".$sort_by;
        }
        else {
            $nextPageUrl = '';
        }

        $all_events = Events::with('get_subcat', 'featureImage', 'approvedReviews')->active();
        if($cat_details->related_items) {
            $all_events = $all_events->whereIn('id', $cat_details->related_items);
        }
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $all_events = $all_events->{$scope}();
        }*/
        if (isset($request)) {
            if (!empty($quick_search)) {
                $search = $quick_search;
                $all_events = $all_events->where(function ($query) use ($search) {
                    $query->orWhere('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%');
                });
            }
            if (!empty($sort_by)) {
                if ($sort_by == "1") {
                    $all_events = $all_events->orderBy('created_at', 'desc');
                } elseif ($sort_by == "2") { //oldest to newest
                    $all_events = $all_events->orderBy('created_at', 'asc');
                } elseif ($sort_by == "3") { // A to Z
                    $all_events = $all_events->orderBy('title', 'asc');
                } elseif ($sort_by == "4") { // Z to A
                    $all_events = $all_events->orderBy('title', 'desc');
                }
            } else {
                $all_events = $all_events->orderBy('created_at', 'desc');
            }
        }

        //dd($all_events->toSql());
        $total_count = 0;
        if (isset($all_events)) {
            $total_count = $all_events->count();
        }
        $all_events = $all_events->paginate(10);
        //dd($all_events->toSql(), $total_count);
        if ($request->ajax()) {
            //dd($all_events->toSql(), $total_count);
            return $view = view('user.event.list', get_defined_vars())->render();
        }

        return view('user.common.listings-by-type', get_defined_vars());
    }

    function search_event_name_ajax(Request $request)
    {

        $search = $request->keyword;

        $events = array();
        if (!empty($search)) {
            $events = Events::select('title', 'id')->where('title', 'LIKE', '%' . $search . '%')->orderby('title')->limit(6)->get();
        }


        $view = view('user.event.ajax.autocomplete_event_ajax', compact('events'))->render();

        return response()->json(['html' => $view]);
    }

    public function eventSort($request)
    {

        // if($request->sort) {
        //     $res='1';
        //     $sort_by=$request->sort_by;

        //     $featured = Venue::with('subCategory', 'city')->where('status', 1)->where('assign_featured', 1);
        //     if($sort_by=='1'){
        //         $featured = $featured->orderBy('created_at', 'desc');
        //     }
        //     elseif($sort_by=='2'){
        //         $featured = $featured->orderBy('created_at', 'asc');
        //     }
        //     elseif($sort_by=='3'){
        //         $featured = $featured->orderBy('title', 'desc');
        //     }
        //     elseif($sort_by=='4'){
        //         $featured = $featured->orderBy('title', 'asc');
        //     }
        //     $featured = $featured->get();

        //     $datas = Venue::with('subCategory', 'city')->where('status', 1);
        //     if($sort_by=='1'){
        //         $datas = $datas->orderBy('created_at', 'desc');
        //     }
        //     elseif($sort_by=='2'){
        //         $datas = $datas->orderBy('created_at', 'asc');
        //     }
        //     elseif($sort_by=='3'){
        //         $datas = $datas->orderBy('title', 'desc');
        //     }
        //     elseif($sort_by=='4'){
        //         $datas = $datas->orderBy('title', 'asc');
        //     }
        //     $datas = $datas->get();


        // $all_main_category = MainCategory::where('major_category_id','=','2')->pluck('id')->toArray();
        // $dynamic_sub_categories = DynamicSubCategory::all();
        // $dynamic_categories = DynamicMainCategory::where('major_category_id','=','2')->get();
        // $cities = City::all();


        // $result_array =  $this->search_event($request);


        // $all_events =   $result_array['all_events'];
        // $total_count =  $result_array['total_count'];
        // $event_category = $result_array['event_category'];
        // $feature_events = $result_array['feature_events'];


        // $sub_cat_search = array();

        // if(isset($_GET['sub_category'])){
        //     if($_GET['sub_category'] != "all"){
        //         $sub_cat_search = SubCategory::where('main_category_id','=',$_GET['main_cat'])->get();
        //     }
        // }

        // $main_category = MainCategory::where('major_category_id','=','2')->get();
        // $dynamic_mains = DynamicMainCategory::where('major_category_id','=','2')->get();

        // return view('user.event.event',compact('cities','dynamic_categories', 'dynamic_mains','sub_cat_search','dynamic_sub_categories','main_category','feature_events','all_events','total_count','event_category'));;
        // }
    }

    public function eventAjaxReservation(Request $request)
    {

        $date = date_create($request->date);
        $date = date_format($date, "Y-m-d H:i");


        $enquire = new EnquireForm(['name' => $request->name, 'email' => $request->email, 'mobile' => $request->mobile, 'message' => $request->message1]);
        $type = Events::find($request->item_id);
        $email = $type->email;
        $type->enquiries()->save($enquire);
        $obj = new EventReservation();
        $obj->person = $request->person;
        $obj->man = $request->no_man;
        $obj->woman = $request->no_woman;
        $obj->children = $request->no_child;
        $obj->user_id = $request->user_id;
        $obj->name = $request->name;
        $obj->mobile_no = $request->mobile_no;
        $obj->email = $request->email;
        $obj->message = $request->message;
        $obj->booking_date = $date;
        $obj->event_id = $request->event_id;
        $obj->booking_type = $request->booking_type;
        $obj->save();

        $subject = "Event Enquiry Mail";

        $date_time_concierge = strtotime($obj->booking_date);
        $date_concierge = date('d/M/Y', $date_time_concierge);
        $time_concierge = date('g:i A', $date_time_concierge);
        $details = [
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'message' => $request->message1,
            'date_concierge' => $date_concierge,
            'time_concierge' => $time_concierge,
            'men' => $request->no_man,
            'women' => $request->no_woman,
            'child' => $request->no_child,
            'total_person' => $request->person,
            'title' => $type->title,
            'subject' => $subject
        ];


        if (!$email) {
            $email = 'farhaz@mailinator.com';
        }
        $contactUs = $url = \config('extra.contact_us');
        $emails = [$email, $contactUs];

        \Mail::to($emails)->send(new ConciergeEnquiryMail($details));

        $message = [
            "message" => "Successfully Inserted",
            "status" => 200
        ];
        return response()->json($message, 200);
    }
}
