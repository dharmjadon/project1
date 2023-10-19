<?php

namespace App\Http\Controllers\User;

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

class FrontUserController extends Controller
{
    public function newLandingPage()
    {
        $main_categories = MainCategory::all();
        $featured_venue = Venue::with('subCategory', 'city')->where('status', 1)->where('assign_featured', 1)->get();
        $venue_category = Venue::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();

        $featured_event = Events::with('get_subcat', 'city')->where('status', 1)->where('is_featured', 1)->get();
        $event_category = Events::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();

        $concierge_event = Concierge::with('subCategory', 'city')->where('status', 1)->where('featured', 1)->get();
        $concierge_category = Concierge::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();

        $news = News::orderby('id', 'desc')->get();

        $popular_cities = City::where('is_popular', '=', '1')->orderby('id', 'desc')->limit(4)->get();
        $popular_cities_eight = City::where('is_popular', '=', '1')->orderby('id', 'desc')->limit(8)->get();
        $galleries = Gallery::with('subCategory', 'city')->where('active', '=', '1')->orderby('id', 'DESC')->get();
        $home_banners = Banner::where('banner_type', 1)->get();
        $promotional_banners = Banner::where('banner_type', 2)->get();
        $recommendations = ItemRecommendation::where('status', 1)->get();
        $home_trend = HomeTrendBanner::all();

        return view(
            'new-landing-page',
            compact(
                'main_categories',
                'featured_venue',
                'venue_category',
                'news',
                'galleries',
                'popular_cities_eight',
                'featured_event',
                'event_category',
                'concierge_event',
                'concierge_category',
                'popular_cities',
                'promotional_banners',
                'home_banners',
                'recommendations',
                'home_trend'
            )
        );
    }

    public function home()
    {
        $main_categories = MainCategory::with(['subCategory'])->get();

        $categoryForSidebar = MajorCategory::with(['mainCategory', 'mainCategory.subCategory']);

        $home_content_section = HomeSectionContent::find(1);
        //dd($categoryForSidebar->get());
        return view('user.home.main', get_defined_vars());
        /*return view('user.home.index',compact('main_categories','featured_venue','venue_category',
        'news',
        'galleries',
        'popular_cities_eight',
        'featured_event','event_category',
        'concierge_event','concierge_category',
        'popular_cities','promotional_banners','home_banners', 'recommendations'));*/
    }

    public function landing_home()
    {

        $main_categories = MainCategory::all();
        $featured_venue = Venue::with('subCategory', 'city')->where('status', 1)->where('assign_featured', 1)->get();
        $venue_category = Venue::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();


        $featured_event = Events::with('get_subcat', 'city')->where('status', 1)->where('is_featured', 1)->get();
        $event_category = Events::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();


        $concierge_event = Concierge::with('subCategory', 'city')->where('status', 1)->where('featured', 1)->get();
        $concierge_category = Concierge::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();


        $news = News::orderby('id', 'desc')->get();

        $popular_cities = City::where('is_popular', '=', '1')->orderby('id', 'desc')->limit(4)->get();
        $popular_cities_eight = City::where('is_popular', '=', '1')->orderby('id', 'desc')->limit(8)->get();


        $galleries = Gallery::with('subCategory', 'city')->where('active', '=', '1')->orderby('id', 'DESC')->get();

        $home_banners = Banner::where('banner_type', 1)->get();
        $promotional_banners = Banner::where('banner_type', 2)->get();
        $recommendations = ItemRecommendation::where('status', 1)->get();

        $home_trend = HomeTrendBanner::all();

        return view(
            'user.home.index',
            compact(
                'main_categories',
                'featured_venue',
                'venue_category',
                'news',
                'galleries',
                'popular_cities_eight',
                'featured_event',
                'event_category',
                'concierge_event',
                'concierge_category',
                'popular_cities',
                'promotional_banners',
                'home_banners',
                'recommendations',
                'home_trend'
            )
        );
    }

