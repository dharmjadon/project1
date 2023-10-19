<?php

namespace App\Http\Controllers\User;

use App\Models\City;
use App\Models\DynamicLink;
use App\Models\News;
use App\Models\Recommendation;
use App\Models\State;
use App\Models\Attraction;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Models\MoreInfo;
use App\Models\AlertNews;
use App\Models\EnquireForm;
use App\Models\SliderImage;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\MajorCategory;
use App\Models\HomeTrendBanner;
use App\Models\InfluencerReview;
use App\Mail\ConciergeEnquiryMail;
use App\Models\ItemRecommendation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class AttractionFrontController extends Controller
{
    public function attraction(Request $request, $category_slug = '')
    {

        $result_array = $this->search_attraction($request, $category_slug);

        $all_attractions = $result_array['all_attractions'];
        $total_count = $result_array['total_count'];
        $attraction_category = $result_array['attraction_category'];
        $feature_attractions = $result_array['feature_attractions'];
        $popular_attractions = $result_array['popular_attractions'];
        $nextPageUrl = $result_array['nextPageUrl'];

        $sub_cat_search = array();
        if (isset($_GET['sub_category'])) {
            if ($_GET['sub_category'] != "all") {
                $sub_cat_search = SubCategory::where('main_category_id', '=', $_GET['main_cat'])->get();
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
        $date_from = $request->date_from ?? '';
        $date_to = $request->date_to ?? '';
        $sort_by = $request->sort_by ?? '';

        if ($request->ajax()) {
            return $view = view('user.attractions.list', get_defined_vars())->render();
        }

        $all_main_category = MainCategory::where('major_category_id', '=', '10')->pluck('id')->toArray();
        $states = Attraction::where('area', '!=', '')->pluck('area', 'area')->toArray();
        $cities = Attraction::where('city', '!=', '')->pluck('city', 'city')->toArray();
        $justJoin = $all_attractions->take($request->get('limit', 10))->sortByDesc('created_at');

        $main_category = MainCategory::where('major_category_id', '=', '10')->get();
        $banner = SliderImage::where('major_category_id', 10)->get();
        $major_category = MajorCategory::find(10);
        $major_category->load(['searchLinksTop', 'searchLinksBottom', 'statistics', 'bannerLinksTop', 'bannerLinksRight', 'bannerLinksBottom', 'bannerLinksLeft']);
        $categories = MainCategory::where('major_category_id', 10)->withCount('attraction')->get();
        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();
        $categoryForSidebar = MainCategory::with('subCategory.attraction')->withCount('attraction')->where('major_category_id', 10)->orderBy('attraction_count', 'DESC')->get();
        $categoryForThingsToDo = MainCategory::with('subCategory.attractionThings')->withCount('attractionthings')->where('major_category_id', 10)->orderBy('attractionthings_count', 'DESC')->get();
        $categoryForPopularPlace = MainCategory::with('subCategory.attractionPopular')->withCount('attractionpopular')->where('major_category_id', 10)->orderBy('attractionpopular_count', 'DESC')->get();
        $allCategory = MajorCategory::with(['mainCategory', 'mainCategory.subCategory']);

        if(!empty($category_slug) || !empty($request->main_cat)) {
            if($sub_category_id) {
                $cat_details = SubCategory::find($sub_category_id);
            } else if(!empty($main_cat) && $main_cat != 'all') {
                $cat_details = MainCategory::find($main_cat);
            } else {
                $cat_details = null;
            }

            return view('user.attractions.attraction-listing', get_defined_vars());
        }
        $dynamic_links = DynamicLink::where('major_category_id', 10)->whereNotIn('slug', ['popular-attractions', 'trending-attractions', 'hot-attractions'])->get();
        $popular_links = $major_category->popularDynamicLinks()->first();
        $trending_links = $major_category->trendingDynamicLinks()->first();
        $hot_links = $major_category->hotDynamicLinks()->first();
        $top_searches = MainCategory::with('topSearch')
            ->withCount('topSearch')
            ->where('major_category_id', 10)
            ->orderBy('top_search_count', 'DESC')
            ->get();
        $popular_searches = MainCategory::with('popularSearch')
            ->withCount('popularSearch')
            ->where('major_category_id', 10)
            ->orderBy('popular_search_count', 'DESC')
            ->get();
        return view('user.attractions.index', get_defined_vars());
    }

    public function search_attraction($request, $category_slug)
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
        $totalItems = Attraction::select(['id'])->active()->count();
        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/attractions?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
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
        $all_attractions = Attraction::with(['get_subcat', 'featureImage', 'approvedReviews'])->where('status', '=', '1');

        $attraction_category = Attraction::with(['get_subcat'])->where('status', '=', '1')
            ->select('title', 'sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        $feature_attractions = Attraction::with(['get_subcat', 'featureImage'])->where('status', '=', '1')->where('is_featured', '=', '1');

        $popular_attractions = Attraction::with(['get_subcat', 'featureImage'])->where('status', 1)->where('is_popular', 1);

        if (isset($request->sort_by)) {
            if ($request->sort_by == "1") {  // newest to oldest
                $all_attractions = $all_attractions->orderBy('created_at', 'desc');
                $feature_attractions = $feature_attractions ? $feature_attractions->orderBy('created_at', 'desc') : null;
                $popular_attractions = $popular_attractions ? $popular_attractions->orderBy('created_at', 'desc') : null;
            } elseif ($request->sort_by == "2") { //oldest to newest

                $all_attractions = $all_attractions->orderBy('created_at', 'asc');
                $feature_attractions = $feature_attractions ? $feature_attractions->orderBy('created_at', 'asc') : null;
                $popular_attractions = $popular_attractions ? $popular_attractions->orderBy('created_at', 'asc') : null;
            } elseif ($request->sort_by == "3") { // A to Z
                // $all_attractions =   $all_attractions->orderBy('title', 'desc');
                // $feature_attractions =  $feature_attractions->orderBy('title', 'desc');

                $all_attractions = $all_attractions->orderBy('title', 'asc');
                $feature_attractions = $feature_attractionattractions ? $feature_attractions->orderBy('title', 'asc') : null;
                $popular_attractions = $popular_attractions ? $popular_attractions->orderBy('title', 'asc') : null;
            } elseif ($request->sort_by == "4") {  // Z to A


                $all_attractions = $all_attractions->orderBy('title', 'desc');
                $feature_attractions = $feature_attractions ? $feature_attractions->orderBy('title', 'desc') : null;
                $popular_attractions = $popular_attractions ? $popular_attractions->orderBy('title', 'desc') : null;
            }
        } else {
            $all_attractions = $all_attractions->orderBy('created_at', 'desc');
            $feature_attractions = $feature_attractions ? $feature_attractions->orderBy('created_at', 'desc') : null;
            $popular_attractions = $popular_attractions ? $popular_attractions->orderBy('created_at', 'desc') : null;
        }

        if (isset($request)) {

            if (isset($request->quick_search)) {   //quick search

                $search = $request->quick_search;

                $main_cat_ids = MainCategory::where('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();

                $sub_cat_ids = SubCategory::where('name', 'LIKE', '%' . $search . '%')
                    ->orWhereIn('main_category_id', $main_cat_ids)
                    ->pluck('id')
                    ->toArray();

                $all_attractions = Attraction::with('get_subcat', 'featureImage')
                    ->where(function ($query) use ($search, $sub_cat_ids) {
                        $query->orWhere('title', 'LIKE', '%' . $search . '%')
                            ->orWhere('description', 'LIKE', '%' . $search . '%')
                            ->orWhereIn('sub_category_id', $sub_cat_ids);
                    })
                    ->where('status', '=', '1');
            }


            if (isset($request->attraction_name)) {  //attraction name

                $attribute = $request->attraction_name;
                $all_attractions = $all_attractions->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });

                $attraction_category = $attraction_category->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });
            }

            if ($request->main_cat != "all" && isset($request->main_cat)) { //main cat

                $sub_cat_ids = SubCategory::where('main_category_id', '=', $request->main_cat)->pluck('id')->toArray();

                $all_attractions = $all_attractions->whereIn('sub_category_id', $sub_cat_ids);

                $attraction_category = $attraction_category->whereIn('sub_category_id', $sub_cat_ids);
            }

            if ($request->sub_category != "all" && isset($request->sub_category)) {  // sub cat

                $all_attractions = $all_attractions->where('sub_category_id', $request->sub_category);

                $attraction_category = $attraction_category->whereIn('sub_category_id', $request->sub_category);
            }

            if ($request->location != "all" && isset($request->location)) { //location means city id

                $all_attractions = $all_attractions->where('city', $request->location);
                $attraction_category = $attraction_category->where('city', $request->location);
                //$feature_attractions = $popular_attractions = [];
            }

            if (isset($request->sub_category_id)) {
                $all_attractions = $all_attractions->where('sub_category_id', $request->sub_category_id);
            }

            if (isset($request->sub_cate_id)) {  //attraction name

                $attribute = $request->sub_cate_id;
                $all_attractions = $all_attractions->filter(function ($item) use ($attribute) {
                    return strpos($item->sub_category_id, $attribute) !== false;
                });

                //$feature_attractions = $popular_attractions = [];
            }
            if (isset($request->date_from)) {
                $all_attractions = $all_attractions->where('date_time', '>', $request->date_from . " 00:00:00");
            }

            if (isset($request->date_to)) {
                $all_attractions = $all_attractions->where('date_time', '<=', $request->date_to . " 23:59:59");
            }
        }

        $feature_attractions = $feature_attractions->get();
        $popular_attractions = $popular_attractions->get();
        $total_count = 0;
        if (isset($all_attractions)) {
            $total_count = $all_attractions->count();
        }

        $result_array = array(
            'all_attractions' => $all_attractions->paginate($perPage),
            'total_count' => $total_count,
            'attraction_category' => $attraction_category,
            'feature_attractions' => $feature_attractions,
            'popular_attractions' => $popular_attractions,
            'nextPageUrl' => $nextPageUrl,
        );

        return $result_array;
    }

    public function attraction_detail($slug)
    {
        $attraction = Attraction::where('slug', '=', $slug)->first();
        $attraction->load(['subCategory', 'approvedReviews', 'featureImage', 'logoImage',  'mainImage', 'mainImages', 'storyImages', 'upcomingEvents']);
        $data = $attraction;
        $type = 'attraction';
        $more_info = NULL;
        if (isset($data->id)) {
            $more_info = MoreInfo::where(['module_id' => $data->id, 'module_name' => 'attraction'])->get();
        }
        $similar = Attraction::with('get_subcat', 'featureImage', 'approvedReviews')->where('status', 1)->where('sub_category_id', $attraction->sub_category_id)->where('id', '!=', $attraction->id)->get();
        $popular = Attraction::with('get_subcat', 'featureImage', 'approvedReviews')->where('status', 1)->where('is_popular', 1)->get();
        $attraction_category = Attraction::where('status', '=', '1')
            ->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $attraction->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        // avg rating
        //$reviews = attractions::where('slug', $slug)->first();
        $avg_rating = round($attraction->approvedReviews->avg('rating'), 1);

        $amenties_attraction = $attraction->amenties;
        $landmark_attraction = $attraction->landmarks;
        if ($attraction->lat && $attraction->lng) {
            $nearby = Attraction::with(['featureImage', 'approvedReviews'])->select(
                    "attractions.id",
                    DB::raw("6371 * acos(cos(radians(" . $attraction->lat . "))
                * cos(radians(attractions.lat))
                * cos(radians(attractions.lng) - radians(" . $attraction->lng . "))
                + sin(radians(" . $attraction->lat . "))
                * sin(radians(attractions.lat))) AS distance"),
                    "attractions.*",
                    "sub_categories.name as sub_category"
                )
                // ->with('subCategory')
                ->join('sub_categories', 'sub_categories.id', 'attractions.sub_category_id')
                ->where('attractions.status', 1)
                ->groupBy("attractions.id")
                ->orderBy('distance', 'ASC')
                ->get();
        } else {
            $nearby = [];
        }
        $shareIcons = createSocialShareIcons($attraction, 'attraction-detail');
        //$recommended = ItemRecommendation::whereHasMorph('item', [attractions::class])->where('status', 1)->get();
        $recommended = Recommendation::where(['module_type' => 'attractions', 'module_id' => $attraction->id])->count();
        $count = $attraction->views + 1;
        $attraction->views = $count;
        $attraction->timestamps = false;
        $attraction->save();

        $alert = AlertNews::first();

        $firstStoryImage = $attraction->storyImages()->first();
        $whatsapp = MajorCategory::where('id', 10)->first();
        $breadcrumbUrl = route('attraction-list', ['category_slug' => $attraction->subCategory->mainCategory->slug]);
        if($attraction->mainImage) {
            $og_image = $attraction-> getStoredImage($attraction->mainImage->image, 'main_image');
        } else if($attraction->featureImage) {
            $og_image = $attraction-> getStoredImage($attraction->featureImage->image, 'feature_image');
        } else {
            $og_image = '/v2/images/image-placeholder.jpeg';
        }

        return view('user.attractions.attraction-detail', get_defined_vars());
        /*return view('user.attraction.attraction-detail',compact('attraction','four_images','amenties_attraction','landmark_attraction','similar','attraction_category',
            'nearby', 'avg_rating','youtube','alert', 'whatsapp', 'recommended'));*/
    }

    function search_attraction_name_ajax(Request $request)
    {
        $search = $request->keyword;
        $attractions = array();
        if (!empty($search)) {
            $attractions = Attraction::select('title', 'id')->where('title', 'LIKE', '%' . $search . '%')->orderby('title')->limit(6)->get();
        }

        $view = view('user.attractions.ajax.autocomplete_attraction_ajax', compact('attractions'))->render();
        return response()->json(['html' => $view]);
    }
}
