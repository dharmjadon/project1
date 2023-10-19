<?php
namespace App\Http\Controllers\User;

use App\Models\City;
use App\Models\DynamicLink;
use App\Models\News;
use App\Models\Recommendation;
use App\Models\State;
use App\Models\Venue;
use App\Models\Education;
use App\Models\MoreInfo;
use App\Models\AlertNews;
use App\Models\EnquireForm;
use App\Models\SliderImage;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\MajorCategory;
use App\Models\HomeTrendBanner;
use App\Models\EducationReservation;
use App\Models\InfluencerReview;
use App\Mail\ConciergeEnquiryMail;
use App\Models\DynamicSubCategory;
use App\Models\ItemRecommendation;
use Illuminate\Support\Facades\DB;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class EducationController extends Controller
{
    public function education(Request $request, $category_slug = '')
    {
        $all_main_category = MainCategory::where('major_category_id', 14)->pluck('id')->toArray();
        $dynamic_sub_categories = DynamicSubCategory::all();
        $dynamic_categories = DynamicMainCategory::where('major_category_id', '=', '14')->get();
        $cities = Education::where('city', '!=', '')->pluck('city', 'city')->toArray();
        $states = Education::where('area', '!=', '')->pluck('area', 'area')->toArray();

        $result_array = $this->search_education($request, $category_slug);

        $all_educations = $result_array['all_educations'];
        $total_count = $result_array['total_count'];
        $education_category = $result_array['education_category'];
        $feature_educations = $result_array['feature_educations'];
        $popular_educations = $result_array['popular_educations'];
        $nextPageUrl = $result_array['nextPageUrl'];

        $justJoin = $all_educations->take($request->get('limit', 10))->sortByDesc('created_at');


        $main_category = MainCategory::where('major_category_id', 14)->get();
        $banner = SliderImage::where('major_category_id', 14)->get();
        $major_category = MajorCategory::find(14);
        $major_category->load(['searchLinksTop', 'searchLinksBottom', 'statistics', 'bannerLinksTop', 'bannerLinksRight', 'bannerLinksBottom', 'bannerLinksLeft']);
        $categories = MainCategory::where('major_category_id', 14)->withCount('education')->get();
        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();
        $categoryForSidebar = MainCategory::with('subCategory.education')->withCount('education')->where('major_category_id', 14)->orderBy('education_count', 'DESC')->get();
        $allCategory = MajorCategory::with(['mainCategory', 'mainCategory.subCategory']);

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
            return $view = view('user.education.list', get_defined_vars())->render();
        }

        if(!empty($category_slug) || !empty($request->main_cat)) {
            if($sub_category_id) {
                $cat_details = SubCategory::find($sub_category_id);
            } else if(!empty($main_cat) && $main_cat != 'all') {
                $cat_details = MainCategory::find($main_cat);
            } else {
                $cat_details = null;
            }
            return view('user.education.education-listing', get_defined_vars());
        }
        $dynamic_links = DynamicLink::where('major_category_id', 14)->whereNotIn('slug', ['popular-educations', 'trending-educations', 'hot-educations'])->get();
        $popular_links = $major_category->popularDynamicLinks()->first();
        $trending_links = $major_category->trendingDynamicLinks()->first();
        $hot_links = $major_category->hotDynamicLinks()->first();
        $top_searches = MainCategory::with('topSearch')
            ->withCount('topSearch')
            ->where('major_category_id', 14)
            ->orderBy('top_search_count', 'DESC')
            ->get();
        $popular_searches = MainCategory::with('popularSearch')
            ->withCount('popularSearch')
            ->where('major_category_id', 14)
            ->orderBy('popular_search_count', 'DESC')
            ->get();
        return view('user.education.index', get_defined_vars());
    }

    public function search_education($request, $category_slug)
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
        $pageNo = $request->get('page', 1);
        $perPage = 10;
        $totalItems = Education::select(['id'])->active()->count();
        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/educations?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
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
        $all_educations = Education::with(['get_subcat', 'featureImage'])->where('status', 1);
        $education_category = Education::with(['get_subcat'])->where('status', 1)
            ->select('title', 'sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        $feature_educations = Education::with(['get_subcat', 'featureImage'])->where('status', 1)->where('is_featured', 1);
        $popular_educations = Education::with(['get_subcat', 'featureImage', 'city'])->where('status', 1)->where('is_popular', 1);



        if (isset($request)) {
            if (isset($request->sort_by)) {
                if ($request->sort_by == "1") {  // newest to oldest
                    $all_educations = $all_educations->orderBy('created_at', 'desc');
                    $feature_educations = $feature_educations ? $feature_educations->orderBy('created_at', 'desc') : null;
                    $popular_educations = $popular_educations ? $popular_educations->orderBy('created_at', 'desc') : null;
                } elseif ($request->sort_by == "14") { //oldest to newest
                    $all_educations = $all_educations->orderBy('created_at', 'asc');
                    $feature_educations = $feature_educations ? $feature_educations->orderBy('created_at', 'asc') : null;
                    $popular_educations = $popular_educations ? $popular_educations->orderBy('created_at', 'asc') : null;
                } elseif ($request->sort_by == "3") { // A to Z
                    $all_educations = $all_educations->orderBy('title', 'asc');
                    $feature_educations = $feature_educations ? $feature_educations->orderBy('title', 'asc') : null;
                    $popular_educations = $popular_educations ? $popular_educations->orderBy('title', 'asc') : null;
                } elseif ($request->sort_by == "4") {  // Z to A
                    $all_educations = $all_educations->orderBy('title', 'desc');
                    $feature_educations = $feature_educations ? $feature_educations->orderBy('title', 'desc') : null;
                    $popular_educations = $popular_educations ? $popular_educations->orderBy('title', 'desc') : null;
                }
            } else {
                $all_educations = $all_educations->orderBy('created_at', 'desc');
                $feature_educations = $feature_educations ? $feature_educations->orderBy('created_at', 'desc') : null;
                $popular_educations = $popular_educations ? $popular_educations->orderBy('created_at', 'desc') : null;
            }

            if (isset($request->quick_search)) {   //quick search

                $search = $request->quick_search;
                $main_cat_ids = MainCategory::where('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();
                $sub_cat_ids = SubCategory::where('name', 'LIKE', '%' . $search . '%')
                    ->orWhereIn('main_category_id', $main_cat_ids)
                    ->pluck('id')
                    ->toArray();
                $all_educations = Education::with('get_subcat', 'featureImage')
                    ->where(function ($query) use ($search, $sub_cat_ids) {
                        $query->orWhere('title', 'LIKE', '%' . $search . '%')
                            ->orWhere('description', 'LIKE', '%' . $search . '%')
                            ->orWhereIn('sub_category_id', $sub_cat_ids);
                    })
                    ->where('status', 1);
            }

            if (isset($request->education_name)) {

                $attribute = $request->education_name;
                $all_educations = $all_educations->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });
                $education_category = $education_category->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });
            }

            if ($request->main_cat != "all" && isset($request->main_cat)) { //main cat

                $sub_cat_ids = SubCategory::where('main_category_id', '=', $request->main_cat)->pluck('id')->toArray();
                $all_educations = $all_educations->whereIn('sub_category_id', $sub_cat_ids);
                $education_category = $education_category->whereIn('sub_category_id', $sub_cat_ids);
            }

            if ($request->sub_category != "all" && isset($request->sub_category)) {  // sub cat

                $all_educations = $all_educations->where('sub_category_id', $request->sub_category);
                $education_category = $education_category->whereIn('sub_category_id', $request->sub_category);
            }

            if ($request->location != "all" && isset($request->location)) { //location means city id

                $all_educations = $all_educations->where('city', $request->location);
                $education_category = $education_category->where('city_id', $request->location);
            }

            if ($request->city != "all" && isset($request->city)) {
                $city_ids = City::where('state_id', $request->city)->pluck('id')->toArray();
                $all_educations = $all_educations->whereIn('city_id', $city_ids);
                $education_category = $education_category->where('city_id', $city_ids);
            }

            if (isset($request->sub_category_id)) {

                $all_educations = $all_educations->where('sub_category_id', $request->sub_category_id);
            }

            if (isset($request->sub_cate_id)) {  //education name

                $attribute = $request->sub_cate_id;
                $all_educations = $all_educations->filter(function ($item) use ($attribute) {
                    return strpos($item->sub_category_id, $attribute) !== false;
                });
            }
            if (isset($request->date_from)) {
                $all_educations = $all_educations->where('date_time', '>', $request->date_from . " 00:00:00");
            }

            if (isset($request->date_to)) {
                $all_educations = $all_educations->where('date_time', '<=', $request->date_to . " 23:59:59");
            }
        }

        $feature_educations = $feature_educations->get();
        $popular_educations = $popular_educations->get();

        $total_count = 0;
        if (isset($all_educations)) {
            $total_count = $all_educations->count();
        }
        $result_array = array(
            'all_educations' => $all_educations->paginate($perPage),
            'total_count' => $total_count,
            'education_category' => $education_category,
            'feature_educations' => $feature_educations,
            'popular_educations' => $popular_educations,
            'nextPageUrl' => $nextPageUrl,
        );
        return $result_array;
    }

    public function education_detail($slug)
    {
        $education = Education::where('slug', '=', $slug)->first();
        $education->load(['approvedReviews', 'featureImage', 'logoImage', 'floorPlanImage', 'menuImage', 'mainImage', 'mainImages', 'storyImages']);
        $data = $education;
        $type = 'education';
        $more_info = NULL;
        if (isset($data->id)) {
            $more_info = MoreInfo::where(['module_id' => $data->id, 'module_name' => 'education'])->get();
        }
        $similar = Education::with('get_subcat','featureImage', 'approvedReviews')->where('status', 1)->where('sub_category_id', $education->sub_category_id)->where('id', '!=', $education->id)->get();
        $popular = Education::with('get_subcat','featureImage', 'approvedReviews')->where('status', 1)->where('is_popular', 1)->get();
        $education_category = Education::where('status', '=', '1')
            ->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $education->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        // avg rating
        //$reviews = Education::where('slug', $slug)->first();
        $avg_rating = round($education->approvedreviews()->avg('rating'), 1);

        $amenties_education = $education->amenties ?? [];
        $landmark_education = $education->landmarks ?? [];
        //dd($landmark_education);
        if ($education->lat && $education->lng) {
            $nearby = Education::with(['featureImage', 'approvedReviews'])->select(
                    "educations.id",
                    DB::raw("6371 * acos(cos(radians(" . $education->lat . "))
                * cos(radians(educations.lat))
                * cos(radians(educations.lng) - radians(" . $education->lng . "))
                + sin(radians(" . $education->lat . "))
                * sin(radians(educations.lat))) AS distance"),
                    "educations.*",
                    "sub_categories.name as sub_category"
                )
                // ->with('subCategory')
                ->join('sub_categories', 'sub_categories.id', 'educations.sub_category_id')
                ->where('educations.status', 1)
                ->groupBy("educations.id")
                ->orderBy('distance', 'ASC')
                ->get();
        } else {
            $nearby = [];
        }

        $shareIcons = createSocialShareIcons($education, 'education');
        $recommended = Recommendation::where(['module_type' => 'education', 'module_id' => $education->id])->count();

        $count = $education->views + 1;
        $education->views = $count;
        $education->timestamps = false;
        $education->save();

        $alert = AlertNews::first();
        $firstStoryImage = $education->storyImages()->first();
        $whatsapp = MajorCategory::where('id', 14)->first();
        $breadcrumbUrl = route('education-list', ['category_slug' => $education->subCategory->mainCategory->slug]);
        if($education->mainImage) {
            $og_image = $education-> getStoredImage($education->mainImage->image, 'main_image');
        } else if($education->featureImage) {
            $og_image = $education-> getStoredImage($education->featureImage->image, 'feature_image');
        } else {
            $og_image = '/v2/images/image-placeholder.jpeg';
        }
        return view('user.education.education-detail', get_defined_vars());
        /*return view('user.education.education-detail',compact('education','four_images','amenties_education','landmark_education','similar','education_category',
            'nearby', 'avg_rating','youtube','alert', 'whatsapp', 'recommended'));*/
    }

    public function educationsByType(Request $request, $type)
    {
        $main_category = MainCategory::where('major_category_id', 14)->pluck('id')->toArray();
        $cat_details = DynamicLink::where('major_category_id', 14)->where('slug', $type)->first();
        $dynamic_links = DynamicLink::where('major_category_id', 14)->pluck('link_title', 'slug')->toArray();
        $quick_search = $request->quick_search ?? '';
        $type = $request->type ?? $type;
        $sort_by = $request->sort_by ?? 1;
        $pageNo = $request->get('page', 1);
        $perPage = 10;
        if($cat_details->related_items) {
            $totalItems = Education::select(['id'])->active()->whereIn('id', $cat_details->related_items);
        } else {
            $totalItems = Education::select(['id'])->active();
        }
        $totalItems = $totalItems->count();
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $totalItems = $totalItems->{$scope}()->count();
        } else {
            $totalItems = $totalItems->count();
        }*/

        if($pageNo < ceil($totalItems/$perPage)) {
            $nextPageUrl = "/educations/view-listings/".$type."?page=".($pageNo + 1)."&quick_search=".urlencode($quick_search);
            $nextPageUrl .= "&sort_by=".$sort_by;
        }
        else {
            $nextPageUrl = '';
        }

        $all_educations = Education::with('get_subcat', 'featureImage', 'approvedReviews')->active();
        if($cat_details->related_items) {
            $all_educations = $all_educations->whereIn('id', $cat_details->related_items);
        }
        /*if($type === 'popular-venues' || $type === 'trending-venues' || $type === 'hot-venues') {
            $scope = str_replace('-venues', '', $type);
            $all_educations = $all_educations->{$scope}();
        }*/
        if (isset($request)) {
            if (!empty($quick_search)) {
                $search = $quick_search;
                $all_educations = $all_educations->where(function ($query) use ($search) {
                    $query->orWhere('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%');
                });
            }
            if (!empty($sort_by)) {
                if ($sort_by == "1") {
                    $all_educations = $all_educations->orderBy('created_at', 'desc');
                } elseif ($sort_by == "2") { //oldest to newest
                    $all_educations = $all_educations->orderBy('created_at', 'asc');
                } elseif ($sort_by == "3") { // A to Z
                    $all_educations = $all_educations->orderBy('title', 'asc');
                } elseif ($sort_by == "4") { // Z to A
                    $all_educations = $all_educations->orderBy('title', 'desc');
                }
            } else {
                $all_educations = $all_educations->orderBy('created_at', 'desc');
            }
        }

        //dd($all_educations->toSql());
        $total_count = 0;
        if (isset($all_educations)) {
            $total_count = $all_educations->count();
        }
        $all_educations = $all_educations->paginate(10);
        //dd($all_educations->toSql(), $total_count);
        if ($request->ajax()) {
            //dd($all_educations->toSql(), $total_count);
            return $view = view('user.education.list', get_defined_vars())->render();
        }

        return view('user.common.listings-by-type', get_defined_vars());
    }


    function search_education_name_ajax(Request $request)
    {

        $search = $request->keyword;

        $educations = array();
        if (!empty($search)) {
            $educations = Education::select('title', 'id')->where('title', 'LIKE', '%' . $search . '%')->orderby('title')->limit(6)->get();
        }


        $view = view('user.education.ajax.autocomplete_education_ajax', compact('educations'))->render();

        return response()->json(['html' => $view]);
    }

    public function educationSort($request)
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


        // $result_array =  $this->search_education($request);


        // $all_educations =   $result_array['all_educations'];
        // $total_count =  $result_array['total_count'];
        // $education_category = $result_array['education_category'];
        // $feature_educations = $result_array['feature_educations'];


        // $sub_cat_search = array();

        // if(isset($_GET['sub_category'])){
        //     if($_GET['sub_category'] != "all"){
        //         $sub_cat_search = SubCategory::where('main_category_id','=',$_GET['main_cat'])->get();
        //     }
        // }

        // $main_category = MainCategory::where('major_category_id','=','2')->get();
        // $dynamic_mains = DynamicMainCategory::where('major_category_id','=','2')->get();

        // return view('user.education.education',compact('cities','dynamic_categories', 'dynamic_mains','sub_cat_search','dynamic_sub_categories','main_category','feature_educations','all_educations','total_count','education_category'));;
        // }
    }

    public function educationAjaxReservation(Request $request)
    {

        $date = date_create($request->date);
        $date = date_format($date, "Y-m-d H:i");


        $enquire = new EnquireForm(['name' => $request->name, 'email' => $request->email, 'mobile' => $request->mobile, 'message' => $request->message1]);
        $type = Education::find($request->item_id);
        $email = $type->email;
        $type->enquiries()->save($enquire);
        $obj = new EducationReservation();
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
        $obj->education_id = $request->education_id;
        $obj->booking_type = $request->booking_type;
        $obj->save();

        $subject = "Education Enquiry Mail";

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