    public function subhome()
    {

        $main_categories = MainCategory::all();
        $featured_venue = Venue::with('subCategory', 'city')->where('status', 1)->where('assign_featured', 1)->get();
        $venue_category = Venue::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();


        $featured_event = Events::with('get_subcat', 'city')->where('status', 1)->where('is_featured', 1)->get();
        $event_category = Events::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();

        $concierge_event = Concierge::with('subCategory', 'city')->where('status', 1)->where('featured', 1)->get();
        $concierge_category = Concierge::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();

        $news = News::orderby('id', 'desc')->get();

        $popular_cities = City::where('is_popular', '=', '1')->orderby('id', 'desc')->limit(4)->get();
        $popular_cities_eight = City::where('is_popular', '=', '1')->orderby('id', 'desc')->limit(8)->get();

        $galleries = Gallery::with('subCategory', 'city')->where('active', '=', '1')->orderby('id', 'DESC')->get();

        $home_banners = Banner::where('banner_type', 1)->get();
        $promotional_banners = Banner::where('banner_type', 2)->get();
        $recommendations = ItemRecommendation::where('status', 1)->get();

        return view('user.home.index', get_defined_vars());
        /*return view('user.home.index',compact('main_categories','featured_venue','venue_category',
        'news',
        'galleries',
        'popular_cities_eight',
        'featured_event','event_category',
        'concierge_event','concierge_category',
        'popular_cities','promotional_banners','home_banners', 'recommendations'));*/
    }

    public function search_result(Request $request)
    {

        $main_categories = MainCategory::all();
        $search = $request->name;
        $location = $request->location;

        // Search in Events
        $events = Events::with('get_subcat', 'featureImage', 'approvedReviews');

        if ($search) {
            $events = $events->where('title', 'like', "%$search%")
                ->orWhereHas('get_subcat', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('get_subcat.mainCategory', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });
        }

        if ($location) {
            $events = $events->where(function ($query) use ($location) {
                $query->where('city', 'LIKE', '%' . $location . '%')
                    ->orWhere('location', 'LIKE', '%' . $location . '%');
            });
        }
        $events = $events->get();

        // Search in Venue
        $venues = Venue::with('subCategory', 'featureImage', 'approvedReviews');
        if ($search) {
            $venues = $venues->where('title', 'like', "%$search%")
                ->orWhereHas('subCategory', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('subCategory.mainCategory', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });
        }
        if ($location) {
            $venues = $venues->where(function ($query) use ($location) {
                        $query->where('city', 'LIKE', '%' . $location . '%')
                                ->orWhere('location', 'LIKE', '%' . $location . '%');
            });
        }
        $venues = $venues->get();
        // Search in Buy and Sell
        $buysell = BuySell::with('get_subcat', 'featureImage', 'approvedReviews');
        if ($search) {
            $buysell = $buysell->where('product_name', 'like', "%$search%")
                ->orWhereHas('get_subcat', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('get_subcat.mainCategory', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });
        }
        if ($location) {
            $buysell = $buysell->where(function ($query) use ($location) {
                $query->where('city', 'LIKE', '%' . $location . '%')
                    ->orWhere('location', 'LIKE', '%' . $location . '%');
            });
        }
        $buysell = $buysell->get();

        // Search in directory
        $directory = Directory::with('subCategory', 'featureImage', 'approvedReviews');
        if ($search) {
            $directory = $directory->where('title', 'like', "%$search%")
                ->orWhereHas('subCategory', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('subCategory.mainCategory', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });
        }
        if ($location) {
            $directory = $directory->where(function ($query) use ($location) {
                $query->where('city', 'LIKE', '%' . $location . '%')
                    ->orWhere('location', 'LIKE', '%' . $location . '%');
            });
        }
        $directory = $directory->get();

        // Search in Concierge
        $concierges = Concierge::with('subCategory', 'approvedReviews');
        if ($search) {
            $concierges = $concierges->where('title', 'like', "%$search%")
                ->orWhereHas('subCategory', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('subCategory.mainCategory', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });
        }
        if ($location) {
            $concierges = $concierges->whereHas('city', function ($query) use ($location) {
                $query->where('name', 'LIKE', '%' . $location . '%');
            })->orWhere('location', 'LIKE', '%' . $location . '%');
        }
        $concierges = $concierges->get();

        // Search in Influencer
        $influencers = Influencer::with('subCategory', 'featureImage', 'approvedReviews');
        if ($search) {
            $influencers = $influencers->where('name', 'like', "%$search%")
                ->orWhereHas('subCategory', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('subCategory.mainCategory', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });
        }
        if ($location) {
            $influencers = $influencers->whereHas('city', function ($query) use ($location) {
                $query->where('name', 'LIKE', '%' . $location . '%');
            })->orWhere('location', 'LIKE', '%' . $location . '%');
        }
        $influencers = $influencers->get();

        //
        $jobs = Job::with('subCategory', 'city_name');
        if ($search) {
            $jobs = $jobs->where('job_title', 'like', "%$search%");
        }
        if ($location) {
            $jobs = $jobs->whereHas('city_name', function ($query) use ($location) {
                $query->where('name', 'LIKE', '%' . $location . '%');
            })->orWhere('location', 'LIKE', '%' . $location . '%');
        }
        $jobs = $jobs->get();

        $tickets = Tickets::with('subCategory', 'city');
        if ($search) {
            $tickets = $tickets->where('title', 'like', "%$search%");
        }
        if ($location) {
            $tickets = $tickets->whereHas('city', function ($query) use ($location) {
                $query->where('name', 'LIKE', '%' . $location . '%');
            });
        }
        $tickets = $tickets->get();


        return view('user.home.search-result', compact('main_categories', 'events', 'venues', 'buysell', 'directory', 'concierges', 'influencers', 'jobs', 'tickets'));
    }

