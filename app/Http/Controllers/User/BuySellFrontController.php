<?php

namespace App\Http\Controllers\User;

use App\Models\DynamicLink;
use App\Models\Recommendation;
use DB;
use App\Models\City;
use App\Models\News;
use App\Models\State;
use App\Models\Banner;
use App\Models\BuySell;
use App\Models\Gallery;
use App\Models\Tickets;
use App\Models\AlertNews;
use App\Models\Concierge;
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
use App\Models\ItemRecommendation;
use App\Mail\ConciergeEnquiryMail;
use App\Http\Controllers\Controller;
use App\Constants\MajorCategoryConst;
use App\Models\HomeSectionContent;
use App\Models\HomeTrendBanner;

class BuySellFrontController extends Controller
{
    public function buysell(Request $request, $category_slug = '')
    {
        $main_category = MainCategory::where('major_category_id', '=', '3')->pluck('id')->toArray();

        $cities = BuySell::where('city', '!=', 'null')->pluck('city', 'city')->toArray();

        $states = BuySell::where('area', '!=', 'null')->pluck('area', 'area')->toArray();

        //$buysells = BuySell::active()->orderBy('created_at', 'DESC')->get();

        $result_array = $this->search_buysell($request, $category_slug);

        $all_buysells = $result_array['all_buysells'];
        $total_count = $result_array['total_count'];
        $buysell_category = $result_array['buysell_category'];
        $feature_buysells = $result_array['feature_buysells'];
        $popular_buysells = $result_array['popular_buysells'];
        $nextPageUrl = $result_array['nextPageUrl'];

        $justJoin = $all_buysells->take($request->get('limit', 10))->sortByDesc('created_at');

        $main_category = MainCategory::withCount('buysells')->where('major_category_id', '=', '3')->get();
        $banner = SliderImage::where('major_category_id', 3)->get();
        $major_category = MajorCategory::find(3);
        $major_category->load(['searchLinksTop', 'searchLinksBottom', 'statistics', 'bannerLinksTop', 'bannerLinksRight', 'bannerLinksBottom', 'bannerLinksLeft']);
        //$categories = MainCategory::where('major_category_id', 3)->withCount('buysells')->get();
        //$hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        //$influencer_reviews = InfluencerReview::active()->limit(3)->get();

        $categoryForSidebar = MainCategory::with('subCategory.buysells')->withCount('buysells')->where('major_category_id', 3)->orderBy('buysells_count', 'DESC')->get();

        $quick_search = $request->quick_search ?? '';
        $main_cat = $request->main_cat ?? '';
        $sub_category = $request->sub_category ?? '';
        $sub_category_id = $request->sub_category_id ?? '';
        $city_search = $request->city ?? '';
        $location_search = $request->location ?? '';
        $area = $request->area ?? '';
        $distance = $request->distance ?? '';
        $deal = $request->deal ?? '';
        $verified = $request->verified ?? '';
        $sort_by = $request->sort_by ?? '';


        if($main_cat) {
            $sub_cat_search = SubCategory::where('main_category_id', '=', $main_cat)->pluck('name', 'id')->toArray();
        } else {
            $sub_cat_search = [];
        }
        if ($request->ajax()) {
            return $view = view('user.buysell.list', get_defined_vars())->render();
        }

        $allCategory = MajorCategory::with(['mainCategory', 'mainCategory.subCategory']);

        if (!empty($category_slug) || !empty($request->main_cat)) {
            if ($sub_category_id) {
                $cat_details = SubCategory::find($sub_category_id);
            } else if (!empty($main_cat) && $main_cat != 'all') {
                $cat_details = MainCategory::find($main_cat);
            } else {
                $cat_details = null;
            }

            return view('user.buysell.buysell-listing', get_defined_vars());
        }
        $dynamic_links = DynamicLink::where('major_category_id', 3)->whereNotIn('slug', ['popular-buy-and-sells', 'trending-buy-and-sells', 'hot-buy-and-sells'])->get();
        $deals_link = DynamicLink::where('major_category_id', 3)->where('slug', 'deals')->first();
        $verified_suppliers_link = DynamicLink::where('major_category_id', 3)->where('slug', 'verified-suppliers')->first();
        $popular_links = $major_category->popularDynamicLinks()->first();
        $trending_links = $major_category->trendingDynamicLinks()->first();
        $hot_links = $major_category->hotDynamicLinks()->first();
        $top_searches = MainCategory::with('topSearch')
            ->withCount('topSearch')
            ->where('major_category_id', 3)
            ->orderBy('top_search_count', 'DESC')
            ->get();
        $popular_searches = MainCategory::with('popularSearch')
            ->withCount('popularSearch')
            ->where('major_category_id', 3)
            ->orderBy('popular_search_count', 'DESC')
            ->get();
        return view('user.buysell.index', get_defined_vars());
    }

