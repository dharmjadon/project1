<?php

namespace App\Http\Controllers\User;

use DB;
use App\Models\Blog;
use App\Models\City;
use App\Models\News;
use App\Models\State;
use App\Models\Venue;
use App\Models\Events;
use App\Models\BuySell;
use App\Models\Gallery;
use App\Models\MoreInfo;
use App\Models\Concierge;
use App\Models\Directory;
use App\Models\SliderImage;
use App\Models\SubCategory;
use App\Models\MainCategory;
use App\Models\Motors;
use App\Models\MajorCategory;
use Illuminate\Http\Request;
use App\Models\InfluencerReview;
use App\Models\ItemRecommendation;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use App\Models\HomeTrendBanner;

class MotorsController extends Controller
{

    public function index(Request $request, $type)
    {
        $acc_type = '';
        if ($type == 'buy') {
            $acc_type = 1;
        }
        if ($type == 'rent') {
            $acc_type = 2;
        }
        $datas = Motors::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)
            ->where('accommodation_type', $acc_type);

        if (isset($request->search_type)) {
            if (isset($request->city)) {
                $datas->where('city_id', $request->city);
            }
            /*if(isset($request->brand))
              {
                $datas->where('motor_brand_id', $request->brand);
              }
              if(isset($request->year))
              {
                $datas->where('motor_year', $request->year);
              }
              if(isset($request->kilometer))
              {
                $datas->where('motor_km', $request->kilometer);
              }
              if(isset($request->body_type))
              {
                $datas->where('motor_bodytype', $request->body_type);
              }
              if(isset($request->feul_type))
              {
                $datas->where('motor_fueltype', $request->feul_type);
              }
              if(isset($request->engine_power))
              {
                $datas->where('motor_powers', $request->engine_power);
              }
              if(isset($request->regional_space))
              {
                $datas->where('motor_regionalspace', $request->regional_space);
              }
              if(isset($request->sellert_type))
              {
                $datas->where('motor_sellertype', $request->sellert_type);
              }
              if(isset($request->transmission_type))
              {
                $datas->where('motor_transmission', $request->transmission_type);
              }
              if(isset($request->badges))
              {
                $datas->where('motor_badges', $request->badges);
              }
              if(isset($request->exports_status))
              {
                $datas->where('motor_export_status', $request->exports_status);
              }
              if(isset($request->colors))
              {
                $datas->where('motor_color', $request->colors);
              }
              if(isset($request->doors))
              {
                $datas->where('motor_doors', $request->doors);
              }
              if(isset($request->tech_feature))
              {
                $datas->where('motor_techfeature', $request->tech_feature);
              }
              if(isset($request->extras))
              {
                $datas->where('motor_extras', $request->extras);
              }
              if(isset($request->warranty))
              {
                $datas->where('motor_warranty', $request->warranty);
              }
              if(isset($request->ads_post))
              {
                $datas->where('motor_ads_posted', $request->ads_post);
              }
              if(isset($request->num_cylinder))
              {
                $datas->where('motor_num_cylinders', $request->num_cylinder);
              }
              if(isset($request->steering_side))
              {
                $datas->where('motor_stringside', $request->steering_side);
              }
              if(isset($request->other_filter))
              {
                $datas->where('motor_other', $request->other_filter);
              } */
            if (isset($request->minimum_price) && isset($request->max_price)) {
                $datas->whereBetween('price', [$request->minimum_price, $request->max_price]);
            }

            if ($request->dynamic_subs == "one") {
                $ids = [];
                $dat = Motors::where('status', 1)->get();
                foreach ($request->dynamic_sub as $sub) {
                    foreach ($dat as $row) {
                        if (isset($row->dynamic_sub_ids)) {
                            $abc = (in_array($sub, json_decode($row->dynamic_sub_ids))) ? $ids[] = $row->id : '';
                        }
                    }
                }
                $datas->whereIn('id', $ids);
            }

            if (isset($request->main_category)) {
                $datas->where('id', $main_category);
            }
            $datas = $datas->whereHas('subCategory.mainCategory', function ($q) use ($main_category) {
                $q->where('id', $main_category);
            });
        }
        if (isset($request->sub_cate_id)) {
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

        $categories = MainCategory::with('subCategory')->where('major_category_id', config('global.motor_major_id'))->get();
        //$categories = MainCategory::where('major_category_id', 9)->get();
        /*dd($categories);*/
        $main = MainCategory::where('major_category_id', config('global.motor_major_id'))->pluck('id')->toArray();
        $main_category = MainCategory::where('major_category_id', '=', config('global.motor_major_id'))->get();
        //dd($main_category);
        $subs = SubCategory::whereIn('main_category_id', $main)->get();

        $locations = City::all();

        $venue_category = Motors::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        $dynamic_mains = DynamicMainCategory::where('major_category_id', config('global.motor_major_id'))->get();
        $banner = SliderImage::where('major_category_id', 9)->get();
        $major_category = MajorCategory::find(config('global.motor_major_id'));
        $states = State::all();
        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();
        $categoryForSidebar = MainCategory::with('subCategory')->withCount('accommodation')->where('major_category_id', config('global.motor_major_id'))->orderBy('accommodation_count', 'DESC')->take(10)->get();
        //dd($categoryForSidebar);
        $allCategory = MajorCategory::with(['mainCategory', 'mainCategory.subCategory']);
        /*return view('user.accommodation.accommodation', compact('datas', 'featured', 'datas2', 'featured2', 'datas3', 'featured3', 'venue_category', 'categories', 'subs', 'locations', 'dynamic_mains', 'banner', 'major_category', 'states','hot_trends', 'influencer_reviews', 'categoryForSidebar'));*/

        $datas = $datas->get();
        $home_trend = HomeTrendBanner::all();
        //dd($datas);
        $justJoin = $datas->take($request->get('limit', 10))->sortByDesc('created_at');
        return view('user.motors.index', get_defined_vars());
        // return view('user.motors.motor',get_defined_vars());

    }
    public function vehicle($type = NULL, Request $request)
    {
        if ($type != NULL) {
        }
    }
    public function property($type = NULL, Request $request)
    {
        $major_category_id = 9;

        if ($type != NULL) {



            $featured = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type', 1)->where('assign_featured', 1);

            if ($type == 'buy') {
                $buy = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type', 4);
                if (isset($request->search_type) && $request->search_type == 'quick') {
                    $buy->when(isset($request->quick_search) && $request->quick_search != '', function ($q) use ($request) {
                        return $q->where('title', 'LIKE', '%' . $request->quick_search . '%');
                    });
                    $featured->when(isset($request->quick_search) && $request->quick_search != '', function ($q) use ($request) {
                        return $q->where('title', 'LIKE', '%' . $request->quick_search . '%');
                    });
                }
                if (isset($request->search_type) && $request->search_type == 'filter') {

                    $main_category = $request->main_category;
                    $sub_category = $request->sub_category;
                    $location = $request->location;
                    //$featured = [];

                    $buy = $buy->with('subCategory.mainCategory');

                    if ($request->dynamic_subs == "one") {
                        $ids = [];
                        $dat = Accommodation::where('status', 1)->get();
                        foreach ($request->dynamic_sub as $sub) {
                            foreach ($dat as $row) {
                                if (isset($row->dynamic_sub_ids)) {
                                    $abc = (in_array($sub, json_decode($row->dynamic_sub_ids))) ? $ids[] = $row->id : '';
                                }
                            }
                        }
                        //$datas_query =  Accommodation::whereIn('id', $ids);
                        //$buy=$buy->whereIn('id', $ids);
                        $buy = $buy->when($request->dynamic_subs == "one", function ($q) use ($ids) {
                            return $q->whereIn('id', $ids);
                        });
                        $featured = $featured->when($request->dynamic_subs == "one", function ($q) use ($ids) {
                            return $q->whereIn('id', $ids);
                        });
                    }

                    if ($main_category) {
                        $buy = $buy->whereHas('subCategory.mainCategory', function ($q) use ($main_category) {
                            $q->where('id', $main_category);
                        });
                        $featured = $featured->whereHas('subCategory.mainCategory', function ($q) use ($main_category) {
                            $q->where('id', $main_category);
                        });
                    }
                    if ($sub_category) {
                        $buy = $buy->where('sub_category_id', $sub_category)->where('status', 1);
                        $featured = $featured->where('sub_category_id', $sub_category)->where('status', 1);
                    }
                    if ($location) {
                        $buy = $buy->where('city_id', $location);
                        $featured = $featured->where('city_id', $location);
                    }
                    if ($request->quick_search) {
                        $buy = $buy->where('title', 'LIKE', '%' . $request->quick_search . '%');
                        $featured = $featured->where('title', 'LIKE', '%' . $request->quick_search . '%');
                    }
                    if ($request->sub_cate_id) {
                        $buy = $buy->where('sub_category_id', $request->sub_cate_id);
                        $featured = $featured->where('sub_category_id', $request->sub_cate_id);
                    }

                    if ($request->city != "all" && isset($request->city)) {
                        $city_ids = City::where('state_id', $request->city)->pluck('id')->toArray();
                        $buy =  $buy->where('city_id', $request->city);
                        $featured = $featured->where('city_id', $request->city);
                    }
                    //$buy = $buy->whereIn('accommodation_types',[1,2,3]);


                }

                $buy = $buy->get();
                //dd($buy);
            }
            if ($type == 'rent') {
                $rent = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type', 5);
                if (isset($request->search_type) && $request->search_type == 'quick') {
                    $rent->when(isset($request->quick_search) && $request->quick_search != '', function ($q) use ($request) {
                        return $q->where('title', 'LIKE', '%' . $request->quick_search . '%');
                    });
                    $featured->when(isset($request->quick_search) && $request->quick_search != '', function ($q) use ($request) {
                        return $q->where('title', 'LIKE', '%' . $request->quick_search . '%');
                    });
                }
                if (isset($request->search_type) && $request->search_type == 'filter') {
                    $main_category = $request->main_category;
                    $sub_category = $request->sub_category;
                    $location = $request->location;
                    //$featured = [];

                    $rent = $rent->with('subCategory.mainCategory');

                    if ($request->dynamic_subs == "one") {
                        $ids = [];
                        $dat = Accommodation::where('status', 1)->get();
                        foreach ($request->dynamic_sub as $sub) {
                            foreach ($dat as $row) {
                                if (isset($row->dynamic_sub_ids)) {
                                    $abc = (in_array($sub, json_decode($row->dynamic_sub_ids))) ? $ids[] = $row->id : '';
                                }
                            }
                        }
                        //$datas_query =  Accommodation::whereIn('id', $ids);
                        //$buy=$buy->whereIn('id', $ids);
                        $rent = $rent->when($request->dynamic_subs == "one", function ($q) use ($ids) {
                            return $q->whereIn('id', $ids);
                        });
                        $featured = $featured->when($request->dynamic_subs == "one", function ($q) use ($ids) {
                            return $q->whereIn('id', $ids);
                        });
                    }

                    if ($main_category) {
                        $rent = $rent->whereHas('subCategory.mainCategory', function ($q) use ($main_category) {
                            $q->where('id', $main_category);
                        });
                        $featured = $featured->whereHas('subCategory.mainCategory', function ($q) use ($main_category) {
                            $q->where('id', $main_category);
                        });
                    }
                    if ($sub_category) {
                        $rent = $rent->where('sub_category_id', $sub_category)->where('status', 1);
                        $featured = $featured->where('sub_category_id', $sub_category)->where('status', 1);
                    }
                    if ($location) {
                        $rent = $rent->where('city_id', $location);
                        $featured = $featured->where('city_id', $location);
                    }
                    if ($request->quick_search) {
                        $rent = $rent->where('title', 'LIKE', '%' . $request->quick_search . '%');
                        $featured = $featured->where('title', 'LIKE', '%' . $request->quick_search . '%');
                    }
                    if ($request->sub_cate_id) {
                        $rent = $rent->where('sub_category_id', $request->sub_cate_id);
                        $featured = $featured->where('sub_category_id', $request->sub_cate_id);
                    }

                    if ($request->city != "all" && isset($request->city)) {
                        $city_ids = City::where('state_id', $request->city)->pluck('id')->toArray();
                        $rent =  $rent->where('city_id', $request->city);
                        $featured = $featured->where('city_id', $request->city);
                    }
                }

                $rent = $rent->get();
            }

            $featured = $featured->get();


            $main = MainCategory::where('major_category_id', 1)->pluck('id')->toArray();
            $subs = SubCategory::whereIn('main_category_id', $main)->get();

            $locations = City::all();
            $states = State::all();

            $categories = MainCategory::where('major_category_id', $major_category_id)->get();

            $venue_category = Accommodation::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
                ->groupBy("sub_category_id")->get();

            $dynamic_mains = DynamicMainCategory::where('major_category_id', $major_category_id)->get();

            $banner = SliderImage::where('major_category_id', $major_category_id)->get();

            $major_category = MajorCategory::find($major_category_id);

            $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
            $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();

            $categoryForSidebar = MainCategory::with('subCategory.accommodation')->withCount('accommodation')
                ->where('major_category_id', $major_category_id)->orderBy('accommodation_count', 'DESC')->take(10)->get();


            //$datas = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type', 1)->get();

            return view('user.properties.' . $type, get_defined_vars());
        }
    }
    public function accommodationViewMore($slug)
    {
        $venues = NULL;
        $data = Motors::with('subCategory', 'city', 'amenities', 'landmarks', 'approvedReviews')->where('slug', $slug)->first();
        $type = 'motor';
        //$more_info=MoreInfo::where(['module_id'=>$data->id,'module_name'=>'motors'])->get();
        $more_info = NULL;
        if (isset($data->id)) {
            $more_info = MoreInfo::where(['module_id' => $data->id, 'module_name' => 'motors'])->get();
        }
        $venues1 = [];
        $venue_category = Motors::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->where('accommodation_type', $data->accommodation_type)->groupBy("sub_category_id")->get();

        $similar = Motors::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('sub_category_id', $data->sub_category_id)->get();

        $events = [];
        $youtube = '';

        // Avg Rating
        $reviews = Motors::where('slug', $slug)->first();
        $avg_rating = round($reviews->approvedreviews()->avg('rating'), 1);
        $count = $data->views + 1;
        $data->views = $count;
        $data->timestamps = false;
        $data->save();



        $nearbyDistance = [];
        $recommended = ItemRecommendation::whereHasMorph('item', [Motors::class])->where('status', 1)->get();

        $nearby = [];
        foreach ($nearbyDistance  as $near) {
            $aminity = Motors::with('subCategory', 'city', 'amenities_amenity')->where('id', $near->id)->first();
            $aminity['distance'] = $near->distance;
            array_push($nearby, $aminity);
        }


        $whatsapp = MajorCategory::where('id', 9)->first();

        // $nearby = DB::table("accommodations")
        // ->select("accommodations.id"
        //     ,DB::raw("6371 * acos(cos(radians(" . $data->lat . "))
        //     * cos(radians(accommodations.lat))
        //     * cos(radians(accommodations.long) - radians(" . $data->long . "))
        //     + sin(radians(" .$data->lat. "))
        //     * sin(radians(accommodations.lat))) AS distance"), "accommodations.*", "sub_categories.name as sub_category",)

        //     // ->with('subCategory')
        //     ->join('sub_categories', 'sub_categories.id', 'accommodations.sub_category_id')
        //     ->where('accommodations.status', 1)
        //     ->groupBy("accommodations.id")
        //     ->orderBy('distance','ASC')
        //     ->get();
        // return $nearby;

        return view('user.motors.motor-view-more', get_defined_vars());
    }

