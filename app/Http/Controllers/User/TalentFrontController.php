<?php

namespace App\Http\Controllers\User;

use App\Models\City;
use App\Models\DynamicLink;
use App\Models\News;
use App\Models\Recommendation;
use App\Models\State;
use App\Models\Talents;
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
use App\Models\DynamicSubCategory;
use App\Models\ItemRecommendation;
use Illuminate\Support\Facades\DB;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class TalentFrontController extends Controller
{
    public function talent (Request $request, $category_slug = '')
    {
        $all_main_category = MainCategory::where('major_category_id', '=', '17')->pluck('id')->toArray();
        $dynamic_sub_categories = DynamicSubCategory::all();
        $dynamic_categories = DynamicMainCategory::where('major_category_id', '=', '17')->get();
     
        $states = State::all();
        $cities = Talents::where('city', '!=', '')->pluck('city', 'city')->toArray();
        $result_array = $this->search_talent($request, $category_slug);

        $all_talents = $result_array['all_talents'];
        $total_count = $result_array['total_count'];
        $talent_category = $result_array['talent_category'];
        $feature_talents = $result_array['feature_talents'];
        $popular_talents = $result_array['popular_talents'];
        $nextPageUrl = $result_array['nextPageUrl'];

        $justJoin = $all_talents->take($request->get('limit', 10))->sortByDesc('created_at');
        $sub_cat_search = array();
        if (isset($_GET['sub_category'])) {
            if ($_GET['sub_category'] != "all") {
                $sub_cat_search = SubCategory::where('main_category_id', '=', $_GET['main_cat'])->get();
            }
        }

        $main_category = MainCategory::where('major_category_id', '=', '17')->get();
        $dynamic_mains = DynamicMainCategory::where('major_category_id', '=', '17')->where('status', 1)->get();
        $banner = SliderImage::where('major_category_id', 17)->get();
        $major_category = MajorCategory::find(17);

        $major_category->load(['searchLinksTop', 'searchLinksBottom', 'statistics', 'bannerLinksTop', 'bannerLinksRight', 'bannerLinksBottom', 'bannerLinksLeft']);
        // dd($major_category);
        $categories = MainCategory::where('major_category_id', 17)->withCount('talent')->get();
        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();

        $categoryForSidebar = MainCategory::with('subCategory.talent')->withCount('talent')->where('major_category_id', 17)->orderBy('talent_count', 'DESC')->get();
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
            return $view = view('user.talent.list', get_defined_vars())->render();
        }

        if(!empty($category_slug) || !empty($request->main_cat)) {
            if($sub_category_id) {
                $cat_details = SubCategory::find($sub_category_id);
            } else if(!empty($main_cat) && $main_cat != 'all') {
                $cat_details = MainCategory::find($main_cat);
            } else {
                $cat_details = null;
            }

            return view('user.talent.talent-listing', get_defined_vars());
        }

        $dynamic_links = DynamicLink::where('major_category_id', 17)->whereNotIn('slug', ['popular-talents', 'trending-talents', 'hot-talents'])->get();
        $popular_links = $major_category->popularDynamicLinks()->first();
        $trending_links = $major_category->trendingDynamicLinks()->first();
        $hot_links = $major_category->hotDynamicLinks()->first();
        $top_searches = MainCategory::with('topSearch')
            ->withCount('topSearch')
            ->where('major_category_id', 17)
            ->orderBy('top_search_count', 'DESC')
            ->get();
        $popular_searches = MainCategory::with('popularSearch')
            ->withCount('popularSearch')
            ->where('major_category_id', 17)
            ->orderBy('popular_search_count', 'DESC')
            ->get();
        return view('user.talent.index', get_defined_vars());
    }

    public function search_talent($request, $category_slug)
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

        $totalItems = Talents::select(['id'])->active()->count();
        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/talents?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
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
        $all_talents = Talents::with(['get_subcat', 'featureImage', 'approvedReviews'])->where('status', '=', '1');

        $talent_category = Talents::with(['get_subcat'])->where('status', '=', '1')
            ->select('title', 'sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        $feature_talents = Talents::with(['get_subcat', 'featureImage'])->where('status', '=', '1')->where('is_featured', '=', '1');

        $popular_talents = Talents::with(['get_subcat', 'featureImage'])->where('status', 1)->where('is_popular', 1);


        if (isset($request)) {
            if (isset($request->sort_by)) {
                if ($request->sort_by == "1") { 

                    $all_talents = $all_talents->orderBy('created_at', 'desc');
                    $feature_talents = $feature_talents ? $feature_talents->orderBy('created_at', 'desc') : null;
                    $popular_talents = $popular_talents ? $popular_talents->orderBy('created_at', 'desc') : null;
                } elseif ($request->sort_by == "2") { 

                    $all_talents = $all_talents->orderBy('created_at', 'asc');
                    $feature_talents = $feature_talents ? $feature_talents->orderBy('created_at', 'asc') : null;
                    $popular_talents = $popular_talents ? $popular_talents->orderBy('created_at', 'asc') : null;
                } elseif ($request->sort_by == "3") {

                    $all_talents = $all_talents->orderBy('title', 'asc');
                    $feature_talents = $feature_talents ? $feature_talents->orderBy('title', 'asc') : null;
                    $popular_talents = $popular_talents ? $popular_talents->orderBy('title', 'asc') : null;
                } elseif ($request->sort_by == "4") { 

                    $all_talents = $all_talents->orderBy('title', 'desc');
                    $feature_talents = $feature_talents ? $feature_talents->orderBy('title', 'desc') : null;
                    $popular_talents = $popular_talents ? $popular_talents->orderBy('title', 'desc') : null;
                }
            } else {

                $all_talents = $all_talents->orderBy('created_at', 'desc');
                $feature_talents = $feature_talents ? $feature_talents->orderBy('created_at', 'desc') : null;
                $popular_talents = $popular_talents ? $popular_talents->orderBy('created_at', 'desc') : null;
            }

            if (isset($request->quick_search)) {  
                $search = $request->quick_search;

                $main_cat_ids = MainCategory::where('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();

                $sub_cat_ids = SubCategory::where('name', 'LIKE', '%' . $search . '%')
                    ->orWhereIn('main_category_id', $main_cat_ids)
                    ->pluck('id')
                    ->toArray();

                $all_talents = Talents::with('get_subcat', 'featureImage')
                    ->where(function ($query) use ($search, $sub_cat_ids) {
                        $query->orWhere('title', 'LIKE', '%' . $search . '%')
                            ->orWhere('description', 'LIKE', '%' . $search . '%')
                            ->orWhereIn('sub_category_id', $sub_cat_ids);
                    })
                    ->where('status', '=', '1');
            }

            if (isset($request->talent_name)) { 

                $attribute = $request->talent_name;
                $all_talents = $all_talents->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });

                $talent_category = $talent_category->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });
            }

            if ($request->main_cat != "all" && isset($request->main_cat)) { //main cat

                $sub_cat_ids = SubCategory::where('main_category_id', '=', $request->main_cat)->pluck('id')->toArray();
                $all_talents = $all_talents->whereIn('sub_category_id', $sub_cat_ids);
                $talent_category = $talent_category->whereIn('sub_category_id', $sub_cat_ids);
            }

            if ($request->sub_category != "all" && isset($request->sub_category)) {  // sub cat

                $all_talents = $all_talents->where('sub_category_id', $request->sub_category);
                $talent_category = $talent_category->whereIn('sub_category_id', $request->sub_category);
            }

            if ($request->location != "all" && isset($request->location)) { //location means city id

                $all_talents = $all_talents->where('city', $request->location);
                $talent_category = $talent_category->where('city', $request->location);
            }

            if (isset($request->sub_category_id)) {
                $all_talents = $all_talents->where('sub_category_id', $request->sub_category_id);
            }

            if (isset($request->sub_cate_id)) {  //talent name

                $attribute = $request->sub_cate_id;
                $all_talents = $all_talents->filter(function ($item) use ($attribute) {
                    return strpos($item->sub_category_id, $attribute) !== false;
                });
            }

            if (isset($request->date_from)) {
                $all_talents = $all_talents->where('date_time', '>', $request->date_from . " 00:00:00");
            }

            if (isset($request->date_to)) {
                $all_talents = $all_talents->where('date_time', '<=', $request->date_to . " 23:59:59");
            }
        }

        $feature_talents = $feature_talents->get();
        $popular_talents = $popular_talents->get();
        $total_count = 0;

        if (isset($all_talents)) {
            $total_count = $all_talents->count();
        }

        $result_array = array(
            'all_talents' => $all_talents->paginate($perPage),
            'total_count' => $total_count,
            'talent_category' => $talent_category,
            'feature_talents' => $feature_talents,
            'popular_talents' => $popular_talents,
            'nextPageUrl' => $nextPageUrl,
        );

        return $result_array;
    }

    public function talent_detail($slug)
    {
        $talent = Talents::where('slug', '=', $slug)->first();
        $talent->load(['approvedReviews', 'featureImage', 'logoImage', 'mainImage', 'mainImages', 'storyImages']);
        $data = $talent;
        $type = 'talent';
       
        $similar = Talents::with('get_subcat', 'featureImage', 'approvedReviews')->where('status', 1)->where('sub_category_id', $talent->sub_category_id)->where('id', '!=', $talent->id)->get();

        $popular = Talents::with('get_subcat', 'featureImage', 'approvedReviews')->where('status', 1)->where('is_popular', 1)->get();

        $talent_category = Talents::where('status', '=', '1')
            ->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        
        $avg_rating = round($talent->approvedReviews->avg('rating'), 1);
        if ($talent->lat && $talent->lng) {
            $nearby = Talents::with(['featureImage', 'approvedReviews'])->select(
                    "talents.id",
                    DB::raw("6371 * acos(cos(radians(" . $talent->lat . "))
                * cos(radians(talents.lat))
                * cos(radians(talents.lng) - radians(" . $talent->lng . "))
                + sin(radians(" . $talent->lat . "))
                * sin(radians(talents.lat))) AS distance"),
                    "talents.*",
                    "sub_categories.name as sub_category"
                )
                ->join('sub_categories', 'sub_categories.id', 'talents.sub_category_id')
                ->where('talents.status', 1)
                ->where('talents.id', '<>', $talent->id)
                ->groupBy("talents.id")
                ->orderBy('distance', 'ASC')
                ->get();
        } else {
            $nearby = [];
        }

        $shareIcons = createSocialShareIcons($talent, 'talent');
        $recommended = Recommendation::where(['module_type' => 'talents', 'module_id' => $talent->id])->count();
        $count = $talent->views + 1;
        $talent->views = $count;
        $talent->timestamps = false;
        $talent->save();

        $alert = AlertNews::first();
        $firstStoryImage = $talent->storyImages()->first();
        $whatsapp = MajorCategory::where('id', 17)->first();
        $breadcrumbUrl = route('talent-list', ['category_slug' => $talent->subCategory->mainCategory->slug]);
        if($talent->mainImage) {
            $og_image = $talent-> getStoredImage($talent->mainImage->image, 'main_image');
        } else if($talent->featureImage) {
            $og_image = $talent-> getStoredImage($talent->featureImage->image, 'feature_image');
        } else {
            $og_image = '/v2/images/image-placeholder.jpeg';
        }

        return view('user.talent.talent-detail', get_defined_vars());
    }

    public function talentsByType(Request $request, $type)
    {
        $main_category = MainCategory::where('major_category_id', 17)->pluck('id')->toArray();
        $cat_details = DynamicLink::where('major_category_id', 17)->where('slug', $type)->first();
        $dynamic_links = DynamicLink::where('major_category_id', 17)->pluck('link_title', 'slug')->toArray();
        $quick_search = $request->quick_search ?? '';
        $type = $request->type ?? $type;
        $sort_by = $request->sort_by ?? 1;
        $pageNo = $request->get('page', 1);
        $perPage = 10;
        if($cat_details->related_items) {
            $totalItems = Talents::select(['id'])->active()->whereIn('id', $cat_details->related_items);
        } else {
            $totalItems = Talents::select(['id'])->active();
        }
        $totalItems = $totalItems->count();
        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/talents/view-listings/".$type."?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
            $nextPageUrl .= "&sort_by=".$sort_by;
        }
        else {
            $nextPageUrl = '';
        }

        $all_talents = Talents::with('get_subcat', 'featureImage', 'approvedReviews')->active();
        if($cat_details->related_items) {
            $all_talents = $all_talents->whereIn('id', $cat_details->related_items);
        }
       
        if (isset($request)) {
            if (!empty($quick_search)) {
                $search = $quick_search;
                $all_talents = $all_talents->where(function ($query) use ($search) {
                    $query->orWhere('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%');
                });
            }
            if (!empty($sort_by)) {
                if ($sort_by == "1") {
                    $all_talents = $all_talents->orderBy('created_at', 'desc');
                } elseif ($sort_by == "2") { //oldest to newest
                    $all_talents = $all_talents->orderBy('created_at', 'asc');
                } elseif ($sort_by == "3") { // A to Z
                    $all_talents = $all_talents->orderBy('title', 'asc');
                } elseif ($sort_by == "4") { // Z to A
                    $all_talents = $all_talents->orderBy('title', 'desc');
                }
            } else {
                $all_talents = $all_talents->orderBy('created_at', 'desc');
            }
        }

        $total_count = 0;
        if (isset($all_talents)) {
            $total_count = $all_talents->count();
        }
        $all_talents = $all_talents->paginate(10);
        if ($request->ajax()) {
            return $view = view('user.talent.list', get_defined_vars())->render();
        }
        return view('user.common.listings-by-type', get_defined_vars());
    }

    function search_talent_name_ajax(Request $request)
    {
        $search = $request->keyword;
        $talents = array();
        if (!empty($search)) {
            $talents = Talents::select('title', 'id')->where('title', 'LIKE', '%' . $search . '%')->orderby('title')->limit(6)->get();
        }

        $view = view('user.talent.ajax.autocomplete_talent_ajax', compact('talents'))->render();
        return response()->json(['html' => $view]);
    }
}