    public function search_buysell($request, $category_slug)
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
        $deal = $request->deal ?? '';
        $verified = $request->verified ?? '';
        $sort_by = $request->sort_by ?? '';
        $pageNo = $request->get('page', 1);
        $perPage = 10;
        $totalItems = BuySell::select(['id'])->active()->count();
        if($pageNo < round($totalItems/$perPage)) {
            $nextPageUrl = "/buy-and-sells?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
            $nextPageUrl .= "&main_cat=".$main_cat;
            $nextPageUrl .= "&sub_category=".$sub_category;
            $nextPageUrl .= "&sub_category_id=".$sub_category_id;
            $nextPageUrl .= "&city=".urlencode($city_search);
            $nextPageUrl .= "&location=".urlencode($location_search);
            $nextPageUrl .= "&area=".$area;
            $nextPageUrl .= "&distance=".$distance;
            $nextPageUrl .= "&deal=".$deal;
            $nextPageUrl .= "&verified=".$verified;
            $nextPageUrl .= "&sort_by=".$sort_by;
        }
        else {
            $nextPageUrl = '';
        }
        $select_fields = ['id', 'product_name', 'slug', 'location', 'area', 'city', 'sub_category_id',
            'view_count', 'map_review', 'map_rating', 'brand_id', 'price',
            'created_at'];
        $all_buysells = BuySell::select($select_fields)->with('get_subcat', 'featureImage', 'approvedReviews')->active();

        /*$buysell_category = BuySell::where('status', '=', '1')
            ->select('product_name', 'sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")->orderBy('created_at', 'DESC');*/