    public function accommodationSearch(Request $request)
    {

        $buy = Accommodation::with('subCategory', 'city', 'amenities_amenity')
            ->when(isset($request->quick_search) && $request->quick_search != '', function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->quick_search . '%');
            })->where('status', 1)->where('accommodation_type', 4)->where('assign_featured', 1)->get();

        $rent = Accommodation::with('subCategory', 'city', 'amenities_amenity')
            ->when(isset($request->quick_search) && $request->quick_search != '', function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->quick_search . '%');
            })->where('status', 1)->where('accommodation_type', 5)->where('assign_featured', 1)->get();

        if ($request->sort) {
            $res = '1';
            $sort_by = $request->sort_by;

            $featured_query = Accommodation::with('subCategory', 'city')->where('status', 1)->where('assign_featured', 1);
            /*$featured2 = Accommodation::with('subCategory', 'city')->where('status', 1)->where('assign_featured', 1);
            $featured3 = Accommodation::with('subCategory', 'city')->where('status', 1)->where('assign_featured', 1);*/
            if ($sort_by == '1') {
                $featured1 = $featured_query->orderBy('created_at', 'desc');
                /*$featured2 = $featured->orderBy('created_at', 'desc');
                $featured3 = $featured->orderBy('created_at', 'desc');*/

                $featured2 = $featured1;
                $featured3 = $featured1;
            } elseif ($sort_by == '2') {
                $featured1 = $featured_query->orderBy('created_at', 'asc');
                $featured2 = $featured1;
                $featured3 = $featured1;
                /*$featured2 = $featured->orderBy('created_at', 'asc');
                $featured3 = $featured->orderBy('created_at', 'asc');*/
            } elseif ($sort_by == '3') {
                $featured1 = $featured_query->orderBy('title', 'desc');
                $featured2 = $featured1;
                $featured3 = $featured1;
                /*$featured2 = $featured->orderBy('title', 'desc');
                $featured3 = $featured->orderBy('title', 'desc');*/
            } elseif ($sort_by == '4') {
                $featured1 = $featured_query->orderBy('title', 'asc');
                $featured2 = $featured1;
                $featured3 = $featured1;
                /*$featured2 = $featured->orderBy('title', 'asc');
                $featured3 = $featured->orderBy('title', 'asc');*/
            }

            $featured = $featured_query->where('accommodation_type', 1)->get();
            $featured2 = $featured_query->where('accommodation_type', 2)->get();
            $featured3 = $featured_query->where('accommodation_type', 3)->get();


            $datas_query = Accommodation::with('subCategory', 'city')->where('status', 1);
            /*$datas2 = Accommodation::with('subCategory', 'city')->where('status', 1);
            $datas3 = Accommodation::with('subCategory', 'city')->where('status', 1);*/
            if ($sort_by == '1') {
                $datas1 = $datas_query->orderBy('created_at', 'desc');
                $datas2 = $datas1;
                $datas3 = $datas1;
                /*$datas2 = $datas->orderBy('created_at', 'desc');
                $datas3 = $datas->orderBy('created_at', 'desc');*/
            } elseif ($sort_by == '2') {
                $datas1 = $datas_query->orderBy('created_at', 'asc');
                $datas2 = $datas1;
                $datas3 = $datas1;
                /*$datas2 = $datas->orderBy('created_at', 'asc');
                $datas3 = $datas->orderBy('created_at', 'asc');*/
            } elseif ($sort_by == '3') {
                $datas1 = $datas_query->orderBy('title', 'desc');
                $datas2 = $datas1;
                $datas3 = $datas1;
                /*$datas2 = $datas->orderBy('title', 'desc');
                $datas3 = $datas->orderBy('title', 'desc');*/
            } elseif ($sort_by == '4') {
                $datas1 = $datas_query->orderBy('title', 'asc');
                $datas2 = $datas1;
                $datas3 = $datas1;
                /*$datas2 = $datas->orderBy('title', 'asc');
                $datas3 = $datas->orderBy('title', 'asc');*/
            }

            $datas = $datas_query->where('accommodation_type', 1)->get();
            $datas2 = $datas_query->where('accommodation_type', 2)->get();
            $datas3 = $datas_query->where('accommodation_type', 3)->get();


            $categories = MainCategory::where('major_category_id', 9)->get();

            $main = MainCategory::where('major_category_id', 9)->pluck('id')->toArray();
            $subs = SubCategory::whereIn('main_category_id', $main)->get();

            $locations = City::all();

            $venue_category = Accommodation::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
                ->groupBy("sub_category_id")
                ->get();
            $dynamic_mains = DynamicMainCategory::where('major_category_id', 9)->get();
            $banner = SliderImage::where('major_category_id', 9)->get();
            $major_category = MajorCategory::find(9);
            $states = State::all();
            $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
            $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();
            $categoryForSidebar = MainCategory::with('subCategory.accommodation')->withCount('accommodation')->where('major_category_id', 9)->orderBy('accommodation_count', 'DESC')->take(10)->get();

            return view('user.accommodation.accommodation', get_defined_vars());
            /*return view('user.accommodation.accommodation', compact('datas', 'featured', 'datas2', 'featured2', 'datas3', 'featured3', 'venue_category', 'categories', 'subs', 'sort_by', 'res', 'locations', 'dynamic_mains', 'banner', 'major_category', 'states', 'hot_trends', 'influencer_reviews', 'categoryForSidebar'));*/
        }

        $main_category = $request->main_category;
        $sub_category = $request->sub_category;
        $location = $request->location;
        $featured = [];
        $featured2 = [];
        $featured3 = [];

        $datas_query = Accommodation::with('subCategory.mainCategory', 'city');
        /*$datas2 = Accommodation::with('subCategory.mainCategory', 'city');
        $datas3 = Accommodation::with('subCategory.mainCategory', 'city');*/



        if ($request->dynamic_subs == "one") {
            $ids = [];
            $dat = Accommodation::where('status', 1)->get();
            foreach ($request->dynamic_sub as $sub) {
                foreach ($dat as $row) {
                    if (isset($row->dynamic_sub_ids)) {
                        $abc = (in_array($sub, json_decode($row->dynamic_sub_ids))) ? $ids[] = $row->id : '';
                    }
                }
            }
            $datas_query =  Accommodation::whereIn('id', $ids);
            /*$datas3 =  Accommodation::whereIn('id', $ids);
            $datas2 =  Accommodation::whereIn('id', $ids);*/
        }

        if ($main_category) {
            $datas_query = Accommodation::whereHas('subCategory.mainCategory', function ($q) use ($main_category) {
                $q->where('id', $main_category);
            });
            /*$datas2 = Accommodation::whereHas('subCategory.mainCategory', function ($q) use ($main_category) {
                $q->where('id', $main_category);
            });
            $datas3 = Accommodation::whereHas('subCategory.mainCategory', function ($q) use ($main_category) {
                $q->where('id', $main_category);
            });*/
        }
        if ($sub_category) {
            $datas1 = $datas_query->where('sub_category_id', $sub_category)->where('status', 1);
            $datas2 = $datas1;
            $datas3 = $datas1;
            /*$datas2 = $datas->where('sub_category_id', $sub_category)->where('status', 1);
            $datas3 = $datas->where('sub_category_id', $sub_category)->where('status', 1);*/
        }
        if ($location) {
            $datas1 = $datas_query->where('city_id', $location);
            $datas2 = $datas1;
            $datas3 = $datas1;
            /*$datas2 = $datas->where('city_id', $location);
            $datas3 = $datas->where('city_id', $location);*/
        }
        /*if($request->name) {
            $datas = $datas->where('title','LIKE','%'.$request->name.'%');
        }*/
        if ($request->quick_search) {
            $datas1 = $datas_query->where('title', 'LIKE', '%' . $request->quick_search . '%');
            $datas2 = $datas1;
            $datas3 = $datas1;
            /*$datas2 = $datas->where('title', 'LIKE', '%' . $request->quick_search . '%');
            $datas3 = $datas->where('title', 'LIKE', '%' . $request->quick_search . '%');*/
        }
        if ($request->sub_cate_id) {
            $datas1 = $datas_query->where('sub_category_id', $request->sub_cate_id);
            $datas2 = $datas1;
            $datas3 = $datas1;
            /*$datas2 = $datas->where('sub_category_id', $request->sub_cate_id);
            $datas3 = $datas->where('sub_category_id', $request->sub_cate_id);*/
        }

        if ($request->city != "all" && isset($request->city)) {
            $city_ids = City::where('state_id', $request->city)->pluck('id')->toArray();
            $datas1 =  $datas_query->whereIn('city_id', $city_ids);
            $datas2 =  $datas1;
            $datas3 =  $datas1;
            /*$datas2 =  $datas->whereIn('city_id', $city_ids);
            $datas3 =  $datas->whereIn('city_id', $city_ids);*/
        }
        $datas = $datas_query->where('accommodation_type', 1)->get();
        $datas2 = $datas_query->where('accommodation_type', 2)->get();
        $datas3 = $datas_query->where('accommodation_type', 3)->get();

        $main = MainCategory::where('major_category_id', 1)->pluck('id')->toArray();
        $subs = SubCategory::whereIn('main_category_id', $main)->get();

        $locations = City::all();
        $states = State::all();
        $categories = MainCategory::where('major_category_id', 9)->get();
        $venue_category = Accommodation::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        $dynamic_mains = DynamicMainCategory::where('major_category_id', 9)->get();
        $banner = SliderImage::where('major_category_id', 9)->get();
        $major_category = MajorCategory::find(9);
        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        $influencer_reviews = InfluencerReview::where('status', 1)->limit(3)->get();
        $categoryForSidebar = MainCategory::with('subCategory.accommodation')->withCount('accommodation')->where('major_category_id', 9)->orderBy('accommodation_count', 'DESC')->take(10)->get();

        return view('user.accommodation.accommodation', get_defined_vars());
        /*return view('user.accommodation.accommodation', compact('datas', 'categoryForSidebar','datas2', 'datas3', 'featured', 'featured2', 'featured3', 'venue_category', 'categories', 'subs', 'locations', 'dynamic_mains', 'banner', 'major_category', 'states','hot_trends','influencer_reviews'));*/
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function motorA($page)
    {
        if ($page == 'list-view') {
            $justJoin = [];
            return view('user.motors.motor_a', get_defined_vars());
        } else if ($page == 'thumb-view') {
            $justJoin = [];
            return view('user.motors.motor_b', get_defined_vars());
        } else if ($page == 'map-view') {
            $justJoin = [];
            return view('user.motors.motor_c', get_defined_vars());
        }
    }
}
