<?php

namespace App\Http\Controllers\User;

use App\Models\DynamicLink;
use App\Models\Recommendation;
use DB;
use App\Models\Job;
use App\Models\Blog;
use App\Models\City;
use App\Models\News;
use App\Models\State;
use App\Models\Venue;
use App\Models\Banner;
use App\Models\Events;
use App\Models\BuySell;
use App\Models\Gallery;
use App\Models\Tickets;
use App\Models\AlertNews;
use App\Models\Concierge;
use App\Models\Directory;
use App\Models\CountClick;
use App\Models\Influencer;
use App\Models\EnquireForm;
use App\Models\SliderImage;
use App\Models\MoreInfo;
use App\Models\SubCategory;
use App\Models\MainCategory;
use App\Models\MajorCategory;
use Illuminate\Http\Request;
use App\Models\InfluencerReview;
use App\Models\VenueReservation;
use App\Models\ItemRecommendation;
use App\Mail\ConciergeEnquiryMail;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use App\Constants\MajorCategoryConst;
use App\Models\HomeSectionContent;
use App\Models\HomeTrendBanner;
use App\Models\DynamicSubCategory;
use App\Models\Education;
use Illuminate\Support\Str;

class VenueController extends Controller
{
    public function venue(Request $request, $category_slug = '')
    {

        $result_array = $this->search_venue($request, $category_slug);

        $all_venues = $result_array['all_venues'];
        $total_count = $result_array['total_count'];
        $venue_category = $result_array['venue_category'];
        $feature_venues = $result_array['feature_venue'];
        $popular_venues = $result_array['popular_venue'];
        $nextPageUrl = $result_array['nextPageUrl'];

        $quick_search = $request->quick_search ?? '';
        $main_cat = $request->main_cat ?? '';
        $sub_category = $request->sub_category ?? '';
        $sub_category_id = $request->sub_category_id ?? '';
        $city_search = $request->city ?? '';
        $location_search = $request->location ?? '';
        $area = $request->area ?? '';
        $distance = $request->distance ?? '';
        $sort_by = $request->sort_by ?? '';

        if($main_cat) {
            $sub_cat_search = SubCategory::where('main_category_id', '=', $main_cat)->pluck('name', 'id')->toArray();
        } else {
            $sub_cat_search = [];
        }
        if ($request->ajax()) {
            return $view = view('user.venue.list', get_defined_vars())->render();
        }
        $main_category = MainCategory::where('major_category_id', '=', '1')->pluck('id')->toArray();
        $cities = Venue::where('city', '!=', 'null')->pluck('city', 'city')->toArray();
        $states = Venue::where('area', '!=', 'null')->pluck('area', 'area')->toArray();
        $cuisins = Venue::where('cusine_name', '!=', 'null')->pluck('cusine_name', 'cusine_name')->toArray();
        $justJoin = $all_venues->take($request->get('limit', 10))->sortByDesc('created_at');

        $main_category = MainCategory::where('major_category_id', '=', '1')->get();
        $banner = SliderImage::where('major_category_id', 1)->get();
        $major_category = MajorCategory::find(1);
        $major_category->load(['searchLinksTop', 'searchLinksBottom', 'statistics', 'bannerLinksTop', 'bannerLinksRight', 'bannerLinksBottom', 'bannerLinksLeft']);

        $categories = MainCategory::where('major_category_id', 1)->withCount('venue')->get();

        $categoryForSidebar = MainCategory::with('subCategory.venue')->withCount('venue')->where('major_category_id', 1)->orderBy('venue_count', 'DESC')->get();
        $allCategory = MajorCategory::with(['mainCategory', 'mainCategory.subCategory']);

        if (!empty($category_slug) || !empty($request->main_cat)) {
            if ($sub_category_id) {
                $cat_details = SubCategory::find($sub_category_id);
            } else if (!empty($main_cat) && $main_cat != 'all') {
                $cat_details = MainCategory::find($main_cat);
            } else {
                $cat_details = null;
            }

            return view('user.venue.venue-listing', get_defined_vars());
        }
        $dynamic_links = DynamicLink::where('major_category_id', 1)->whereNotIn('slug', ['popular-venues', 'trending-venues', 'hot-venues'])->get();
        $popular_links = $major_category->popularDynamicLinks()->first();
        $trending_links = $major_category->trendingDynamicLinks()->first();
        $hot_links = $major_category->hotDynamicLinks()->first();
        $top_searches = MainCategory::with('topSearch')
            ->withCount('topSearch')
            ->where('major_category_id', 1)
            ->orderBy('top_search_count', 'DESC')
            ->get();
        $popular_searches = MainCategory::with('popularSearch')
            ->withCount('popularSearch')
            ->where('major_category_id', 1)
            ->orderBy('popular_search_count', 'DESC')
            ->get();

        return view('user.venue.index', get_defined_vars());
    }

