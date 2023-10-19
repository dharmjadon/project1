<?php

namespace App\Http\Controllers\User;

use App\Models\City;
use App\Models\DynamicLink;
use App\Models\News;
use App\Models\Recommendation;
use App\Models\State;
use App\Models\Venue;
use App\Models\Influencer;
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

class InfluencersViewController extends Controller
{
    public function influencer(Request $request, $category_slug = '')
    {
        $all_main_category = MainCategory::where('major_category_id', '=', '6')->pluck('id')->toArray();

        $states = State::all();
        $cities = Influencer::where('city', '!=', '')->pluck('city', 'city')->toArray();

        $result_array = $this->search_influencer($request, $category_slug);

        $all_influencers = $result_array['all_influencers'];
        $total_count = $result_array['total_count'];
        $influencer_category = $result_array['influencer_category'];
        $feature_influencers = $result_array['feature_influencers'];
        $popular_influencers = $result_array['popular_influencers'];
        $nextPageUrl = $result_array['nextPageUrl'];

        $justJoin = $all_influencers->take($request->get('limit', 10))->sortByDesc('created_at');

        $sub_cat_search = array();

        if (isset($_GET['sub_category'])) {
            if ($_GET['sub_category'] != "all") {
                $sub_cat_search = SubCategory::where('main_category_id', '=', $_GET['main_cat'])->get();
            }
        }

        $main_category = MainCategory::where('major_category_id', '=', '6')->get();

        $banner = SliderImage::where('major_category_id', 6)->get();
        $major_category = MajorCategory::find(6);
        $major_category->load(['searchLinksTop', 'searchLinksBottom', 'statistics', 'bannerLinksTop', 'bannerLinksRight', 'bannerLinksBottom', 'bannerLinksLeft']);
        $categories = MainCategory::where('major_category_id', 6)->withCount('influencer')->get();
        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();

        $categoryForSidebar = MainCategory::with('subCategory.influencer')->withCount('influencer')->where('major_category_id', 6)->orderBy('influencer_count', 'DESC')->get();
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
            return $view = view('user.influencers.list', get_defined_vars())->render();
        }