    public function ajax_render_subcategory(Request $request)
    {
        $sub_cat = SubCategory::where('main_category_id', '=', $request->select_v)->orderBy('name', 'ASC')->get();
        echo json_encode($sub_cat);
        exit;
    }

    public function home_news($slug)
    {

        $data = News::where('slug', $slug)->first();
        $count = $data->views + 1;

        $data->views = $count;
        $data->timestamps = false;
        $data->save();


        $datas = News::all();

        return view('user.home.news_more', compact('data', 'datas'));
    }

    function save_click_count(Request $request)
    {
        if ($request->ajax()) {

            $main_category = $request->main_category_id;
            $main_id = $request->main_id;
            $typeClick = $request->type;

            if ($main_category == 1) {
                $type = Venue::find($main_id);
            } elseif ($main_category == 2) {
                $type = Events::find($main_id);
            } elseif ($main_category == 3) {
                $type = BuySell::find($main_id);
            } elseif ($main_category == 4) {
                $type = Directory::find($main_id);
            } elseif ($main_category == 5) {
                $type = Concierge::find($main_id);
            } elseif ($main_category == 6) {
                $type = Influencer::find($main_id);
            } elseif ($main_category == 14) {
                $type = Education::find($main_id);
            }

            $clickcount = new CountClick([
                'major_category_id' => $main_category,
                'product_id' => $main_id,
                'type_of_click' => $typeClick
            ]);
            $type->clickCount()->save($clickcount);

            return "success";
        }
    }

    public function sidebarSearch($request)
    {
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

        if ($request->dynamic_subs == "one") {
            $ids = [];
            $dat = Venue::where('status', 1)->get();
            foreach ($request->dynamic_sub as $sub) {
                foreach ($dat as $row) {
                    if (isset($row->dynamic_sub_ids)) {
                        $abc = (in_array($sub, json_decode($row->dynamic_sub_ids))) ? $ids[] = $row->id : '';
                    }
                }
            }
            $datas = Venue::whereIn('id', $ids);
        }

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

        if (isset($request->sort_by)) {
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

        $datas = $datas->where('status', 1)->get();

        return $datas;
    }
}