    public function search_venue($request, $category_slug)
    {
        $result_array = array();
        if (!empty($category_slug)) {
            if ($category_slug === 'all') {
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
        $pageNo = $request->get('page', 1);
        $perPage = 10;
        $totalItems = Venue::select(['id'])->active()->count();
        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/venues?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
            $nextPageUrl .= "&main_cat=".$main_cat;
            $nextPageUrl .= "&sub_category=".$sub_category;
            $nextPageUrl .= "&sub_category_id=".$sub_category_id;
            $nextPageUrl .= "&city=".urlencode($city_search);
            $nextPageUrl .= "&location=".urlencode($location_search);
            $nextPageUrl .= "&area=".$area;
            $nextPageUrl .= "&distance=".$distance;
            $nextPageUrl .= "&sort_by=".urlencode($sort_by);
        }
        else {
            $nextPageUrl = '';
        }

        $all_venues = Venue::with('get_subcat', 'featureImage', 'approvedReviews')->active()->orderBy('created_at', 'DESC');

        $venue_category = Venue::select('title', 'sub_category_id', DB::raw('count(*) as total'))
            ->active()->groupBy("sub_category_id")->orderBy('created_at', 'DESC');

        $feature_venue = Venue::with('get_subcat', 'featureImage', 'approvedReviews')->active()->featured()->orderBy('created_at', 'DESC');

        $popular_venue = Venue::with('get_subcat', 'featureImage', 'approvedReviews')->active()->popular()->orderBy('created_at', 'DESC');


        if (isset($request)) {
            if (isset($request->sort_by)) {
                if ($request->sort_by == "1") {
                    $all_venues = Venue::with('get_subcat', 'featureImage', 'approvedReviews')->active()
                        ->orderBy('created_at', 'desc');

                    $feature_venue = $feature_venue ? $feature_venue->orderBy('created_at', 'desc') : null;
                    $popular_venue = $popular_venue ? $popular_venue->orderBy('created_at', 'desc') : null;
                } elseif ($request->sort_by == "2") { //oldest to newest

                    $all_venues = Venue::with('get_subcat', 'featureImage', 'approvedReviews')->active()->orderBy('created_at', 'asc');

                    $feature_venue = $feature_venue ? $feature_venue->orderBy('created_at', 'asc') : null;
                    $popular_venue = $popular_venue ? $popular_venue->orderBy('created_at', 'asc') : null;
                } elseif ($request->sort_by == "3") { // A to Z
                    $all_venues = Venue::with('get_subcat', 'featureImage', 'approvedReviews')->active()
                        ->orderBy('title', 'asc');

                    $feature_venue = $feature_venue ? $feature_venue->orderBy('title', 'asc') : null;
                    $popular_venue = $popular_venue ? $popular_venue->orderBy('title', 'asc') : null;
                } elseif ($request->sort_by == "4") { // Z to A
                    $all_venues = Venue::with('get_subcat', 'featureImage', 'approvedReviews')->active()
                        ->orderBy('title', 'desc');

                    $feature_venue = $feature_venue ? $feature_venue->orderBy('title', 'desc') : null;
                    $popular_venue = $popular_venue ? $popular_venue->orderBy('title', 'desc') : null;
                }
            }

            if (isset($request->quick_search)) {
                $search = $request->quick_search;

                $main_cat_ids = MainCategory::where('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();

                $sub_cat_ids = SubCategory::where('name', 'LIKE', '%' . $search . '%')
                    ->orWhereIn('main_category_id', $main_cat_ids)
                    ->pluck('id')
                    ->toArray();

                $all_venues = Venue::with('get_subcat', 'featureImage', 'approvedReviews')
                    ->where(function ($query) use ($search, $sub_cat_ids) {
                        $query->orWhere('title', 'LIKE', '%' . $search . '%')
                            ->orWhere('description', 'LIKE', '%' . $search . '%')
                            ->orWhereIn('sub_category_id', $sub_cat_ids);
                    })
                    ->active();
            }

            if (isset($request->venue_name)) { //event name

                $attribute = $request->venue_name;
                $all_venues = $all_venues->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });

                $venue_category = $venue_category->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });
            }