        $feature_buysells = BuySell::select($select_fields)->with('get_subcat', 'featureImage', 'approvedReviews')->active()->featured();
        $popular_buysells = BuySell::select($select_fields)->with('get_subcat', 'featureImage', 'approvedReviews')->active()->popular();
        if (!empty($request->sort_by)) {
            if ($request->sort_by == "1") {
                $all_buysells = $all_buysells->orderBy('created_at', 'desc');

                $feature_buysells = $feature_buysells ? $feature_buysells->orderBy('created_at', 'desc') : null;
                $popular_buysells = $popular_buysells ? $popular_buysells->orderBy('created_at', 'desc') : null;
            } elseif ($request->sort_by == "2") { //oldest to newest

                $all_buysells = $all_buysells->active()->orderBy('created_at', 'asc');

                $feature_buysells = $feature_buysells ? $feature_buysells->orderBy('created_at', 'asc') : null;
                $popular_buysells = $popular_buysells ? $popular_buysells->orderBy('created_at', 'asc') : null;
            } elseif ($request->sort_by == "3") { // A to Z
                $all_buysells = $all_buysells->orderBy('title', 'asc');

                $feature_buysells = $feature_buysells ? $feature_buysells->orderBy('title', 'asc') : null;
                $popular_buysells = $popular_buysells ? $popular_buysells->orderBy('title', 'asc') : null;
            } elseif ($request->sort_by == "4") { // Z to A
                $all_buysells = $all_buysells->orderBy('title', 'desc');

                $feature_buysells = $feature_buysells ? $feature_buysells->orderBy('title', 'desc') : null;
                $popular_buysells = $popular_buysells ? $popular_buysells->orderBy('title', 'desc') : null;
            }
        } else {
            $all_buysells = $all_buysells->orderBy('created_at', 'desc');
            $feature_buysells = $feature_buysells ? $feature_buysells->orderBy('created_at', 'desc') : null;
            $popular_buysells = $popular_buysells ? $popular_buysells->orderBy('created_at', 'desc') : null;
        }
        if (!empty($request)) {


            if (!empty($request->quick_search)) {
                $search = $request->quick_search;

                $main_cat_ids = MainCategory::where('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();

                $sub_cat_ids = SubCategory::where('name', 'LIKE', '%' . $search . '%')
                    ->orWhereIn('main_category_id', $main_cat_ids)
                    ->pluck('id')
                    ->toArray();

                $all_buysells = BuySell::with('get_subcat', 'featureImage', 'approvedReviews')
                    ->where(function ($query) use ($search, $sub_cat_ids) {
                        $query->orWhere('product_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('description', 'LIKE', '%' . $search . '%')
                            ->orWhereIn('sub_category_id', $sub_cat_ids);
                    })
                    ->active();
            }

            if ($request->main_cat !== "all" && !empty($request->main_cat)) { //main cat

                $sub_cat_ids = SubCategory::where('main_category_id', '=', $request->main_cat)->pluck('id')->toArray();

                $all_buysells = $all_buysells->whereIn('sub_category_id', $sub_cat_ids);

                //$buysell_category = $buysell_category->whereIn('sub_category_id', $sub_cat_ids);

            }

            if ($request->sub_category !== "all" && !empty($request->sub_category)) {

                $all_buysells = $all_buysells->where('sub_category_id', $request->sub_category);
                //$buysell_category = $buysell_category->where('sub_category_id', $request->sub_category);
            }
            if ($request->location !== "all" && !empty($request->location)) {
                $all_buysells = $all_buysells->where('city', $request->location);
                //$buysell_category = $buysell_category->where('city', $request->location);
            }
            if ($request->area !== "all" && !empty($request->area)) {
                $all_buysells = $all_buysells->where('area', $request->area);
                //$buysell_category = $buysell_category->where('area', $request->area);
            }
            if ($deal && $deal == '1') {
                $all_buysells = $all_buysells->where('is_deals', 1);
                //$buysell_category = $buysell_category->where('is_deals', 1);
            }
            if ($verified && $verified == '1') {
                $all_buysells = $all_buysells->where('is_verified', 1);
                //$buysell_category = $buysell_category->where('is_verified', 1);
            }

            if (!empty($request->sub_category_id)) {
                $all_buysells = $all_buysells->where('sub_category_id', $request->sub_category_id);
            }

            if (!empty($request->sub_cate_id)) { //event name
                $attribute = $request->sub_cate_id;
                $all_buysells = $all_buysells->filter(function ($item) use ($attribute) {
                    return strpos($item->sub_category_id, $attribute) !== false;
                });
            }
        }

        $feature_buysells = $feature_buysells->get();
        $popular_buysells = $popular_buysells->get();
        //$buysell_category = $buysell_category->get();
        $buysell_category = null;
        $total_count = 0;

        if (!empty($all_buysells)) {
            $total_count = $all_buysells->count();
        }

        $result_array = array(
            'all_buysells' => $all_buysells->paginate($perPage),
            'total_count' => $total_count,
            'buysell_category' => $buysell_category,
            'feature_buysells' => $feature_buysells,
            'popular_buysells' => $popular_buysells,
            'nextPageUrl' => $nextPageUrl,
        );
        return $result_array;
    }

    public function more_data(Request $request)
    {
        if ($request->ajax()) {
            $skip = $request->skip;
            $take = 5;
            $all_buysells = BuySell::with('get_subcat', 'featureImage', 'approvedReviews')->skip($skip)->take($take)->get();
            return view('user.buysell.list', compact('all_buysells'));
        } else {
            return response()->json('Direct Access Not Allowed!!');
        }
    }

    public function buy_and_sell_detail($slug)
    {
        $buysell = BuySell::with(['subCategory', 'featureImage', 'storyImages', 'mainImages', 'logoImage', 'menuImage', 'mainImage', 'approvedReviews'])->where('slug', '=', $slug)->active()->first();
        $data = $buysell;
        $type = 'buysell';
        $more_info = NULL;
        if (!empty($data->id)) {
            $more_info = MoreInfo::where(['module_id' => $data->id, 'module_name' => 'buysell'])->get();
        }
        $similar = BuySell::with('get_subcat', 'featureImage', 'approvedReviews')->active()->where('sub_category_id', $buysell->sub_category_id)->where('id', '!=', $buysell->id)->get();
        $popular = BuySell::with('get_subcat', 'featureImage', 'approvedReviews')->active()->popular()->get();
        $buysell_category = BuySell::where('status', '=', '1')
            ->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $buysell->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }

        $avg_rating = round($buysell->approvedReviews->avg('rating'), 1);

        if ($buysell->lat && $buysell->lng) {
            $nearby = BuySell::select(
                "buy_sells.id",
                DB::raw("6371 * acos(cos(radians(" . $buysell->lat . "))
                * cos(radians(buy_sells.lat))
                * cos(radians(buy_sells.lng) - radians(" . $buysell->lng . "))
                + sin(radians(" . $buysell->lat . "))
                * sin(radians(buy_sells.lat))) AS distance"),
                "buy_sells.*",
                "sub_categories.name as sub_category"
            )
                // ->with('subCategory')
                ->join('sub_categories', 'sub_categories.id', 'buy_sells.sub_category_id')
                ->where('buy_sells.status', 1)
                ->groupBy("buy_sells.id")
                ->orderBy('distance', 'ASC')
                ->get();
        } else {
            $nearby = [];
        }
        //dd($nearby);
        $shareIcons = createSocialShareIcons($buysell, 'buy-and-sell');

        //$recommended = ItemRecommendation::whereHasMorph('item', [BuySell::class])->active()->get();
        $recommended = Recommendation::where(['module_type' => 'buysell', 'module_id' => $buysell->id])->count();

        $count = $buysell->view_count + 1;
        $buysell->view_count = $count;
        $buysell->timestamps = false;
        $buysell->save();

        $alert = AlertNews::first();

        $firstStoryImage = $buysell->storyImages()->first();

        $whatsapp = MajorCategory::where('id', 3)->first();
        $breadcrumbUrl = route('buy-sell-list', ['category_slug' => $buysell->subCategory->mainCategory->slug]);

        if ($buysell->mainImage) {
            $og_image = $buysell->getStoredImage($buysell->mainImage->image, 'main_image');
        } else if ($buysell->featureImage) {
            $og_image = $buysell->getStoredImage($buysell->featureImage->image, 'feature_image');
        } else {
            $og_image = '/v2/images/image-placeholder.jpeg';
        }

        return view('user.buysell.buysell-detail', get_defined_vars());
    }

