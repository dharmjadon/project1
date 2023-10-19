<?php

namespace App\Http\Controllers\User;

use App\Models\City;
use App\Models\State;
use App\Models\Tickets;
use App\Models\SliderImage;
use App\Models\SubCategory;
use App\Models\MainCategory;
use App\Models\TicketBanner;
use Illuminate\Http\Request;
use App\Models\DynamicMainCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\HomeTrendBanner;
use App\Models\InfluencerReview;
use App\Models\MajorCategory;
use App\Models\News;

class TicketController extends Controller
{
    public function index(Request $request, $category_slug = '')
    {
        $main_category = MainCategory::where('major_category_id', '=', '8')->get();
        $cities = City::all();
        $states = State::all();

        $result_array = $this->search_result($request, $category_slug);
        $tickets = $result_array['tickets'];
        $ticket_category = $result_array['ticket_category'];
        $featured = $result_array['featured'];
        $ticket_count = $result_array['ticket_count'];
        // return $featured;
        $sub_cat_search = array();
        if (isset($_GET['sub_category'])) {
            if ($_GET['sub_category'] != "all") {
                $sub_cat_search = SubCategory::where('main_category_id', '=', $_GET['main_cat'])->get();
            }
        }
        $dynamic_mains = DynamicMainCategory::where('major_category_id', 8)->where('status', 1)->get();
        $banner = TicketBanner::first();
        $banners = SliderImage::where('major_category_id', 8)->first();
        $major_category = MajorCategory::find(8);
        $categories = MainCategory::where('major_category_id', 8)->withCount('ticket')->get();
        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();
        $categoryForSidebar = MainCategory::with('subCategory.ticket')->withCount('ticket')->where('major_category_id', 8)->orderBy('ticket_count', 'DESC')->take(10)->get();
        // return $categories;
        $allCategory = MajorCategory::with(['mainCategory', 'mainCategory.subCategory']);
        $home_trend = HomeTrendBanner::all();

        $allTickets = Tickets::where('status', 1)->get();
        $justJoin = $allTickets->take($request->get('limit', 10))->sortByDesc('created_at');
        // return $justJoin;

        if (!empty($category_slug) || !empty($request->main_cat)) {
            # code...
            return view('user.tickets.ticket-listing', get_defined_vars());
        }

        return view('user.tickets.index', get_defined_vars());
    }

    public function ticket_more($slug)
    {
        # code...
    }

    public function search_result($request, $category_slug)
    {
        $result_array = array();
        $tickets = Tickets::with('subCategory', 'city')->where('status', 1);
        $ticket_category = Tickets::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")->get();
        $featured = Tickets::with('subCategory', 'city')->where('status', 1)->where('featured', 1)->get();

        if (!empty($category_slug)) {
            $main_category = MainCategory::where('slug', $category_slug)->first();
            if ($main_category) {
                $request->main_cat = $main_category->id;
            }
        }

        if (isset($request)) {
            if (isset($request->quick_search)) {

                $search = $request->quick_search;
                $main_ids = MainCategory::where('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();
                $sub_ids = SubCategory::where('name', 'LIKE', '%' . $search . '%')->orWhere('main_category_id', $main_ids)->pluck('id')->toArray();
                $tickets = Tickets::with('subCategory')
                    ->where(function ($q) use ($search, $sub_ids) {
                        $q->orWhere('title', 'LIKE', '%' . $search . '%')->orWhere('description', 'LIKE', '%' . $search . '%')
                            ->orWhereIn('sub_category_id', $sub_ids);
                    })->where('status', 1);
                $featured = [];
            }

            if (isset($request->name)) {

                $attribute = $request->name;
                $tickets = $tickets->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });
                $featured = [];
            }

            if ($request->main_cat != "all" && isset($request->main_cat)) {

                $sub_cat_ids = SubCategory::where('main_category_id', '=', $request->main_cat)->pluck('id')->toArray();
                $tickets =  $tickets->whereIn('sub_category_id', $sub_cat_ids);
                $ticket_category =  $ticket_category->whereIn('sub_category_id', $sub_cat_ids);
                $featured = [];
            }

            if ($request->sub_category != "all" && isset($request->sub_category)) {

                $tickets =  $tickets->where('sub_category_id', $request->sub_category);
                $ticket_category =  $ticket_category->whereIn('sub_category_id', $request->sub_category);
                $featured = [];
            }

            if (isset($request->min_range)) {

                if (isset($request->max_range)) {

                    $tickets =  $tickets->whereBetween('price', [$request->min_range, $request->max_range]);
                    $featured = [];
                } else {
                    $tickets =  $tickets->where('price', '>=', $request->min_range);
                    $featured = [];
                }
            }

            if (isset($request->location)) {

                $tickets =  $tickets->where('city_id', $request->location);
                $featured = [];
            }

            if ($request->dynamic_subs == "one") {
                $ids = [];
                foreach ($request->dynamic_sub as $sub) {
                    foreach ($tickets as $row) {
                        if (isset($row->dynamic_sub_ids)) {
                            $abc = (in_array($sub, json_decode($row->dynamic_sub_ids))) ? $ids[] = $row->id : '';
                        }
                    }
                }
                $tickets =  $tickets->whereIn('id', $ids);
                $featured = [];
            }

            if (isset($request->sub_cate_id)) {

                $attribute = $request->sub_cate_id;
                $tickets =  $tickets->filter(function ($item) use ($attribute) {
                    return strpos($item->sub_category_id, $attribute) !== false;
                });
                $featured = [];
            }

            if ($request->sub_category_id) {
                $tickets =  $tickets->where('sub_category_id', $request->sub_category_id);
            }

            if (isset($request->sort_by)) {
                if ($request->sort_by == "1") {
                    $tickets =  $tickets->orderBy('created_at', 'desc');
                }
                if ($request->sort_by == "2") {
                    $tickets =  $tickets->orderBy('created_at', 'asc');
                }
                if ($request->sort_by == "3") {
                    $tickets =  $tickets->orderBy('title', 'asc');
                }
                if ($request->sort_by == "4") {
                    $tickets =  $tickets->orderBy('title', 'desc');
                }
            }

            $tickets =  $tickets->get();
        }

