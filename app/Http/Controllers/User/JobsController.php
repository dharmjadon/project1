<?php

namespace App\Http\Controllers\User;

use App\Models\DynamicLink;
use App\Models\JobCompany;
use App\Models\JobPageContent;
use DB;
use App\Models\Job;
use App\Models\It;
use App\Models\City;
use App\Models\News;
use App\Models\State;
use App\Models\JobUsers;
use App\Models\JobApplied;
use App\Models\Nationality;
use App\Models\SliderImage;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\MajorCategory;
use App\Models\HomeTrendBanner;
use App\Models\InfluencerReview;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class JobsController extends Controller
{
    public function index(Request $request, $category_slug = '')
    {

        $cities = Job::where('city', '!=', 'null')->pluck('city', 'city')->toArray();

        $type = 'jobs';

        //$jobs = Job::with('sub_category')->active()->get(); //return $jobs;
        // if (isset($jobs)) {
        //     $job_count = count($jobs);
        // }

        $jobSeekers = JobUsers::orderBy('created_at', 'DESC')->limit(10)->get(); //return $jobSeekers;

        $result_array = $this->search_result($request, $category_slug);

        $jobs = $result_array['jobs'];
        $total_count = $result_array['total_count'];
        $job_featured = $result_array['featured'];
        $job_popular = $result_array['popular'];
        $nextPageUrl = $result_array['nextPageUrl'];
        // return $job_popular;
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
        $location_search = $request->location ?? '';
        $salary = $request->salary ?? '';
        $experience = $request->experience ?? '';
        $job_type = $request->job_type ?? '';
        $sort_by = $request->sort_by ?? '';
        if($main_cat) {
            $sub_cat_search = SubCategory::where('main_category_id', '=', $main_cat)->pluck('name', 'id')->toArray();
        } else {
            $sub_cat_search = [];
        }
        if ($request->ajax()) {
            return $view = view('user.jobs.list', get_defined_vars())->render();
        }
        $main_category = MainCategory::where('major_category_id', '=', '7')->orderBy('name')->get();

        $dynamic_mains = DynamicMainCategory::where('major_category_id', '=', '7')->where('status', 1)->get();
        $banner = SliderImage::where('major_category_id', 7)->get();
        $major_category = MajorCategory::find(7);
        $states = State::all();
        $categories = MainCategory::where('major_category_id', 7)->withCount('job')->get(); //return $categories;
        $categoryForSidebar = MainCategory::with('subCategory.job')->withCount('job')->where('major_category_id', 7)->orderBy('job_count', 'DESC')->get(); //return $categoryForSidebar;
        $allCategory = MajorCategory::with(['mainCategory', 'mainCategory.subCategory']);

        /*$job_category = Job::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")->orderBy('total', 'desc')
            ->get();*/

        $justJoin = Job::with('sub_category.job')->where('status', '=', '1')->take($request->get('limit', 10))->groupBy('created_at')->orderBy('created_at', 'DESC')->get();
        // $justJoin = $allJobs->take($request->get('limit', 10))->groupBy('created_at')->sortBy('created_at'); return $justJoin;
        if (!empty($category_slug) || !empty($request->main_cat)) {
            if ($sub_category_id) {
                $cat_details = SubCategory::find($sub_category_id);
            } else if (!empty($main_cat) && $main_cat != 'all') {
                $cat_details = MainCategory::find($main_cat);
            } else {
                $cat_details = null;
            }
            return view('user.jobs.job-listing', get_defined_vars());
        }
        $dynamic_links = DynamicLink::where('major_category_id', 7)->whereNotIn('slug', ['popular-jobs', 'trending-jobs', 'hot-jobs'])->get();
        $popular_links = $major_category->popularDynamicLinks()->first();
        $trending_links = $major_category->trendingDynamicLinks()->first();
        $hot_links = $major_category->hotDynamicLinks()->first();
        $top_searches = MainCategory::with('topSearch')
            ->withCount('topSearch')
            ->where('major_category_id', 7)
            ->orderBy('top_search_count', 'DESC')
            ->get();
        $popular_searches = MainCategory::with('popularSearch')
            ->withCount('popularSearch')
            ->where('major_category_id', 7)
            ->orderBy('popular_search_count', 'DESC')
            ->get();
        $page_content = JobPageContent::find(1);
        $topCompanies = JobCompany::select(['company_name', 'slug', 'logo', 'location'])->withCount('openJobs')->orderBy('job_companies.created_at', 'desc')->limit(20)->get();
        return view('user.jobs.index', get_defined_vars());
    }

    public function search_result($request, $category_slug)
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
        $location_search = $request->location ?? '';
        $salary = $request->salary ?? '';
        $job_type = $request->job_type ?? '';
        $experience = $request->experience ?? '';
        $sort_by = $request->sort_by ?? '';
        $pageNo = $request->get('page', 1);
        $perPage = 8;
        //dd($main_cat);
        $totalItems = Job::select(['id'])->active()->count();
        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/jobs?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
            $nextPageUrl .= "&main_cat=".$main_cat;
            $nextPageUrl .= "&sub_category=".$sub_category;
            $nextPageUrl .= "&sub_category_id=".$sub_category_id;
            $nextPageUrl .= "&location=".urlencode($location_search);
            $nextPageUrl .= "&salary=".urlencode($salary);
            $nextPageUrl .= "&job_type=".$job_type;
            $nextPageUrl .= "&experience=".$experience;
            $nextPageUrl .= "&sort_by=".urlencode($sort_by);
        }
        else {
            $nextPageUrl = '';
        }
        // $allJobs = Job::with('city_name')->where('status', 1)->get();
        $selectFields = ['id', 'job_company_id', 'sub_category_id', 'lang', 'job_title', 'slug', 'company', 'status',
            'featured', 'is_popular', 'min_salary', 'max_salary', 'views', 'experience', 'location',
            'created_at'
            ];
        $jobs = Job::with('sub_category', 'jobCompany')->select($selectFields)->active();
        $job_category = Job::where('status', '=', '1')
            ->select('job_title', 'sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id");

        $featured = Job::with('sub_category', 'jobCompany')->select($selectFields)->featured()->active();
        $popular = Job::with('sub_category', 'jobCompany')->select($selectFields)->popular()->active();

        if (!empty($request)) {

            if (!empty($request->quick_search)) {
                $search = $request->quick_search;
                $main_ids = MainCategory::where('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();
                $sub_ids = SubCategory::where('name', 'LIKE', '%' . $search . '%')->orWhere('main_category_id', $main_ids)->pluck('id')->toArray();
                $jobs = $jobs->where('job_title', 'LIKE', '%' . $search . '%');
                // $featured = [];
            }

            if (!empty($request->name)) {
                $attribute = $request->name;
                $jobs = $jobs->filter(function ($item) use ($attribute) {
                    return strpos($item->job_title, $attribute) !== false;
                });

                $job_category = $job_category->filter(function ($item) use ($attribute) {
                    return strpos($item->job_title, $attribute) !== false;
                });
                // $featured = [];
            }

            if (!empty($salary) && $salary !== 'all') {
                if(Str::contains($salary, '-')) {
                    $salaryRange = explode('-', $salary);
                    $jobs = $jobs->where('min_salary', '>=', $salaryRange[0])->where('max_salary', '<=', $salaryRange[1]);
                    $job_category = $job_category->where('min_salary', '>=', $salaryRange[0])->where('max_salary', '<=', $salaryRange[1]);
                } else {
                    $jobs = $jobs->where('min_salary', '>=', $salary);
                    $job_category = $job_category->where('min_salary', '>=', $salary);
                }
            }

            if (!empty($job_type) && $job_type !== 'all') {

                if($job_type === "remote") {
                    $jobs = $jobs->where('is_remote', 1);
                    $job_category = $job_category->where('is_remote', 1);
                } else {
                    $jobs = $jobs->where('job_type', '=', $job_type);
                    $job_category = $job_category->where('job_type', '=', $job_type);
                }
            }
            if (!empty($experience) && $experience !== 'all') {
                if(Str::contains($experience, '-')) {
                    $expRange = explode('-', $experience);
                    $jobs = $jobs->where('experience', '>=', $expRange[0])->where('experience', '<=', $expRange[1]);
                    $job_category = $job_category->where('experience', '>=', $expRange[0])->where('experience', '<=', $expRange[1]);
                } else {
                    $jobs = $jobs->where('experience', '>=', 10);
                    $job_category = $job_category->where('experience', '>=', 10);
                }
            }

            if ($request->main_cat != "all" && isset($request->main_cat)) {
                $sub_cat_ids = SubCategory::where('main_category_id', '=', $request->main_cat)->pluck('id')->toArray();
                $jobs = $jobs->whereIn('sub_category_id', $sub_cat_ids);
                $job_category = $job_category->whereIn('sub_category_id', $sub_cat_ids);
                // $featured = [];
            }

            if ($request->sub_category != "all" && isset($request->sub_category)) {
                $jobs = $jobs->where('sub_category_id', $request->sub_category);
                $job_category = $job_category->where('sub_category_id', $request->sub_category);
                // $featured = [];
            }

            if ($request->sub_category_id) {
                $jobs = $jobs->where('sub_category_id', $request->sub_category_id);
                $job_category = $job_category->where('sub_category_id', $request->sub_category_id);
            }

            if (!empty($location_search) && $location_search !== 'all') {
                $jobs = $jobs->where('city', $location_search);
                $job_category = $job_category->where('city', $location_search);
                // $featured = [];
            }

            if ($request->city != "all" && !empty($request->city)) {
                $city_ids = City::where('state_id', $request->city)->pluck('id')->toArray();
                $jobs = $jobs->whereIn('city_id', $city_ids);
                $job_category = $job_category->whereIn('city_id', $city_ids);
                // $featured = [];
            }


            if (!empty($request->sort_by)) {

                if ($request->sort_by == "1") {
                    $jobs = $jobs->orderBy('created_at', 'desc');
                    $job_category = $job_category->orderBy('created_at', 'desc');
                }
                if ($request->sort_by == "2") {
                    $jobs = $jobs->orderBy('created_at', 'asc');
                    $job_category = $job_category->orderBy('created_at', 'asc');
                }
                if ($request->sort_by == "3") {
                    $jobs = $jobs->orderBy('job_title', 'asc');
                    $job_category = $job_category->orderBy('job_title', 'desc');
                }
                if ($request->sort_by == "4") {
                    $jobs = $jobs->orderBy('job_title', 'desc');
                    $job_category = $job_category->orderBy('job_title', 'desc');
                }
            } else {
                $jobs = $jobs->orderBy('created_at', 'desc');
                $job_category = $job_category->orderBy('created_at', 'desc');
            }
        } else {
            $jobs = $jobs->orderBy('created_at', 'desc');
            $job_category = $job_category->orderBy('created_at', 'desc');
        }
        //$jobs = $jobs->get();
        $total_count = 0;
        if (!empty($jobs)) {
            $total_count = $jobs->count();
        }
        $featured = $featured->get();
        $popular = $popular->get();
        $result_array = array(
            'jobs' => $jobs->paginate($perPage),
            'total_count' => $total_count,
            'featured' => $featured,
            'job_category' => $job_category,
            'popular' => $popular,
            'nextPageUrl' => $nextPageUrl,
        );
        return $result_array;
    }

    public function search_job_name_ajax(Request $request)
    {
        $search = $request->keyword;
        $jobs = array();
        if (!empty($search)) {
            $jobs = Job::select('job_title', 'id')->where('job_title', 'LIKE', '%' . $search . '%')->orderby('job_title')->limit(6)->get();
        }
        $view = view('user.jobs.autocomplete_job_ajax', compact('jobs'))->render();
        return response()->json(['html' => $view]);
    }

    public function jobsMore(Request $request, $slug)
    {
        $featured = Job::with('jobCompany')->where('slug', '=', $slug)->first();
        // $data = Job::where('slug','=',$slug)->first();

        $job = Job::where('slug', '=', $slug)->first();
        abort_if(!$job, 404);
        // $similar_job=Job::where()

        if ($job->lat && $job->long) {
            $nearby = Job::select("jobs.id", DB::raw("6371 * acos(cos(radians(" . $job->lat . "))
                * cos(radians(jobs.lat))
                * cos(radians(jobs.long) - radians(" . $job->long . "))
                + sin(radians(" . $job->lat . "))
                * sin(radians(jobs.lat))) AS distance"), "jobs.*")
                // ->with('subCategory')
                ->groupBy("jobs.id")
                ->orderBy('distance', 'ASC')
                ->get();
        } else {
            $nearby = [];
        }

        $nations = Nationality::all();

        $count = $job->views + 1;
        $job->views = $count;
        $job->timestamps = false;
        $job->save();
        // return $job;
        //    dd()

        $similar_jobs = Job::where('job_title', 'LIKE', '%' . $job->job_title . '%')
            ->where('slug', '!=', $slug)->get(); //return $similar_jobs;

        $jobs = Job::with('sub_category')->where('status', '=', '1')->get(); //return $jobs;
        $justJoin = $jobs->take($request->get('limit', 10))->sortByDesc('created_at');

        $categoryForSidebar = MainCategory::with('subCategory.job')->withCount('job')->where('major_category_id', 7)->take(10)->get();

        $totalJobApplied = JobApplied::where('job_id', $job->id)->count();

        $jobCompany = $job->jobCompany;
        $sociaLinks = json_decode($jobCompany->social_links, true);
        //var_dump($jobCompany->social_links);
        // $justJoin = [];
        return view('user.jobs.jobs-more', get_defined_vars());
    }

    public function jobSort(Request $request)
    {
        if ($request->sort_by) {
            $featured = Job::where('status', '1')->where('featured', '1');
            if ($request->sort_by == '1') {
                $featured = $featured->orderBy('created_at', 'ASC')->get();
            } elseif ($request->sort_by == '2') {
                $featured = $featured->orderBy('created_at', 'DESC')->get();
            } elseif ($request->sort_by == '3') {
                $featured = $featured->orderBy('job_title', 'ASC')->get();
            } elseif ($request->sort_by == '4') {
                $featured = $featured->orderBy('job_title', 'DESC')->get();
            } else {
                $featured = $featured->get();
            }

            $jobs = Job::where('status', '1');
            if ($request->sort_by == '1') {
                $jobs = $jobs->orderBy('created_at', 'ASC')->get();
            } elseif ($request->sort_by == '2') {
                $jobs = $jobs->orderBy('created_at', 'DESC')->get();
            } elseif ($request->sort_by == '3') {
                $jobs = $jobs->orderBy('job_title', 'ASC')->get();
            } elseif ($request->sort_by == '4') {
                $jobs = $jobs->orderBy('job_title', 'DESC')->get();
            } else {
                $jobs = $jobs->get();
            }

            $dynamic_mains = DynamicMainCategory::where('major_category_id', '=', '7')->where('status', 1)->get();
            $banner = SliderImage::where('major_category_id', 7)->get();
            $major_category = MajorCategory::find(7);
            $states = State::all();
            $categories = MainCategory::where('major_category_id', 7)->get();
            $hot_trends = News::orderBy('created_at', 'desc')->limit(10)->get();
            $influencer_reviews = InfluencerReview::where('status', 1)->get();
            $locations = City::all();
            $main_category = MainCategory::where('major_category_id', '=', '7')->get();
            $categoryForSidebar = MainCategory::with('subCategory.job')->withCount('job')->where('major_category_id', 7)->orderBy('job_count', 'DESC')->take(10)->get();
            $sub_cat_search = array();
            if (isset($_GET['sub_category'])) {
                if ($_GET['sub_category'] != "all") {
                    $sub_cat_search = SubCategory::where('main_category_id', '=', $_GET['main_cat'])->get();
                }
            }

            return view('user.jobs.index', compact('featured', 'banner', 'major_category', 'jobs', 'locations', 'dynamic_mains', 'main_category', 'sub_cat_search', 'states', 'categories', 'hot_trends', 'influencer_reviews', 'categoryForSidebar'));
        }
    }

    public function applyJobForm(Request $request, Job $job)
    {
        $nations = Nationality::all();
        //$job = Job::where('slug', '!=', $slug)->first();
        abort_if(!$job, 404);
        return view('user.common.apply-job-form', get_defined_vars());
    }

    public function jobsByType(Request $request, $type)
    {
        $main_category = MainCategory::where('major_category_id', 7)->pluck('id')->toArray();
        $cat_details = DynamicLink::where('major_category_id', 7)->where('slug', $type)->first();
        $dynamic_links = DynamicLink::where('major_category_id', 7)->pluck('link_title', 'slug')->toArray();
        $quick_search = $request->quick_search ?? '';
        $type = $request->type ?? $type;
        $sort_by = $request->sort_by ?? 1;
        $pageNo = $request->get('page', 1);
        $perPage = 10;
        if($cat_details->related_items) {
            $totalItems = Job::select(['id'])->active()->whereIn('id', $cat_details->related_items);
        } else {
            $totalItems = Job::select(['id'])->active();
        }
        $totalItems = $totalItems->count();
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $totalItems = $totalItems->{$scope}()->count();
        } else {
            $totalItems = $totalItems->count();
        }*/

        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/jobs/view-listings/".$type."?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
            $nextPageUrl .= "&sort_by=".$sort_by;
        }
        else {
            $nextPageUrl = '';
        }

        $jobs = Job::with('subCategory', 'jobCompany', 'approvedReviews')->active();
        if($cat_details->related_items) {
            $jobs = $jobs->whereIn('id', $cat_details->related_items);
        }
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $jobs = $jobs->{$scope}();
        }*/
        if (isset($request)) {
            if (!empty($quick_search)) {
                $search = $quick_search;
                $jobs = $jobs->where(function ($query) use ($search) {
                    $query->orWhere('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%');
                });
            }
            if (!empty($sort_by)) {
                if ($sort_by == "1") {
                    $jobs = $jobs->orderBy('created_at', 'desc');
                } elseif ($sort_by == "2") { //oldest to newest
                    $jobs = $jobs->orderBy('created_at', 'asc');
                } elseif ($sort_by == "3") { // A to Z
                    $jobs = $jobs->orderBy('title', 'asc');
                } elseif ($sort_by == "4") { // Z to A
                    $jobs = $jobs->orderBy('title', 'desc');
                }
            } else {
                $jobs = $jobs->orderBy('created_at', 'desc');
            }
        }

        //dd($jobs->toSql());
        $total_count = 0;
        if (isset($jobs)) {
            $total_count = $jobs->count();
        }
        $jobs = $jobs->paginate(10);
        //dd($jobs->toSql(), $total_count);
        if ($request->ajax()) {
            //dd($jobs->toSql(), $total_count);
            return $view = view('user.jobs.list', get_defined_vars())->render();
        }

        return view('user.common.listings-by-type', get_defined_vars());
    }
}
