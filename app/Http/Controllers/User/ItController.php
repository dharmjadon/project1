<?php

namespace App\Http\Controllers\User;

use App\Models\It;
use App\Models\City;
use App\Models\News;
use App\Models\State;
use App\Models\MoreInfo;
use App\Models\Nationality;
use App\Models\SliderImage;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\MajorCategory;
use App\Models\HomeTrendBanner;
use App\Models\InfluencerReview;
use Illuminate\Support\Facades\DB;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;

class ItController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $category_slug = '')
    {
        $cities = City::all();
        $states = State::all();

        // $allIts = It::with('sub_category')->where('status', '=', '1')->get();
        $result_array = $this->search_result($request, $category_slug);

        $its = $result_array['its'];
        $itCount = $result_array['it_count'];
        $itFeatured = $result_array['featured'];
        $itPopulars = $result_array['popular'];

        $sub_cat_search = array();

        if (isset($_GET['sub_category'])) {
            if ($_GET['sub_category'] != "all") {
                $sub_cat_search = SubCategory::where('main_category_id', '=', $_GET['main_cat'])->get();
            }
        }

        $main_category = MainCategory::where('major_category_id', 15)->get();

        $dynamic_mains = DynamicMainCategory::where('major_category_id', 15)->where('status', 1)->get();
        $banner = SliderImage::where('major_category_id', 15)->get();
        $major_category = MajorCategory::find(15);
        $states = State::all();
        $categories = MainCategory::where('major_category_id', 15)->withCount('it')->get(); //return $categories;
        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();

        $categoryForSidebar = MainCategory::with('subCategory.it')->withCount('it')->where('major_category_id', 15)->take(10)->get(); //return $categoryForSidebar;
        $allCategory = MajorCategory::with(['mainCategory', 'mainCategory.subCategory']);

        $it_category = It::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")->orderBy('total', 'desc')
            ->get();

        $home_trend = HomeTrendBanner::all();

        $allits = It::with('sub_category.it')->where('status', '=', '1')->take($request->get('limit', 10))->groupBy('created_at')->orderBy('created_at', 'DESC')->get();
        $justJoin = $allits->take($request->get('limit', 10))->sortByDesc('created_at');
        // return $its;
        if (!empty($category_slug) && !empty($request->main_cat)) {
            # code...
            return view('user.it.it-listing', get_defined_vars());
        }
        return view('user.it.index', get_defined_vars());
    }


    public function search_result($request, $category_slug)
    {
        $result_array = array();

        $its = It::with('sub_category', 'city')->where('status', 1);
        $it_category = It::where('status', '=', '1')
            ->select('title', 'sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id");

        $featured = It::with('sub_category', 'city')->where('is_featured', 1)->where('status', 1);
        $populars = It::with('sub_category', 'city')->where('is_popular', 1)->where('status', 1);

        if (!empty($category_slug)) {
            # code...
            if ($category_slug === 'all') {
                # code...
                $request->main_cat = 'all';
            } else {
                # code...
                $main_category = MainCategory::where('slug', $category_slug)->first();
                if ($main_category) {
                    # code...
                    $request->main_cat = $main_category->id;
                }
            }
        }

        if (isset($request)) {
            if (isset($request->quick_search)) {
                $search = $request->quick_search;
                $main_ids = MainCategory::where('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();
                $sub_ids = SubCategory::where('name', 'LIKE', '%' . $search . '%')->orWhere('main_category_id', $main_ids)->pluck('id')->toArray();
                $its = It::where('status', 1)->Where('title', 'LIKE', '%' . $search . '%');
                // $featured = [];
            }

            if (isset($request->name)) {
                $attribute = $request->name;
                $its = $its->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });

                $it_category = $it_category->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });
                // $featured = [];
            }

            if (isset($request->company_name)) {
                # code...
                $its = $its->where('company_name', $request->company_name);

                $it_category = $it_category->where('company_name', $request->company_name);
            }

            if ($request->main_cat != "all" && isset($request->main_cat)) {
                $sub_cat_ids = SubCategory::where('main_category_id', '=', $request->main_cat)->pluck('id')->toArray();
                $its = $its->whereIn('sub_category_id', $sub_cat_ids);
                $it_category = $it_category->whereIn('sub_category_id', $sub_cat_ids);
                // $featured = [];
            }

            if ($request->sub_category != "all" && isset($request->sub_category)) {
                $its = $its->where('sub_category_id', $request->sub_category);
                $it_category = $it_category->where('sub_category_id', $request->sub_category);
                // $featured = [];
            }

            if ($request->sub_category_id) {
                $its = $its->where('sub_category_id', $request->sub_category_id);
                $it_category = $it_category->where('sub_category_id', $request->sub_category_id);
            }

            if (isset($request->location)) {
                $its = $its->where('city_id', $request->location);
                $it_category = $it_category->where('city_id', $request->location);
                // $featured = [];
            }

            if ($request->dynamic_subs == "one") {
                $ids = [];
                foreach ($request->dynamic_sub as $sub) {
                    foreach ($its as $row) {
                        if (isset($row->dynamic_sub_ids)) {
                            $abc = (in_array($sub, json_decode($row->dynamic_sub_ids))) ? $ids[] = $row->id : '';
                        }
                    }
                }
                $its = $its->whereIn('id', $ids);
                $it_category = $it_category->whereIn('id', $ids);
                // $featured = [];
            }
            if ($request->city != "all" && isset($request->city)) {
                $city_ids = City::where('state_id', $request->city)->pluck('id')->toArray();
                $its = $its->whereIn('city_id', $city_ids);
                $it_category = $it_category->whereIn('city_id', $city_ids);
                // $featured = [];
            }

            if (isset($request->sort_by)) {
                if ($request->sort_by == "1") {
                    $its = $its->orderBy('created_at', 'desc');
                    $it_category = $it_category->orderBy('created_at', 'desc');
                    $featured = $featured ? $featured->orderBy('created_at', 'desc') : null;
                    $populars = $populars ? $populars->orderBy('created_at', 'desc') : null;
                }
                if ($request->sort_by == "2") {
                    $its = $its->orderBy('created_at', 'desc');
                    $it_category = $it_category->orderBy('created_at', 'desc');
                    $featured = $featured ? $featured->orderBy('created_at', 'desc') : null;
                    $populars = $populars ? $populars->orderBy('created_at', 'desc') : null;
                }
                if ($request->sort_by == "3") {
                    $its = $its->orderBy('title', 'desc');
                    $it_category = $it_category->orderBy('title', 'desc');
                    $featured = $featured ? $featured->orderBy('created_at', 'desc') : null;
                    $populars = $populars ? $populars->orderBy('created_at', 'desc') : null;
                }
                if ($request->sort_by == "4") {
                    $its = $its->orderBy('title', 'desc');
                    $it_category = $it_category->orderBy('title', 'desc');
                    $featured = $featured ? $featured->orderBy('created_at', 'desc') : null;
                    $populars = $populars ? $populars->orderBy('created_at', 'desc') : null;
                }
            } else {
                $its = $its->orderBy('created_at', 'desc');
                $it_category = $it_category->orderBy('title', 'desc');
                $featured = $featured ? $featured->orderBy('created_at', 'desc') : null;
                $populars = $populars ? $populars->orderBy('created_at', 'desc') : null;
            }
        }
        // dd($its);

        $total_count = 0;
        if (isset($its)) {
            # code...
            $total_count = $its->count();
        }

        $result_array = array(
            'its' => $its->get(),
            'it_count' => $total_count,
            'featured' => $featured->get(),
            'it_category' => $it_category->get(),
            'popular' => $populars->get(),
        );

        return $result_array;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function itsMore(Request $request, $slug)
    {
        // $featured = It::with('approvedReviews')->where('slug', $slug)->first();


        $it = It::with('approvedReviews')->where('slug', '=', $slug)->first();
        abort_if(!$it, 404);
        $type = 'it';
        $more_info = NULL;
        if (isset($it->id)) {
            $more_info = MoreInfo::where(['module_id' => $it->id, 'module_name' => 'it'])->get();
        }

        $json = json_decode($it->social_links, true);
        $youtube = '';
        if ($json) {
            # code...
            foreach ($json as $key => $value) {
                if ($key == '1') {
                    $youtube = $value;
                }
            }
        }

        // Avg Rating
        $reviews = It::where('slug', $slug)->first();
        $avg_rating = round($reviews->approvedreviews()->avg('rating'), 1);

        $four_images = json_decode($it->images);
        $amenties_it = json_decode($it->amenties);
        // return $amenties_event;
        $landmark_it = json_decode($it->landmarks);

        //similar its
        $sub_cate = $it->sub_category_id;
        $similarIts = It::where('title', 'LIKE', '%' . $it->title . '%')->where('sub_category_id', '=', $sub_cate)->where('slug', '!=', $slug)->get(); //return $similar_its;

        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $it->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }


        if ($it->lat && $it->long) {
            $nearby = DB::table("its")
                ->select("its.id", DB::raw("6371 * acos(cos(radians(" . $it->lat . "))
                * cos(radians(its.lat))
                * cos(radians(its.long) - radians(" . $it->long . "))
                + sin(radians(" . $it->lat . "))
                * sin(radians(its.lat))) AS distance"), "its.*")
                // ->with('subCategory')
                ->groupBy("its.id")
                ->orderBy('distance', 'ASC')
                ->get();
        } else {
            $nearby = [];
        }

        $nations = Nationality::all();

        $count = $it->views + 1;
        $it->views = $count;
        $it->timestamps = false;
        $it->save();
        // return $it;
        //    dd()



        $its = It::with('sub_category')->where('status', '=', '1')->get(); //return $its;
        $justJoin = $its->take($request->get('limit', 10))->sortByDesc('created_at');

        $categoryForSidebar = MainCategory::with('subCategory.it')->withCount('it')->where('major_category_id', 15)->take(10)->get();

        $whatsapp = MajorCategory::where('id', 15)->first();

        // $justJoin = [];
        return view('user.it.it-details', get_defined_vars());
    }


    public function search_it_name_ajax(Request $request)
    {
        $search = $request->keyword;
        $its = array();
        if (!empty($search)) {
            $its = It::select('title', 'id')->where('title', 'LIKE', '%' . $search . '%')->orderby('title')->limit(6)->get();
        }
        $view = view('user.it.autocomplete_it_ajax', get_defined_vars())->render();
        return response()->json(['html' => $view]);
    }

    public function itSort(Request $request)
    {
        if ($request->sort_by) {
            $featured = It::where('status', '1')->where('is_featured', '1');
            if ($request->sort_by == '1') {
                $featured = $featured->orderBy('created_at', 'ASC')->get();
            } elseif ($request->sort_by == '2') {
                $featured = $featured->orderBy('created_at', 'DESC')->get();
            } elseif ($request->sort_by == '3') {
                $featured = $featured->orderBy('title', 'ASC')->get();
            } elseif ($request->sort_by == '4') {
                $featured = $featured->orderBy('title', 'DESC')->get();
            } else {
                $featured = $featured->get();
            }

            $its = It::where('status', '1');
            if ($request->sort_by == '1') {
                $its = $its->orderBy('created_at', 'ASC')->get();
            } elseif ($request->sort_by == '2') {
                $its = $its->orderBy('created_at', 'DESC')->get();
            } elseif ($request->sort_by == '3') {
                $its = $its->orderBy('title', 'ASC')->get();
            } elseif ($request->sort_by == '4') {
                $its = $its->orderBy('title', 'DESC')->get();
            } else {
                $its = $its->get();
            }

            $dynamic_mains = DynamicMainCategory::where('major_category_id', '=', '15')->where('status', 1)->get();
            $banner = SliderImage::where('major_category_id', 15)->get();
            $major_category = MajorCategory::find(15);
            $states = State::all();
            $categories = MainCategory::where('major_category_id', 15)->get();
            $hot_trends = News::orderBy('created_at', 'desc')->limit(10)->get();

            $locations = City::all();
            $main_category = MainCategory::where('major_category_id', '=', '15')->get();
            $categoryForSidebar = MainCategory::with('subCategory.it')->withCount('it')->where('major_category_id', 15)->orderBy('it_count', 'DESC')->take(10)->get();
            $sub_cat_search = array();
            if (isset($_GET['sub_category'])) {
                if ($_GET['sub_category'] != "all") {
                    $sub_cat_search = SubCategory::where('main_category_id', '=', $_GET['main_cat'])->get();
                }
            }

            return view('user.it.index', get_defined_vars());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\It  $it
     * @return \Illuminate\Http\Response
     */
    public function show(It $it)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\It  $it
     * @return \Illuminate\Http\Response
     */
    public function edit(It $it)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\It  $it
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, It $it)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\It  $it
     * @return \Illuminate\Http\Response
     */
    public function destroy(It $it)
    {
        //
    }
}
