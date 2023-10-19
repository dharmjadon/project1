<?php

namespace App\Http\Controllers\User;

use DB;
use App\Models\City;
use App\Models\News;
use App\Models\State;
use App\Models\Concierge;
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
use App\Models\DynamicMainCategory;
use App\Models\ConciergeReservation;
use App\Http\Controllers\Controller;
use App\Models\HomeTrendBanner;

class ConciergeController extends Controller
{
    public function index(Request $request)
    {
        $book_table = MainCategory::where('major_category_id', '=', '5')->where('name', 'Like', '%Book a Table%')->first();

        $book_table_id = $book_table->id ?? '';

        $result_array = $this->search_result($request);
        $concierges = $result_array['concierges'];
        $concierge_category = $result_array['concierge_category'];
        $featured = $result_array['featured'];
        $main_category = MainCategory::where('major_category_id', '=', '5')->get();
        $sub_cat_search = array();

        if (isset($_GET['sub_category'])) {
            if ($_GET['sub_category'] != "all") {
                $sub_cat_search = SubCategory::where('main_category_id', '=', $_GET['main_cat'])->get();
            }
        }

        $dynamic_mains = DynamicMainCategory::where('major_category_id', 5)->where('status', 1)->get();
        $banner = SliderImage::where('major_category_id', 5)->get();
        $states = State::all();
        $major_category = MajorCategory::find(5);
        $categories = MainCategory::where('major_category_id', 5)->get();
        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        $influencer_reviews = InfluencerReview::where('status',1)->limit(3)->get();
        $categoryForSidebar = MainCategory::with('subCategory.concierge')->withCount('concierge')->where('major_category_id', 5)->orderBy('concierge_count', 'DESC')->take(10)->get();
        $allCategory = MajorCategory::with(['mainCategory','mainCategory.subCategory']);
        $home_trend = HomeTrendBanner::all();

        return view('user.concierge.index', compact('featured', 'banner', 'major_category', 'concierges', 'concierge_category', 'main_category', 'sub_cat_search', 'dynamic_mains', 'book_table_id', 'states','categories','hot_trends' ,'influencer_reviews', 'categoryForSidebar', 'allCategory', 'home_trend'));
    }