            if ($request->main_cat != "all" && isset($request->main_cat)) { //main cat

                $sub_cat_ids = SubCategory::where('main_category_id', '=', $request->main_cat)->pluck('id', 'id')->toArray();

                $all_venues = $all_venues->whereIn('sub_category_id', $sub_cat_ids);
                $venue_category = $venue_category->whereIn('sub_category_id', $sub_cat_ids);
            }

            if (isset($request->cusine_name) && $request->cusine_name != 'all') { //cuisin

                $all_venues = $all_venues->where('cusine_name', 'LIKE', '%' . $request->cusine_name . '%');

                $venue_category = $venue_category->where('cusine_name', 'LIKE', '%' . $request->cusine_name . '%');
            }

            if ($request->sub_category != "all" && isset($request->sub_category)) { // sub cat

                $all_venues = $all_venues->where('sub_category_id', $request->sub_category);

                $venue_category = $venue_category->where('sub_category_id', $request->sub_category);
            }

            if ($request->location != "all" && isset($request->location)) { //location means city id

                $all_venues = $all_venues->where('city', $request->location);
                $venue_category = $venue_category->where('city', $request->location);
            }
            if ($request->area != "all" && isset($request->area)) {
                // $city_ids = City::where('state_id', $request->city)->pluck('id')->toArray();
                $all_venues = $all_venues->where('area', $request->area);
                $venue_category = $venue_category->where('city', $request->area);
            }

            if (isset($request->sub_category_id)) {
                $all_venues = $all_venues->where('sub_category_id', $request->sub_category_id);
            }

