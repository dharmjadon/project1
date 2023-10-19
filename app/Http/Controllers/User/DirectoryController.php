<?php

namespace App\Http\Controllers\User;

use App\Models\Blog;
use App\Models\DynamicLink;
use App\Models\Recommendation;
use App\Models\City;
use App\Models\News;
use App\Models\State;
use App\Models\Directory;
use App\Models\MoreInfo;
use App\Models\SliderImage;
use App\Models\SubCategory;
use App\Models\MainCategory;
use App\Models\MajorCategory;
use Illuminate\Http\Request;
use App\Models\InfluencerReview;
use App\Models\ItemRecommendation;
use App\Http\Controllers\Controller;
use App\Models\HomeTrendBanner;
use App\Models\AlertNews;
use App\Models\EnquireForm;
use App\Mail\ConciergeEnquiryMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DirectoryController extends Controller
{
    public function index(Request $request, $category_slug = '')
    {
        $result_array = $this->search_directory($request, $category_slug);

        $all_directories = $result_array['all_directories'];
        $total_count = $result_array['total_count'];
        $directories_category = $result_array['directories_category'];
        $feature_directories = $result_array['feature_directories'];
        $popular_directories = $result_array['popular_directories'];
        $nextPageUrl = $result_array['nextPageUrl'];

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

        if($main_cat) {
            $sub_cat_search = SubCategory::where('main_category_id', '=', $main_cat)->pluck('name', 'id')->toArray();
        } else {
            $sub_cat_search = [];
        }

        if ($request->ajax()) {
            return $view = view('user.directory.list', get_defined_vars())->render();
        }

        $all_main_category = MainCategory::where('major_category_id', 4)->pluck('id')->toArray();
        $cities = Directory::where('city', '!=', 'null')->pluck('city', 'city')->toArray();
        $states = Directory::where('area', '!=', 'null')->pluck('area', 'area')->toArray();
        $justJoin = $all_directories->take($request->get('limit', 10))->sortByDesc('created_at');

        $main_category = MainCategory::where('major_category_id', 4)->get();
        $banner = SliderImage::where('major_category_id', 4)->get();
        $major_category = MajorCategory::find(4);
        $major_category->load(['searchLinksTop', 'searchLinksBottom', 'statistics', 'bannerLinksTop', 'bannerLinksRight', 'bannerLinksBottom', 'bannerLinksLeft']);
        $categories = MainCategory::where('major_category_id', 4)->withCount('directory')->get();

        $categoryForSidebar = MainCategory::with('subCategory.directory')->withCount('directory')->where('major_category_id', 4)->orderBy('directory_count', 'DESC')->get();
        $allCategory = MajorCategory::with(['mainCategory', 'mainCategory.subCategory']);

        if(!empty($category_slug) || !empty($request->main_cat) || $request->anyFilled(['quick_search', 'location', 'area', 'date_from', 'date_from', 'sort_by', 'distance'])) {
            if($sub_category_id) {
                $cat_details = SubCategory::find($sub_category_id);
            } else if(!empty($main_cat) && $main_cat != 'all') {
                $cat_details = MainCategory::find($main_cat);
            } else {
                $cat_details = null;
            }
            return view('user.directory.directory-listing', get_defined_vars());
        }
        $dynamic_links = DynamicLink::where('major_category_id', 4)->whereNotIn('slug', ['popular-directories', 'trending-directories', 'hot-directories'])->get();
        $popular_links = $major_category->popularDynamicLinks()->first();
        $trending_links = $major_category->trendingDynamicLinks()->first();
        $hot_links = $major_category->hotDynamicLinks()->first();
        $top_searches = MainCategory::with('topSearch')
            ->withCount('topSearch')
            ->where('major_category_id', 4)
            ->orderBy('top_search_count', 'DESC')
            ->get();
        $popular_searches = MainCategory::with('popularSearch')
            ->withCount('popularSearch')
            ->where('major_category_id', 4)
            ->orderBy('popular_search_count', 'DESC')
            ->get();

        $online_market = Directory::select(['id', 'slug', 'title'])->where('online_market', 1)->active()->limit(20)->get();
        if(isset($major_category->blogs_list)){
            $blogs_list = Blog::select(['id', 'slug', 'title', 'content'])->active()->whereIn('id', $major_category->blogs_list)->get();
        } else {
            $blogs_list = null;
        }

        return view('user.directory.index', get_defined_vars());
    }

    public function search_directory($request, $category_slug)
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
        $date_from = $request->date_from ?? '';
        $date_to = $request->date_to ?? '';
        $pageNo = $request->get('page', 1);
        $perPage = 10;
        $totalItems = Directory::select(['id'])->active()->count();
        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/directories?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
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
        $all_directories = Directory::with(['get_subcat', 'featureImage', 'approvedReviews'])->where('status', 1);

        $directories_category = Directory::with(['get_subcat'])->where('status', 1)
            ->select('title', 'sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();

        $feature_directories = Directory::with(['get_subcat', 'featureImage', 'approvedReviews'])->where('status', 1)->where('is_feature', 1);

        $popular_directories = Directory::with(['get_subcat', 'featureImage', 'approvedReviews'])->where('status', 1)->where('is_popular', 1);


        if (isset($request)) {
            if (isset($request->sort_by)) {
                if ($request->sort_by == "1") {  // newest to oldest
                    $all_directories = $all_directories->orderBy('created_at', 'desc');
                    $feature_directories = $feature_directories ? $feature_directories->orderBy('created_at', 'desc') : null;
                    $popular_directories = $popular_directories ? $popular_directories->orderBy('created_at', 'desc') : null;
                } elseif ($request->sort_by == "14") { //oldest to newest
                    $all_directories = $all_directories->orderBy('created_at', 'asc');
                    $feature_directories = $feature_directories ? $feature_directories->orderBy('created_at', 'asc') : null;
                    $popular_directories = $popular_directories ? $popular_directories->orderBy('created_at', 'asc') : null;
                } elseif ($request->sort_by == "3") { // A to Z
                    $all_directories = $all_directories->orderBy('title', 'asc');
                    $feature_directories = $feature_directories ? $feature_directories->orderBy('title', 'asc') : null;
                    $popular_directories = $popular_directories ? $popular_directories->orderBy('title', 'asc') : null;
                } elseif ($request->sort_by == "4") {  // Z to A
                    $all_directories = $all_directories->orderBy('title', 'desc');
                    $feature_directories = $feature_directories ? $feature_directories->orderBy('title', 'desc') : null;
                    $popular_directories = $popular_directories ? $popular_directories->orderBy('title', 'desc') : null;
                }
            } else {
                $all_directories = $all_directories->orderBy('created_at', 'desc');
                $feature_directories = $feature_directories ? $feature_directories->orderBy('created_at', 'desc') : null;
                $popular_directories = $popular_directories ? $popular_directories->orderBy('created_at', 'desc') : null;
            }

            if (isset($request->quick_search)) {   //quick search
                $search = $request->quick_search;

                if(strlen($search) >1){
                    $main_cat_ids = MainCategory::where('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();
                    $sub_cat_ids = SubCategory::where('name', 'LIKE', '%' . $search . '%')
                        ->orWhereIn('main_category_id', $main_cat_ids)
                        ->pluck('id')
                        ->toArray();
                    $all_directories = Directory::with('get_subcat', 'featureImage', 'approvedReviews')
                        ->where(function ($query) use ($search, $sub_cat_ids) {
                            $query->orWhere('title', 'LIKE', '%' . $search . '%')
                                ->orWhere('description', 'LIKE', '%' . $search . '%')
                                ->orWhereIn('sub_category_id', $sub_cat_ids);
                        })
                        ->where('status', 1);

                }else{
                    $all_directories = Directory::with('subCategory', 'featureImage', 'approvedReviews')
                    ->where(function ($query) use ($search) {
                        $query->where('title', 'LIKE',  $search . '%');
                    })
                    ->where('status', '=', '1');
                }
            }

            if ($request->main_cat != "all" && isset($request->main_cat)) { //main cat

                $sub_cat_ids = SubCategory::where('main_category_id', '=', $request->main_cat)->pluck('id')->toArray();
                $all_directories = $all_directories->whereIn('sub_category_id', $sub_cat_ids);
                $directories_category = $directories_category->whereIn('sub_category_id', $sub_cat_ids);
            }

            if ($request->sub_category != "all" && isset($request->sub_category)) {  // sub cat

                $all_directories = $all_directories->where('sub_category_id', $request->sub_category);
                $directories_category = $directories_category->whereIn('sub_category_id', $request->sub_category);
            }

            if ($request->location != "all" && isset($request->location)) { //location means city id

                $all_directories = $all_directories->where('city', $request->location);
                $directories_category = $directories_category->where('city', $request->location);
            }

            if ($request->area != "all" && isset($request->area)) {
                $all_directories = $all_directories->where('area', $request->area);
                $directories_category = $directories_category->where('city', $request->area);
            }

            if (isset($request->sub_category_id)) {
                $all_directories = $all_directories->where('sub_category_id', $request->sub_category_id);
            }

            if (isset($request->date_from)) {
                $all_directories = $all_directories->where('created_at', '>', $request->date_from . " 00:00:00");
            }

            if (isset($request->date_to)) {
                $all_directories = $all_directories->where('created_at', '<=', $request->date_to . " 23:59:59");
            }
        }

        $feature_directories = $feature_directories->get();
        $popular_directories = $popular_directories->get();

        $total_count = 0;
        if (isset($all_directories)) {
            $total_count = $all_directories->count();
        }

        $result_array = array(
            'all_directories' => $all_directories->paginate($perPage),
            'total_count' => $total_count,
            'directories_category' => $directories_category,
            'feature_directories' => $feature_directories,
            'popular_directories' => $popular_directories,
            'nextPageUrl' => $nextPageUrl,
        );
        return $result_array;
    }

    public function directory_detail($slug)
    {
        $directory = Directory::where('slug', '=', $slug)->first();
        $directory->load(['subCategory', 'approvedReviews', 'featureImage', 'logoImage', 'floorPlanImage', 'menuImage', 'mainImage', 'mainImages', 'storyImages']);
        $data = $directory;
        $type = 'directory';
        $more_info = NULL;
        if (isset($data->id)) {
            $more_info = MoreInfo::where(['module_id' => $data->id, 'module_name' => 'directory'])->get();
        }
        $similar = Directory::with('get_subcat', 'featureImage', 'approvedReviews')->where('status', 1)->where('sub_category_id', $directory->sub_category_id)->where('id', '!=', $data->id)->get();
        $popular = Directory::with('get_subcat', 'featureImage', 'approvedReviews')->where('status', 1)->where('is_popular', 1)->get();
        $directory_category = Directory::where('status', '=', '1')
            ->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $directory->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }

        $avg_rating = round($directory->approvedreviews()->avg('rating'), 1);

        if ($directory->lat && $directory->long) {
            $nearby = Directory::with(['featureImage'])->select(
                    "directories.id",
                    DB::raw("6371 * acos(cos(radians(" . $directory->lat . "))
                * cos(radians(directories.lat))
                * cos(radians(directories.long) - radians(" . $directory->long . "))
                + sin(radians(" . $directory->lat . "))
                * sin(radians(directories.lat))) AS distance"),
                    "directories.*",
                    "sub_categories.name as sub_category"
                )
                // ->with('subCategory')
                ->join('sub_categories', 'sub_categories.id', 'directories.sub_category_id')
                ->where('directories.status', 1)
                ->groupBy("directories.id")
                ->orderBy('distance', 'ASC')
                ->get();
        } else {
            $nearby = [];
        }
        $shareIcons = createSocialShareIcons($directory, 'directory');

        $recommended = Recommendation::where(['module_type' => 'directory', 'module_id' => $directory->id])->count();
        $count = $directory->views_counter + 1;
        $directory->views_counter = $count;
        $directory->timestamps = false;
        $directory->save();

        $alert = AlertNews::first();
        $firstStoryImage = $directory->storyImages()->first();
        $whatsapp = MajorCategory::where('id', 4)->first();
        $breadcrumbUrl = route('directory-list', ['category_slug' => $directory->subCategory->mainCategory->slug]);
        if($directory->mainImage) {
            $og_image = $directory-> getStoredImage($directory->mainImage->image, 'main_image');
        } else if($directory->featureImage) {
            $og_image = $directory-> getStoredImage($directory->featureImage->image, 'feature_image');
        } else {
            $og_image = '/v2/images/image-placeholder.jpeg';
        }

        return view('user.directory.directory-detail', get_defined_vars());
    }

    public function directoriesByType(Request $request, $type)
    {
        $main_category = MainCategory::where('major_category_id', 4)->pluck('id')->toArray();
        $cat_details = DynamicLink::where('major_category_id', 4)->where('slug', $type)->first();
        $dynamic_links = DynamicLink::where('major_category_id', 4)->pluck('link_title', 'slug')->toArray();
        $quick_search = $request->quick_search ?? '';
        $type = $request->type ?? $type;
        $sort_by = $request->sort_by ?? 1;
        $pageNo = $request->get('page', 1);
        $perPage = 10;
        if($cat_details->related_items) {
            $totalItems = Directory::select(['id'])->active()->whereIn('id', $cat_details->related_items);
        } else {
            $totalItems = Directory::select(['id'])->active();
        }
        $totalItems = $totalItems->count();
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $totalItems = $totalItems->{$scope}()->count();
        } else {
            $totalItems = $totalItems->count();
        }*/

        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/directories/view-listings/".$type."?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
            $nextPageUrl .= "&sort_by=".$sort_by;
        }
        else {
            $nextPageUrl = '';
        }

        $all_directories = Directory::with('get_subcat', 'featureImage', 'approvedReviews')->active();
        if($cat_details->related_items) {
            $all_directories = $all_directories->whereIn('id', $cat_details->related_items);
        }
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $all_directories = $all_directories->{$scope}();
        }*/
        if (isset($request)) {
            if (!empty($quick_search)) {
                $search = $quick_search;
                $all_directories = $all_directories->where(function ($query) use ($search) {
                    $query->orWhere('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%');
                });
            }
            if (!empty($sort_by)) {
                if ($sort_by == "1") {
                    $all_directories = $all_directories->orderBy('created_at', 'desc');
                } elseif ($sort_by == "2") { //oldest to newest
                    $all_directories = $all_directories->orderBy('created_at', 'asc');
                } elseif ($sort_by == "3") { // A to Z
                    $all_directories = $all_directories->orderBy('title', 'asc');
                } elseif ($sort_by == "4") { // Z to A
                    $all_directories = $all_directories->orderBy('title', 'desc');
                }
            } else {
                $all_directories = $all_directories->orderBy('created_at', 'desc');
            }
        }

        //dd($all_directories->toSql());
        $total_count = 0;
        if (isset($all_directories)) {
            $total_count = $all_directories->count();
        }
        $all_directories = $all_directories->paginate(10);
        //dd($all_directories->toSql(), $total_count);
        if ($request->ajax()) {
            //dd($all_directories->toSql(), $total_count);
            return $view = view('user.directory.list', get_defined_vars())->render();
        }

        return view('user.common.listings-by-type', get_defined_vars());
    }


    function search_directory_name_ajax(Request $request)
    {
        $search = $request->keyword;
        $directory = array();
        if (!empty($search)) {
            $directory = Directory::select('title', 'id')->where('title', 'LIKE', '%' . $search . '%')->orderby('title')->limit(6)->get();
        }
        $view = view('user.directory.ajax.autocomplete_directory_ajax', compact('directory'))->render();
        return response()->json(['html' => $view]);
    }
}