    public function search_result($request)
    {
        $result_array = array();
        $concierges = Concierge::with('subCategory')->where('status', 1)->where('is_vip', 1);
        $concierge_category = Concierge::where('status', '=', '1')->where('is_vip', 1)->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")->get();
        $featured = Concierge::with('subCategory')->where('featured', 1)->where('is_vip', 1)->where('status', 1)->get();

        if (isset($request)) {

            if ($request->sort) {
                $res = '1';
                $sort_by = $request->sort_by;

                $featured = Concierge::with('subCategory')->where('featured', 1)->where('status', 1)->where('is_vip', 1);
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

                $datas = Concierge::with('subCategory')->where('status', 1)->where('is_vip', 1);
                if ($sort_by == '1') {
                    $datas = $datas->orderBy('created_at', 'desc');
                } elseif ($sort_by == '2') {
                    $datas = $datas->orderBy('created_at', 'asc');
                } elseif ($sort_by == '3') {
                    $datas = $datas->orderBy('title', 'asc');
                } elseif ($sort_by == '4') {
                    $datas = $datas->orderBy('title', 'desc');
                }
                $concierges = $datas->get();
            }

            if (isset($request->quick_search)) {

                $search = $request->quick_search;
                $main_ids = MainCategory::where('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();
                $sub_ids = SubCategory::where('name', 'LIKE', '%' . $search . '%')->orWhere('main_category_id', $main_ids)->pluck('id')->toArray();
                $concierges = Concierge::with('subCategory')
                    ->where(function ($q) use ($search, $sub_ids) {
                        $q->orWhere('title', 'LIKE', '%' . $search . '%')->orWhere('description', 'LIKE', '%' . $search . '%')
                            ->orWhereIn('sub_category_id', $sub_ids);
                    })->where('status', 1)->where('is_vip', 1);
                $featured = [];
            }

            if (isset($request->name)) {

                $attribute = $request->name;
                $concierges = $concierges->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });
                $featured = [];
            }

            if ($request->main_cat != "all" && isset($request->main_cat)) {

                $sub_cat_ids = SubCategory::where('main_category_id', '=', $request->main_cat)->pluck('id')->toArray();
                $concierges =  $concierges->whereIn('sub_category_id', $sub_cat_ids);
                $concierge_category =  $concierge_category->whereIn('sub_category_id', $sub_cat_ids);
                $featured = [];
            }

            if ($request->sub_category != "all" && isset($request->sub_category)) {

                $concierges =  $concierges->where('sub_category_id', $request->sub_category);
                $concierge_category =  $concierge_category->whereIn('sub_category_id', $request->sub_category);
                $featured = [];
            }

            if($request->sub_category_id){
                $concierges =  $concierges->where('sub_category_id', $request->sub_category_id);
            }


            if($request->city!="all" && isset($request->city)){
                $city_ids = City::where('state_id', $request->city)->pluck('id')->toArray();
                $concierges =  $concierges->whereIn('city_id',$city_ids);
                $concierge_category =  $concierge_category->where('city_id',$city_ids);
                $featured = [];
             }
            if ($request->dynamic_subs == "one") {
                $ids = [];
                foreach ($request->dynamic_sub as $sub) {
                    foreach ($concierges as $row) {
                        if (isset($row->dynamic_sub_ids)) {
                            $abc = (in_array($sub, json_decode($row->dynamic_sub_ids))) ? $ids[] = $row->id : '';
                        }
                    }
                }
                $concierges =  $concierges->whereIn('id', $ids);
                $featured = [];
            }

            if(isset($request->sort_by)){
                if($request->sort_by == "1"){
                    $concierges =  $concierges->orderBy('created_at', 'desc');
                }
                if($request->sort_by == "2"){
                    $concierges =  $concierges->orderBy('created_at', 'asc');
                }
                if($request->sort_by == "3"){
                    $concierges =  $concierges->orderBy('title', 'asc');
                }
                if($request->sort_by == "4"){
                    $concierges =  $concierges->orderBy('title', 'desc');
                }
            }

            if (isset($request->sub_cate_id)) {

                $attribute = $request->sub_cate_id;
                $concierges =  $concierges->filter(function ($item) use ($attribute) {
                    return strpos($item->sub_category_id, $attribute) !== false;
                });
                $featured = [];
            }
        }

        $concierges =  $concierges->get();