        if(!empty($category_slug) || !empty($request->main_cat)) {
            if($sub_category_id) {
                $cat_details = SubCategory::find($sub_category_id);
            } else if(!empty($main_cat) && $main_cat != 'all') {
                $cat_details = MainCategory::find($main_cat);
            } else {
                $cat_details = null;
            }

            return view('user.influencers.influencer-listing', get_defined_vars());
        }
        $dynamic_links = DynamicLink::where('major_category_id', 6)->whereNotIn('slug', ['popular-influencers', 'trending-influencers', 'hot-influencers'])->get();
        $popular_links = $major_category->popularDynamicLinks()->first();
        $trending_links = $major_category->trendingDynamicLinks()->first();
        $hot_links = $major_category->hotDynamicLinks()->first();
        $top_searches = MainCategory::with('topSearch')
            ->withCount('topSearch')
            ->where('major_category_id', 6)
            ->orderBy('top_search_count', 'DESC')
            ->get();
        $popular_searches = MainCategory::with('popularSearch')
            ->withCount('popularSearch')
            ->where('major_category_id', 6)
            ->orderBy('popular_search_count', 'DESC')
            ->get();
        return view('user.influencers.index', get_defined_vars());
    }

    public function search_influencer($request, $category_slug)
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
        $totalItems = Influencer::select(['id'])->active()->count();
        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/influencers?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
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

        $all_influencers = Influencer::with(['get_subcat', 'featureImage', 'approvedReviews'])->where('status', '=', '1');

        $influencer_category = Influencer::with(['get_subcat'])->where('status', '=', '1')
            ->select('name', 'sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        $feature_influencers = Influencer::with(['get_subcat', 'featureImage'])->where('status', '=', '1')->where('featured', '=', '1');

        $popular_influencers = Influencer::with(['get_subcat', 'featureImage'])->where('status', 1)->where('is_popular', 1);


        if (isset($request)) {
            if (isset($request->sort_by)) {
                if ($request->sort_by == "1") {  // newest to oldest
                    $all_influencers = $all_influencers->orderBy('created_at', 'desc');
                    $feature_influencers = $feature_influencers ? $feature_influencers->orderBy('created_at', 'desc') : null;
                    $popular_influencers = $popular_influencers ? $popular_influencers->orderBy('created_at', 'desc') : null;
                } elseif ($request->sort_by == "2") { //oldest to newest

                    $all_influencers = $all_influencers->orderBy('created_at', 'asc');
                    $feature_influencers = $feature_influencers ? $feature_influencers->orderBy('created_at', 'asc') : null;
                    $popular_influencers = $popular_influencers ? $popular_influencers->orderBy('created_at', 'asc') : null;
                } elseif ($request->sort_by == "3") { // A to Z
                    // $all_influencers =   $all_influencers->orderBy('title', 'desc');
                    // $feature_influencers =  $feature_influencers->orderBy('title', 'desc');

                    $all_influencers = $all_influencers->orderBy('name', 'asc');
                    $feature_influencers = $feature_influencers ? $feature_influencers->orderBy('name', 'asc') : null;
                    $popular_influencers = $popular_influencers ? $popular_influencers->orderBy('name', 'asc') : null;
                } elseif ($request->sort_by == "4") {  // Z to A


                    $all_influencers = $all_influencers->orderBy('name', 'desc');
                    $feature_influencers = $feature_influencers ? $feature_influencers->orderBy('name', 'desc') : null;
                    $popular_influencers = $popular_influencers ? $popular_influencers->orderBy('name', 'desc') : null;
                }
            } else {
                $all_influencers = $all_influencers->orderBy('created_at', 'desc');
                $feature_influencers = $feature_influencers ? $feature_influencers->orderBy('created_at', 'desc') : null;
                $popular_influencers = $popular_influencers ? $popular_influencers->orderBy('created_at', 'desc') : null;
            }

            if (isset($request->quick_search)) {   //quick search

                $search = $request->quick_search;

                $main_cat_ids = MainCategory::where('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();

                $sub_cat_ids = SubCategory::where('name', 'LIKE', '%' . $search . '%')
                    ->orWhereIn('main_category_id', $main_cat_ids)
                    ->pluck('id')
                    ->toArray();

                $all_influencers = Influencer::with('get_subcat', 'featureImage')
                    ->where(function ($query) use ($search, $sub_cat_ids) {
                        $query->orWhere('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('description', 'LIKE', '%' . $search . '%')
                            ->orWhereIn('sub_category_id', $sub_cat_ids);
                    })
                    ->where('status', '=', '1');
            }


            if (isset($request->influencer_name)) {  //influencer name

                $attribute = $request->influencer_name;
                $all_influencers = $all_influencers->filter(function ($item) use ($attribute) {
                    return strpos($item->name, $attribute) !== false;
                });

                $influencer_category = $influencer_category->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });
            }

            if ($request->main_cat != "all" && isset($request->main_cat)) { //main cat

                $sub_cat_ids = SubCategory::where('main_category_id', '=', $request->main_cat)->pluck('id')->toArray();

                $all_influencers = $all_influencers->whereIn('sub_category_id', $sub_cat_ids);

                $influencer_category = $influencer_category->whereIn('sub_category_id', $sub_cat_ids);
            }

            if ($request->sub_category != "all" && isset($request->sub_category)) {  // sub cat

                $all_influencers = $all_influencers->where('sub_category_id', $request->sub_category);

                $influencer_category = $influencer_category->whereIn('sub_category_id', $request->sub_category);
            }

            if ($request->location != "all" && isset($request->location)) { //location means city id

                $all_influencers = $all_influencers->where('city', $request->location);
                $influencer_category = $influencer_category->where('city', $request->location);
                //$feature_influencers = $popular_influencers = [];
            }

            if (isset($request->sub_category_id)) {
                $all_influencers = $all_influencers->where('sub_category_id', $request->sub_category_id);
            }

            if (isset($request->sub_cate_id)) {  //influencer name

                $attribute = $request->sub_cate_id;
                $all_influencers = $all_influencers->filter(function ($item) use ($attribute) {
                    return strpos($item->sub_category_id, $attribute) !== false;
                });

                //$feature_influencers = $popular_influencers = [];
            }
            if (isset($request->date_from)) {
                $all_influencers = $all_influencers->where('created_at', '>', $request->date_from . " 00:00:00");
            }

            if (isset($request->date_to)) {
                $all_influencers = $all_influencers->where('created_at', '<=', $request->date_to . " 23:59:59");
            }
        }

        $feature_influencers = $feature_influencers->get();
        $popular_influencers = $popular_influencers->get();
        $total_count = 0;
        if (isset($all_influencers)) {
            $total_count = $all_influencers->count();
        }

        $result_array = array(
            'all_influencers' => $all_influencers->paginate($perPage),
            'total_count' => $total_count,
            'influencer_category' => $influencer_category,
            'feature_influencers' => $feature_influencers,
            'popular_influencers' => $popular_influencers,
            'nextPageUrl' => $nextPageUrl,
        );

        return $result_array;
    }

    public function influencer_detail($slug)
    {
        $influencer = Influencer::where('slug', '=', $slug)->first();
        $influencer->load(['approvedReviews', 'featureImage', 'mainImages', 'storyImages']);
        $data = $influencer;
        $type = 'influencer';
        $categories = MainCategory::where('major_category_id', 6)->withCount('influencer')->get();
        $similar = Influencer::with('get_subcat', 'featureImage', 'approvedReviews')->where('status', 1)->where('sub_category_id', $influencer->sub_category_id)->where('id', '!=', $influencer->id)->get();

        $popular = Influencer::with('get_subcat', 'featureImage', 'approvedReviews')->where('status', 1)->where('is_popular', 1)->get();

        $event_category = Influencer::where('status', '=', '1')
            ->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();

        $avg_rating = round($influencer->approvedReviews->avg('rating'), 1);

        if ($influencer->lat && $influencer->lng) {
            $nearby = Influencer::with(['featureImage', 'approvedReviews'])->select(
                "influencers.id",
                DB::raw("6371 * acos(cos(radians(" . $influencer->lat . "))
                * cos(radians(influencers.lat))
                * cos(radians(influencers.lng) - radians(" . $influencer->lng . "))
                + sin(radians(" . $influencer->lat . "))
                * sin(radians(influencers.lat))) AS distance"),
                "influencers.*",
                "sub_categories.name as sub_category"
            )
                // ->with('subCategory')
                ->join('sub_categories', 'sub_categories.id', 'influencers.sub_category_id')
                ->where('influencers.status', 1)
                ->groupBy("influencers.id")
                ->orderBy('distance', 'ASC')
                ->get();
        } else {
            $nearby = [];
        }

        $shareIcons = createSocialShareIcons($influencer, 'influencer');
        $recommended = Recommendation::where(['module_type' => 'events', 'module_id' => $influencer->id])->count();
        $count = $influencer->views + 1;
        $influencer->views = $count;
        $influencer->timestamps = false;
        $influencer->save();

        $alert = AlertNews::first();

        $firstStoryImage = $influencer->storyImages()->first();
        $whatsapp = MajorCategory::where('id', 6)->first();
        $breadcrumbUrl = route('influencers-list', ['category_slug' => $influencer->subCategory->mainCategory->slug]);
        if($influencer->mainImage) {
            $og_image = $influencer-> getStoredImage($influencer->mainImage->image, 'main_image');
        } else if($influencer->featureImage) {
            $og_image = $influencer-> getStoredImage($influencer->featureImage->image, 'feature_image');
        } else {
            $og_image = '/v2/images/image-placeholder.jpeg';
        }

        return view('user.influencers.influencer-detail', get_defined_vars());
    }

    public function influencersByType(Request $request, $type)
    {
        $main_category = MainCategory::where('major_category_id', 6)->pluck('id')->toArray();
        $cat_details = DynamicLink::where('major_category_id', 6)->where('slug', $type)->first();
        $dynamic_links = DynamicLink::where('major_category_id', 6)->pluck('link_title', 'slug')->toArray();
        $quick_search = $request->quick_search ?? '';
        $type = $request->type ?? $type;
        $sort_by = $request->sort_by ?? 1;
        $pageNo = $request->get('page', 1);
        $perPage = 10;
        if($cat_details->related_items) {
            $totalItems = Influencer::select(['id'])->active()->whereIn('id', $cat_details->related_items);
        } else {
            $totalItems = Influencer::select(['id'])->active();
        }
        $totalItems = $totalItems->count();
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $totalItems = $totalItems->{$scope}()->count();
        } else {
            $totalItems = $totalItems->count();
        }*/

        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/influencers/view-listings/".$type."?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
            $nextPageUrl .= "&sort_by=".$sort_by;
        }
        else {
            $nextPageUrl = '';
        }

        $all_influencers = Influencer::with('get_subcat', 'featureImage', 'approvedReviews')->active();
        if($cat_details->related_items) {
            $all_influencers = $all_influencers->whereIn('id', $cat_details->related_items);
        }
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $all_influencers = $all_influencers->{$scope}();
        }*/
        if (isset($request)) {
            if (!empty($quick_search)) {
                $search = $quick_search;
                $all_influencers = $all_influencers->where(function ($query) use ($search) {
                    $query->orWhere('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%');
                });
            }
            if (!empty($sort_by)) {
                if ($sort_by == "1") {
                    $all_influencers = $all_influencers->orderBy('created_at', 'desc');
                } elseif ($sort_by == "2") { //oldest to newest
                    $all_influencers = $all_influencers->orderBy('created_at', 'asc');
                } elseif ($sort_by == "3") { // A to Z
                    $all_influencers = $all_influencers->orderBy('title', 'asc');
                } elseif ($sort_by == "4") { // Z to A
                    $all_influencers = $all_influencers->orderBy('title', 'desc');
                }
            } else {
                $all_influencers = $all_influencers->orderBy('created_at', 'desc');
            }
        }

        $total_count = 0;
        if (isset($all_influencers)) {
            $total_count = $all_influencers->count();
        }
        $all_influencers = $all_influencers->paginate(10);
        if ($request->ajax()) {
            return $view = view('user.influencers.list', get_defined_vars())->render();
        }

        return view('user.common.listings-by-type', get_defined_vars());
    }

}