    public function buyAndSellByType(Request $request, $type)
    {
        $main_category = MainCategory::where('major_category_id', 3)->pluck('id')->toArray();
        $cat_details = DynamicLink::where('major_category_id', 3)->where('slug', $type)->first();
        $dynamic_links = DynamicLink::where('major_category_id', 3)->pluck('link_title', 'slug')->toArray();
        $quick_search = $request->quick_search ?? '';
        $type = $request->type ?? $type;
        $sort_by = $request->sort_by ?? 1;
        $pageNo = $request->get('page', 1);
        $perPage = 10;
        if($cat_details->related_items) {
            $totalItems = BuySell::select(['id'])->active()->whereIn('id', $cat_details->related_items);
        } else {
            $totalItems = BuySell::select(['id'])->active();
        }
        $totalItems = $totalItems->count();
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $totalItems = $totalItems->{$scope}()->count();
        } else {
            $totalItems = $totalItems->count();
        }*/

        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/buy-and-sells/view-listings/".$type."?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
            $nextPageUrl .= "&sort_by=".$sort_by;
        }
        else {
            $nextPageUrl = '';
        }

        $all_buysells = BuySell::with('get_subcat', 'featureImage', 'approvedReviews')->active();
        if($cat_details->related_items) {
            $all_buysells = $all_buysells->whereIn('id', $cat_details->related_items);
        }
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $all_buysells = $all_buysells->{$scope}();
        }*/
        if (!empty($request)) {
            if (!empty($quick_search)) {
                $search = $quick_search;
                $all_buysells = $all_buysells->where(function ($query) use ($search) {
                    $query->orWhere('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%');
                });
            }
            if (!empty($sort_by)) {
                if ($sort_by == "1") {
                    $all_buysells = $all_buysells->orderBy('created_at', 'desc');
                } elseif ($sort_by == "2") { //oldest to newest
                    $all_buysells = $all_buysells->orderBy('created_at', 'asc');
                } elseif ($sort_by == "3") { // A to Z
                    $all_buysells = $all_buysells->orderBy('title', 'asc');
                } elseif ($sort_by == "4") { // Z to A
                    $all_buysells = $all_buysells->orderBy('title', 'desc');
                }
            } else {
                $all_buysells = $all_buysells->orderBy('created_at', 'desc');
            }
        }

        //dd($all_buysells->toSql());
        $buysells_count = 0;
        if (!empty($all_buysells)) {
            $buysells_count = $all_buysells->count();
        }
        $all_buysells = $all_buysells->paginate(10);
        //dd($all_buysells->toSql(), $total_count);
        if ($request->ajax()) {
            //dd($all_buysells->toSql(), $total_count);
            return $view = view('user.venue.list', get_defined_vars())->render();
        }

        return view('user.common.listings-by-type', get_defined_vars());
    }

    public function sidebarSearch($request)
    {
        $main_category = $request->main_category;
        $sub_category = $request->sub_category;
        $location = $request->location;
        $city = $request->city;

        $featured = [];
        // if($main_category) {
        //     $featured = BuySell::whereHas('subCategory.mainCategory', function($q) use($main_category) {
        //         $q->where('id', $main_category);
        //     });
        // }
        // if($sub_category) {
        //     $featured = $featured->where('sub_category_id',$sub_category);
        // }if($location) {
        //     $featured = $featured->where('city_id',$location);
        // }
        // $featured = $featured->active()->where('assign_featured', 1)->get();

        $datas = BuySell::with('subCategory.mainCategory', 'city');

        if ($request->dynamic_subs == "one") {
            $ids = [];
            $dat = BuySell::active()->get();
            foreach ($request->dynamic_sub as $sub) {
                foreach ($dat as $row) {
                    if (!empty($row->dynamic_sub_ids)) {
                        $abc = (in_array($sub, json_decode($row->dynamic_sub_ids))) ? $ids[] = $row->id : '';
                    }
                }
            }
            $datas = BuySell::whereIn('id', $ids);
        }

        if ($main_category) {
            $datas = BuySell::whereHas('subCategory.mainCategory', function ($q) use ($main_category) {
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

        if (!empty($request->sort_by)) {
            if ($request->sort_by == "1") {
                $datas = $datas->orderBy('created_at', 'desc');
            }
            if ($request->sort_by == "2") {
                $datas = $datas->orderBy('created_at', 'asc');
            }
            if ($request->sort_by == "3") {
                $datas = $datas->orderBy('title', 'asc');
            }
            if ($request->sort_by == "4") {
                $datas = $datas->orderBy('title', 'desc');
            }
        }

        $datas = $datas->active()->get();

        return $datas;
    }
}