        $result_array = array(
            'concierges' => $concierges,
            'concierge_category' => $concierge_category,
            'featured' => $featured,
        );
        return $result_array;
    }

    function search_concierge_name_ajax(Request $request)
    {
        $search = $request->keyword;
        $concierges = array();
        if (!empty($search)) {
            $concierges = Concierge::select('title', 'id')->where('title', 'LIKE', '%' . $search . '%')->where('is_vip', 1)->orderby('title')->limit(6)->get();
        }
        $view = view('user.concierge.autocomplete_concierge_ajax', compact('concierges'))->render();
        return response()->json(['html' => $view]);
    }
    function vipConcierge()
    {
        $concierges = Concierge::where('is_vip', 2)->where('status', 1)->get();
        return view('user.concierge.vip-concierge', compact('concierges'));
    }

    public function conciergeMore($slug)
    {
        $data = Concierge::with('subCategory')->where('slug', $slug)->where('is_vip', 1)->first();

        $type='concierge';
        $more_info=NULL;
        if(isset($data->id))
        {
          $more_info=MoreInfo::where(['module_id'=>$data->id,'module_name'=>'concierge'])->get();
        }
        $concierges = Concierge::where('is_vip', 1)->get();
        $similar = Concierge::with('subCategory', 'city')->where('sub_category_id', $data->sub_category_id)->where('is_vip', 1)->get();
        if ($data->lat && $data->long) {
            $nearby = DB::table("concierges")
                ->select(
                    "concierges.id",
                    DB::raw("6371 * acos(cos(radians(" . $data->lat . "))
                * cos(radians(concierges.lat))
                * cos(radians(concierges.long) - radians(" . $data->long . "))
                + sin(radians(" . $data->lat . "))
                * sin(radians(concierges.lat))) AS distance"),
                    "concierges.*",
                    "sub_categories.name as sub_category",
                    "sub_categories.id as sub_category_id"
                )
                // ->with('subCategory')
                ->join('sub_categories', 'sub_categories.id', 'concierges.sub_category_id')
                ->where('is_vip', 1)
                ->groupBy("concierges.id")
                ->orderBy('distance', 'ASC')
                ->get();
        } else {
            $nearby = [];
        }
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }

        $avg_rating = round($data->approvedreviews()->avg('rating'), 1);

        $recommended = ItemRecommendation::whereHasMorph('item', [Concierge::class])->where('status', 1)->get();

        $count = $data->views + 1;
        $data->views= $count;
        $data->timestamps = false;
        $data->save();

        $whatsapp = MajorCategory::where('id', 5)->first();


        return view('user.concierge.concierge-more-two',get_defined_vars());
        /*return view('user.concierge.concierge-more-two', compact('data', 'concierges', 'nearby', 'similar','youtube', 'whatsapp', 'recommended', 'avg_rating'));*/
    }

    public function conciergeSearch(Request $request)
    {
        if ($request->sort) {
            $res = '1';
            $sort_by = $request->sort_by;

            $featured = Concierge::with('subCategory')->where('featured', 1)->where('status', 1)->where('is_vip', 1);
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

            $datas = Concierge::with('subCategory')->where('status', 1)->where('is_vip', 1);
            if ($sort_by == '1') {
                $datas = $datas->orderBy('created_at', 'desc');
            } elseif ($sort_by == '2') {
                $datas = $datas->orderBy('created_at', 'asc');
            } elseif ($sort_by == '3') {
                $datas = $datas->orderBy('title', 'asc');
            } elseif ($sort_by == '4') {
                $datas = $datas->orderBy('title', 'desc');
            }
            $concierges = $datas->get();


            $categories = MainCategory::where('major_category_id', 1)->get();

            $main = MainCategory::where('major_category_id', 1)->pluck('id')->toArray();
            $subs = SubCategory::whereIn('main_category_id', $main)->get();

            $concierge_category = Concierge::where('status', '=', '1')->where('is_vip', 1)
                ->select('sub_category_id', DB::raw('count(*) as total'))
                ->groupBy("sub_category_id")
                ->get();

            $main_category = MainCategory::where('major_category_id', '=', '5')->get();
            $sub_cat_search = array();

            if (isset($_GET['sub_category'])) {
                if ($_GET['sub_category'] != "all") {
                    $sub_cat_search = SubCategory::where('main_category_id', '=', $_GET['main_cat'])->get();
                }
            }

            $dynamic_mains = DynamicMainCategory::where('major_category_id', 5)->where('status', 1)->where('is_vip', 1)->get();
            $banner = SliderImage::where('major_category_id', 5)->first();
            $categoryForSidebar = MainCategory::with('subCategory.concierge')->withCount('concierge')->where('major_category_id', 5)->orderBy('concierge_count', 'DESC')->take(10)->get();

            return view('user.concierge.index', compact('concierges', 'featured','banner', 'concierge_category', 'categories', 'subs', 'sort_by', 'res', 'main_category', 'sub_cat_search', 'dynamic_mains', 'categoryForSidebar'));
            }
        }

        public function ajaxReservation(Request $request){

            $date = date_create($request->date);
            $date = date_format($date,"Y-m-d H:i");

            $obj = new ConciergeReservation();
            $obj->person = $request->person;
            $obj->man = $request->no_man;
            $obj->woman = $request->no_woman;
            $obj->children = $request->no_child;
            $obj->user_id = $request->user_id;
            $obj->name = $request->name;
            $obj->email = $request->email;
            $obj->message = $request->message;
            $obj->mobile_no = $request->mobile_no;
            $obj->booking_date = $date;
            $obj->booking_type = $request->booking_type;
            $obj->concierge_id = $request->concierge_id;
            $obj->save();

            $enquire = new EnquireForm(['name' => $request->name, 'email' =>  $request->email, 'mobile' => $request->mobile, 'message' => $request->message1]);
            $type = Concierge::find($request->item_id);
            $email = $type->email;
            $type->enquiries()->save($enquire);
            $subject = "Concierge Enquiry Mail";

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