        $total_count = 0;
        if (isset($tickets)) {
            $total_count = count($tickets);
        }

        $result_array = array(
            'tickets' => $tickets,
            'ticket_category' => $ticket_category,
            'featured' => $featured,
            'ticket_count' => $total_count,
        );
        return $result_array;
    }

    public function tickets_sort(Request $request)
    {
        if ($request->sort) {
            $res = '1';
            $sort_by = $request->sort_by;

            $featured = Tickets::with('subCategory')->where('featured', 1)->where('status', 1);
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

            $datas = Tickets::with('subCategory')->where('status', 1);
            if ($sort_by == '1') {
                $datas = $datas->orderBy('created_at', 'desc');
            } elseif ($sort_by == '2') {
                $datas = $datas->orderBy('created_at', 'asc');
            } elseif ($sort_by == '3') {
                $datas = $datas->orderBy('title', 'asc');
            } elseif ($sort_by == '4') {
                $datas = $datas->orderBy('title', 'desc');
            }
            $tickets = $datas->get();


            $ticket_category = Tickets::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
                ->groupBy("sub_category_id")->get();

            $main_category = MainCategory::where('major_category_id', '=', '8')->get();
            $sub_cat_search = array();

            if (isset($_GET['sub_category'])) {
                if ($_GET['sub_category'] != "all") {
                    $sub_cat_search = SubCategory::where('main_category_id', '=', $_GET['main_cat'])->get();
                }
            }

            $dynamic_mains = DynamicMainCategory::where('major_category_id', 8)->where('status', 1)->get();
            $banner = TicketBanner::first();
            $locations = City::all();
            $banners = SliderImage::where('major_category_id', 8)->first();
            $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
            $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();
            $major_category = MajorCategory::find(8);
            $categories = MainCategory::where('major_category_id', 8)->get();

            $categoryForSidebar = MainCategory::with('subCategory.ticket')->withCount('ticket')->where('major_category_id', 8)->orderBy('ticket_count', 'DESC')->take(10)->get();

            return view('user.tickets.index', compact(
                'tickets',
                'categoryForSidebar',
                'featured',
                'banners',
                'main_category',
                'ticket_category',
                'sub_cat_search',
                'locations',
                'dynamic_mains',
                'banner',
                'sort_by',
                'hot_trends',
                'influencer_reviews',
                'major_category',
                'categories'
            ));
        }
    }

    public function search_ticket_name_ajax(Request $request)
    {
        $search = $request->keyword;
        $tickets = array();
        if (!empty($search)) {
            $tickets = Tickets::select('title', 'id')->where('title', 'LIKE', '%' . $search . '%')->orderby('title')->limit(6)->get();
        }
        $view = view('user.tickets.autocomplete_ticket_ajax', compact('tickets'))->render();
        return response()->json(['html' => $view]);
    }
}