            if (isset($request->sub_cate_id)) { //event name

                $attribute = $request->sub_cate_id;
                $all_venues = $all_venues->filter(function ($item) use ($attribute) {
                    return strpos($item->sub_category_id, $attribute) !== false;
                });
            }
        }

        $feature_venue = $feature_venue->get();
        $popular_venue = $popular_venue->get();
        $venue_category = $venue_category->get();
        $total_count = 0;

        if (isset($all_venues)) {
            $total_count = $all_venues->count();
        }


        $result_array = array(
            'all_venues' => $all_venues->paginate($perPage),
            'total_count' => $total_count,
            'venue_category' => $venue_category,
            'feature_venue' => $feature_venue,
            'popular_venue' => $popular_venue,
            'nextPageUrl' => $nextPageUrl,
        );

        return $result_array;
    }

    public function venuesByType(Request $request, $type)
    {
        $main_category = MainCategory::where('major_category_id', 1)->pluck('id')->toArray();
        $cat_details = DynamicLink::where('major_category_id', 1)->where('slug', $type)->first();
        $dynamic_links = DynamicLink::where('major_category_id', 1)->pluck('link_title', 'slug')->toArray();
        $quick_search = $request->quick_search ?? '';
        $type = $request->type ?? $type;
        $sort_by = $request->sort_by ?? 1;
        $pageNo = $request->get('page', 1);
        $perPage = 10;
        if($cat_details->related_items) {
            $totalItems = Venue::select(['id'])->active()->whereIn('id', $cat_details->related_items);
        } else {
            $totalItems = Venue::select(['id'])->active();
        }
        $totalItems = $totalItems->count();
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $totalItems = $totalItems->{$scope}()->count();
        } else {
            $totalItems = $totalItems->count();
        }*/

        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/venues/view-listings/".$type."?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
            $nextPageUrl .= "&sort_by=".$sort_by;
        }
        else {
            $nextPageUrl = '';
        }

        $all_venues = Venue::with('get_subcat', 'featureImage', 'approvedReviews')->active();
        if($cat_details->related_items) {
            $all_venues = $all_venues->whereIn('id', $cat_details->related_items);
        }
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $all_venues = $all_venues->{$scope}();
        }*/
        if (isset($request)) {
            if (!empty($quick_search)) {
                $search = $quick_search;
                $all_venues = $all_venues->where(function ($query) use ($search) {
                    $query->orWhere('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%');
                    });
            }
            if (!empty($sort_by)) {
                if ($sort_by == "1") {
                    $all_venues = $all_venues->orderBy('created_at', 'desc');
                } elseif ($sort_by == "2") { //oldest to newest
                    $all_venues = $all_venues->orderBy('created_at', 'asc');
                } elseif ($sort_by == "3") { // A to Z
                    $all_venues = $all_venues->orderBy('title', 'asc');
                } elseif ($sort_by == "4") { // Z to A
                    $all_venues = $all_venues->orderBy('title', 'desc');
                }
            } else {
                $all_venues = $all_venues->orderBy('created_at', 'desc');
            }
        }

        //dd($all_venues->toSql());
        $total_count = 0;
        if (isset($all_venues)) {
            $total_count = $all_venues->count();
        }
        $all_venues = $all_venues->paginate(10);
        //dd($all_venues->toSql(), $total_count);
        if ($request->ajax()) {
            //dd($all_venues->toSql(), $total_count);
            return $view = view('user.venue.list', get_defined_vars())->render();
        }

        return view('user.common.listings-by-type', get_defined_vars());
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function venueList(Request $request)
    {
        $featured = Venue::with('subCategory', 'city')->where('status', 1)->where('assign_featured', 1)->get();
        $popular = Venue::with('subCategory', 'city')->where('status', 1)->where('is_popular', 1)->get();
        $datas = Venue::with('subCategory', 'city')->where('status', 1)->get();
        if ($request) {
            $datas = $this->sidebarSearch($request);
        }

        $categories = MainCategory::where('major_category_id', MajorCategoryConst::VENUE)->get();

        $main = MainCategory::where('major_category_id', 1)->pluck('id')->toArray();
        $subs = SubCategory::whereIn('main_category_id', $main)->get();

        $cities = City::all();
        $states = State::all();

        $justJoin = $datas->take($request->get('limit', 10))->sortByDesc('created_at');

        $venue_category = Venue::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")->orderBy('total', 'desc')
            ->get();
        $dynamic_mains = DynamicMainCategory::where('major_category_id', 1)->where('status', 1)->get();
        $banner = SliderImage::where('major_category_id', 1)->get();
        $major_category = MajorCategory::find(1);


        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();

        $categoryForSidebar = MainCategory::with('subCategory.venue')->withCount('venue')->where('major_category_id', 1)->orderBy('venue_count', 'DESC')->get();
        $allCategory = MajorCategory::with(['mainCategory', 'mainCategory.subCategory'])->get();
        $home_trend = HomeTrendBanner::all();
        // return $subs;
        return view('user.venue.venue-listing', get_defined_vars());
    }

    public function venueViewMore($slug)
    {
        $venues = Venue::with('subCategory', 'featureImage')->get();
        $data = Venue::with('subCategory', 'approvedReviews', 'events', 'featureImage')->where('slug', $slug)->first();
        $type = 'venue';
        $more_info = NULL;
        if (isset($data->id)) {
            $more_info = MoreInfo::where(['module_id' => $data->id, 'module_name' => 'venue'])->get();
        }
        $similar = Venue::with('subCategory', 'city')->where('status', 1)->where('sub_category_id', $data->sub_category_id)->where('id', '!=', $data->id)->get();
        $events = Events::where('date_time', '>', now())->orderBy('date_time', 'ASC')->take(3)->get();
        $venue_category = Venue::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        // Avg Rating
        $reviews = Venue::where('slug', $slug)->first();
        $avg_rating = round($reviews->approvedreviews()->avg('rating'), 1);

        if ($data->lat && $data->long) {
            $nearby = Venue::with('featureImage')->select(
                    "venues.id",
                    DB::raw("6371 * acos(cos(radians(" . $data->lat . "))
                * cos(radians(venues.lat))
                * cos(radians(venues.long) - radians(" . $data->long . "))
                + sin(radians(" . $data->lat . "))
                * sin(radians(venues.lat))) AS distance"),
                    "venues.*",
                    "sub_categories.name as sub_category"
                )
                // ->with('subCategory')
                ->join('sub_categories', 'sub_categories.id', 'venues.sub_category_id')
                ->where('venues.status', 1)
                ->groupBy("venues.id")
                ->orderBy('distance', 'ASC')
                ->get();
        } else {
            $nearby = [];
        }

        $recommended = ItemRecommendation::whereHasMorph('item', [Venue::class])->where('status', 1)->get();

        // return $nearby;

        $count = $data->views + 1;

        $data->views = $count;
        $data->timestamps = false;
        $data->save();


        $alert = AlertNews::first();

        $whatsapp = MajorCategory::where('id', 1)->first();
        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();


        return view('user.venue.venue-view-more', get_defined_vars());
    }

    public function venueSearch(Request $request)
    {
        if ($request->sort) {
            $res = '1';
            $sort_by = $request->sort_by;
            $featured = Venue::with('subCategory', 'featureImage', 'approvedReviews')->where('status', 1)->where('assign_featured', 1);
            if ($sort_by == '1') {
                $featured = $featured->orderBy('created_at', 'desc');
            } elseif ($sort_by == '2') {
                $featured = $featured->orderBy('created_at', 'asc');
            } elseif ($sort_by == '3') {
                $featured = $featured->orderBy('title', 'asc');
            } elseif ($sort_by == '4') {
                $featured = $featured->orderBy('title', 'desc');
            }
            $featured = $featured->get();

            $datas = Venue::with('subCategory', 'featureImage', 'approvedReviews')->where('status', 1);
            if ($sort_by == '1') {
                $datas = $datas->orderBy('created_at', 'desc');
            } elseif ($sort_by == '2') {
                $datas = $datas->orderBy('created_at', 'asc');
            } elseif ($sort_by == '3') {
                $datas = $datas->orderBy('title', 'asc');
            } elseif ($sort_by == '4') {
                $datas = $datas->orderBy('title', 'desc');
            }


            $datas = $datas->get();


            $categories = MainCategory::where('major_category_id', 1)->get();

            $main = MainCategory::where('major_category_id', 1)->pluck('id')->toArray();
            $subs = SubCategory::whereIn('main_category_id', $main)->get();

            $locations = City::all();
            $cities = State::all();

            $venue_category = Venue::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
                ->groupBy("sub_category_id")
                ->get();
            $dynamic_mains = DynamicMainCategory::where('major_category_id', 1)->get();
            $banner = SliderImage::where('major_category_id', 1)->get();
            $major_category = MajorCategory::find(1);
            $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
            $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();
            $categoryForSidebar = MainCategory::with('subCategory.venue')->withCount('venue')->where('major_category_id', 1)->orderBy('venue_count', 'DESC')->take(10)->get();

            return view('user.venue.venue', compact('datas', 'featured', 'banner', 'major_category', 'venue_category', 'categories', 'subs', 'sort_by', 'res', 'locations', 'dynamic_mains', 'cities', 'hot_trends', 'influencer_reviews', 'categoryForSidebar'));
        }

        $main_category = $request->main_category;
        $sub_category = $request->sub_category;
        $location = $request->location;
        $city = $request->city;

        $featured = [];
        // if($main_category) {
        //     $featured = Venue::whereHas('subCategory.mainCategory', function($q) use($main_category) {
        //         $q->where('id', $main_category);
        //     });
        // }
        // if($sub_category) {
        //     $featured = $featured->where('sub_category_id',$sub_category);
        // }if($location) {
        //     $featured = $featured->where('city_id',$location);
        // }
        // $featured = $featured->where('status', 1)->where('assign_featured', 1)->get();

        $datas = Venue::with('subCategory.mainCategory', 'city');

        if ($main_category) {
            $datas = Venue::whereHas('subCategory.mainCategory', function ($q) use ($main_category) {
                $q->where('id', $main_category);
            });
        }
        if ($sub_category) {
            $datas = $datas->where('sub_category_id', $sub_category);
        }
        if ($location) {
            $datas = $datas->where('city_id', $location);
        }
        if ($city) {
            $city_ids = City::where('state_id', $city)->pluck('id')->toArray();
            $datas = $datas->whereIn('city_id', $city_ids);
        }
        if ($request->name) {
            $datas = $datas->where('title', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->quick_search) {
            $datas = $datas->where('title', 'LIKE', '%' . $request->quick_search . '%');
        }
        if ($request->sub_cate_id) {
            $datas = $datas->where('sub_category_id', $request->sub_cate_id);
        }

        $datas = $datas->where('status', 1)->get();

        $main = MainCategory::where('major_category_id', 1)->pluck('id')->toArray();
        $subs = SubCategory::whereIn('main_category_id', $main)->get();

        $locations = City::all();
        $cities = State::all();

        $categories = MainCategory::where('major_category_id', 1)->get();
        $venue_category = Venue::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        $dynamic_mains = DynamicMainCategory::where('major_category_id', 1)->get();
        $banner = SliderImage::where('major_category_id', 1)->get();
        $major_category = MajorCategory::find(1);
        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();
        $categoryForSidebar = MainCategory::with('subCategory.venue')->withCount('venue')->where('major_category_id', 1)->orderBy('venue_count', 'DESC')->take(10)->get();

        return view('user.venue.venue', compact('datas', 'banner', 'major_category', 'featured', 'venue_category', 'categories', 'subs', 'locations', 'dynamic_mains', 'cities', 'hot_trends', 'influencer_reviews', 'categoryForSidebar'));
    }

    public function venue_detail($slug)
    {
        $venue = Venue::with(['subCategory', 'featureImage', 'storyImages', 'mainImages', 'logoImage', 'menuImage', 'mainImage', 'approvedReviews', 'upcomingEvents'])->where('slug', '=', $slug)->active()->first();
        $data = $venue;
        $type = 'venue';
        $more_info = NULL;
        if (isset($data->id)) {
            $more_info = MoreInfo::where(['module_id' => $data->id, 'module_name' => 'venue'])->get();
        }
        $similar = Venue::with('get_subcat', 'featureImage', 'approvedReviews')->active()->where('sub_category_id', $venue->sub_category_id)->where('id', '!=', $venue->id)->get();
        $popular = Venue::with('get_subcat', 'featureImage', 'approvedReviews')->active()->popular()->get();
        $venue_category = Venue::where('status', '=', '1')
            ->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $venue->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        // avg rating
        // $reviews = Venue::where('slug', $slug)->first();
        $avg_rating = round($venue->approvedreviews()->avg('rating'), 1);

        // $four_images = json_decode($venue->images);
        // $amenties_venue = json_decode($venue->amenity_id);

        // // return $amenties_event;
        // $landmark_venue = json_decode($venue->landmark_id);
        $amenties_venue = $venue->amenity_id;
        $landmark_venue = $venue->landmark_id;

        if ($venue->lat && $venue->long) {
            $nearby = Venue::with('featureImage', 'approvedReviews')->select(
                    "venues.id",
                    DB::raw("6371 * acos(cos(radians(" . $venue->lat . "))
                * cos(radians(venues.lat))
                * cos(radians(venues.long) - radians(" . $venue->long . "))
                + sin(radians(" . $venue->lat . "))
                * sin(radians(venues.lat))) AS distance"),
                    "venues.*",
                    "sub_categories.name as sub_category"
                )
                // ->with('subCategory')
                ->join('sub_categories', 'sub_categories.id', 'venues.sub_category_id')
                ->where('venues.status', 1)
                ->groupBy("venues.id")
                ->orderBy('distance', 'ASC')
                ->get();
        } else {
            $nearby = [];
        }
        $shareIcons = createSocialShareIcons($venue, 'venue-detail');

        //$recommended = ItemRecommendation::whereHasMorph('item', [Venue::class])->where('item_id', $venue->id)->where('status', 1)->get();

        $recommended = Recommendation::where(['module_type' => 'venue', 'module_id' => $venue->id])->count();
        $count = $venue->views + 1;
        $venue->views = $count;
        $venue->timestamps = false;
        $venue->save();

        $alert = AlertNews::first();

        $firstStoryImage = $venue->storyImages()->first();
        $whatsapp = MajorCategory::where('id', 1)->first();
        $breadcrumbUrl = route('venue-list', ['category_slug' => $venue->subCategory->mainCategory->slug]);
        if($venue->mainImage) {
            $og_image = $venue-> getStoredImage($venue->mainImage->image, 'main_image');
        } else if($venue->featureImage) {
            $og_image = $venue-> getStoredImage($venue->featureImage->image, 'feature_image');
        } else {
            $og_image = '/v2/images/image-placeholder.jpeg';
        }
        return view('user.venue.venue-detail', get_defined_vars());
        /*return view('user.event.event-detail',compact('event','four_images','amenties_event','landmark_event','similar','event_category',
        'nearby', 'avg_rating','youtube','alert', 'whatsapp', 'recommended'));*/
    }

    function search_venue_name_ajax(Request $request)
    {
        $search = $request->keyword;
        $venues = array();
        if (!empty($search)) {
            $venues = Venue::select('title', 'id')->where('title', 'LIKE', '%' . $search . '%')->orderby('title')->limit(6)->get();
        }
        $view = view('user.venue.autocomplete_venue_ajax', compact('venues'))->render();
        return response()->json(['html' => $view]);
    }

    public function venueAjaxReservation(Request $request)
    {

        $date = date_create($request->date);
        $date = date_format($date, "Y-m-d H:i");


        $enquire = new EnquireForm(['name' => $request->name, 'email' => $request->email, 'mobile' => $request->mobile, 'message' => $request->message1]);
        $type = Venue::find($request->item_id);
        $email = $type->email;
        $type->enquiries()->save($enquire);
        $obj = new VenueReservation();
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
        $obj->venue_id = $request->venue_id;
        $obj->booking_type = $request->booking_type;
        $obj->save();

        $subject = "Venue Enquiry Mail";

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
