<?php

namespace App\Http\Controllers\User;

use DB;
use Log;
use App\Models\Blog;
use App\Models\City;
use App\Models\News;
use App\Models\State;
use App\Models\Venue;
use App\Models\Events;
use App\Models\BuySell;
use App\Models\Gallery;
use App\Models\Concierge;
use App\Models\Directory;
use App\Models\MoreInfo;
use App\Models\SliderImage;
use App\Models\SubCategory;
use App\Models\MainCategory;
use App\Models\Accommodation;
use App\Models\MajorCategory;
use Illuminate\Http\Request;
use App\Models\InfluencerReview;
use App\Models\ItemRecommendation;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use App\Models\HomeTrendBanner;

class AccommodationController extends Controller
{

    public function accommodation()
    {


        $buy = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type',4)->where('assign_featured', 1)->get();
        //dd($buy);
        $rent = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type',5)->where('assign_featured', 1)->get();

       /* $featured = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type', 1)->where('assign_featured', 1)->get();*/


        /*$featured2 = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type', 2)->where('assign_featured', 1)->get();
        $datas2 = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type', 2)->get();

        $featured3 = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type', 3)->where('assign_featured', 1)->get();
        $datas3 = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type', 3)->get();*/

        $datas = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type', 1)->get();
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
        $influencer_reviews = InfluencerReview::where('status',1)->limit(3)->get();
        $categoryForSidebar = MainCategory::with('subCategory.accommodation')->withCount('accommodation')->where('major_category_id', 9)->orderBy('accommodation_count', 'DESC')->take(10)->get();

        /*return view('user.accommodation.accommodation', compact('datas', 'featured', 'datas2', 'featured2', 'datas3', 'featured3', 'venue_category', 'categories', 'subs', 'locations', 'dynamic_mains', 'banner', 'major_category', 'states','hot_trends', 'influencer_reviews', 'categoryForSidebar'));*/

        return view('user.accommodation.accommodation',get_defined_vars());

    }
    public function vehicle($type=NULL,Request $request)
    {
        if($type!=NULL)
        {

        }
    }
    public function property($type=NULL,Request $request)
    {
        $major_category_id=config('global.space_major_id');

        if($type!=NULL)
        {

            $accommodation_type=($type=='buy')?4:5;
            $featured = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type',$accommodation_type)->where('assign_featured', 1);



            if($type=='buy')
            {
                //dd($request->dynamic_sub);
                //\DB::enableQueryLog();
                $buy = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)
                ->where('accommodation_type',$accommodation_type);
                if(isset($request->search_type) && $request->search_type=='quick')
                {
                    $buy->when(isset($request->quick_search) && $request->quick_search!='',function($q) use($request)
                    {
                        return $q->where('title', 'LIKE', '%' . $request->quick_search . '%');
                    });
                    $featured->when(isset($request->quick_search) && $request->quick_search!='',function($q) use($request)
                    {
                        return $q->where('title', 'LIKE', '%' . $request->quick_search . '%');
                    });
                }
                if ($request->sub_category_id)
                {
                    $buy = $buy->where('sub_category_id', $request->sub_category_id)->where('status', 1);
                }
                if(isset($request->search_type) && $request->search_type=='filter')
                {

                    $main_category = $request->main_category;
                    $sub_category = $request->sub_category;
                    $location = $request->location;
                    //$featured = [];

                    $buy = $buy->with('subCategory.mainCategory');

                    if ($request->dynamic_subs == "one")
                    {
                       //$buy->whereNotNull('dynamic_sub_ids')->orWhereRaw('FIND_IN_SET("'. implode ( ",",$request->dynamic_sub) .'",dynamic_sub_ids)');
                       //$featured->whereNotNull('dynamic_sub_ids')->orWhereRaw('FIND_IN_SET("'. implode ( ",",$request->dynamic_sub) .'",dynamic_sub_ids)');
                        $ids = [];
                        $dat = Accommodation::where('status', 1)->where('accommodation_type',$accommodation_type)->get();
                        foreach ($request->dynamic_sub as $sub)
                        {
                          foreach ($dat as $row)
                          {
                            if(isset($row->dynamic_sub_ids))
                            {
                             $abc = (in_array($sub, json_decode($row->dynamic_sub_ids))) ? $ids[] = $row->id : '';
                            }
                          }
                        }

                        $buy=$buy->when($request->dynamic_subs == "one",function($q)use($ids)
                        {
                            return $q->whereIn('id', $ids);
                        });
                        $featured->when($request->dynamic_subs == "one",function($q)use($ids){
                            return $q->whereIn('id', $ids);
                        });
                    }
                    if(isset($request->minimum_price) && isset($request->max_price))
                    {
                        $buy->whereBetween('price', [$request->minimum_price,$request->max_price]);
                    }
                    if ($main_category)
                    {
                        $buy = $buy->whereHas('subCategory.mainCategory', function ($q) use ($main_category)
                        {
                            $q->where('id', $main_category);
                        });
                        $featured = $featured->whereHas('subCategory.mainCategory', function ($q) use ($main_category)
                        {
                            $q->where('id', $main_category);
                        });
                    }
                    if ($sub_category)
                    {
                       $buy=$buy->where('sub_category_id', $sub_category)->where('status', 1);
                       $featured=$featured->where('sub_category_id', $sub_category)->where('status', 1);

                    }
                    if ($location)
                    {
                        $buy= $buy->where('city_id', $location);
                        $featured= $featured->where('city_id', $location);
                    }
                    if ($request->quick_search)
                    {
                        $buy = $buy->where('title', 'LIKE', '%' . $request->quick_search . '%');
                        $featured =$featured->where('title', 'LIKE', '%' . $request->quick_search . '%');
                    }
                    if ($request->sub_cate_id)
                    {
                        $buy = $buy->where('sub_category_id', $request->sub_cate_id);
                        $featured =$featured->where('sub_category_id', $request->sub_cate_id);

                    }

                    if ($request->city != "all" && isset($request->city))
                    {
                        $city_ids = City::where('state_id', $request->city)->pluck('id')->toArray();
                        $buy =  $buy->where('city_id', $request->city);
                        $featured =$featured->where('city_id', $request->city);
                    }
                    //$buy = $buy->whereIn('accommodation_types',[1,2,3]);


                }

                if($request->quick_search){
                    $buy = $buy->where('title','LIKE','%'.$request->quick_search.'%');
                }

                if(isset($request->sort_by)){
                    if($request->sort_by == "1"){
                        $buy = $buy->orderBy('created_at', 'desc');
                    }
                    if($request->sort_by == "2"){
                        $buy = $buy->orderBy('created_at', 'asc');
                    }
                    if($request->sort_by == "3"){
                        $buy = $buy->orderBy('title', 'asc');
                    }
                    if($request->sort_by == "4"){
                        $buy = $buy->orderBy('title', 'desc');
                    }
                }

                $buy = $buy->get();
                //dd($buy);
                //Log::info('buy filter query '.json_encode(\DB::getQueryLog()));
            }
            if($type=='rent')
            {
                $rent = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type',$accommodation_type);
                if(isset($request->search_type) && $request->search_type=='quick')
                {
                    $rent->when(isset($request->quick_search) && $request->quick_search!='',function($q) use($request)
                    {
                        return $q->where('title', 'LIKE', '%' . $request->quick_search . '%');
                    });
                    $featured->when(isset($request->quick_search) && $request->quick_search!='',function($q) use($request)
                    {
                        return $q->where('title', 'LIKE', '%' . $request->quick_search . '%');
                    });
                }
                {
                    $rent = $rent->where('sub_category_id', $request->sub_category_id)->where('status', 1);
                }
                if(isset($request->search_type) && $request->search_type=='filter')
                {
                    $main_category = $request->main_category;
                    $sub_category = $request->sub_category;
                    $location = $request->location;
                    //$featured = [];

                    $rent = $rent->with('subCategory.mainCategory');

                    if ($request->dynamic_subs == "one")
                    {
                        //$rent->whereNotNull('dynamic_sub_ids')->orWhereRaw('FIND_IN_SET("'. implode ( ",",$request->dynamic_sub) .'",dynamic_sub_ids)');
                        //$featured->whereNotNull('dynamic_sub_ids')->orWhereRaw('FIND_IN_SET("'. implode ( ",",$request->dynamic_sub) .'",dynamic_sub_ids)');
                        $ids = [];
                        $dat = Accommodation::where('status', 1)->where('accommodation_type',$accommodation_type)->get();
                        foreach ($request->dynamic_sub as $sub)
                        {
                          foreach ($dat as $row)
                          {
                            if (isset($row->dynamic_sub_ids))
                            {
                             $abc = (in_array($sub, json_decode($row->dynamic_sub_ids))) ? $ids[] = $row->id : '';
                            }
                          }
                        }

                        $rent=$rent->when($request->dynamic_subs == "one",function($q)use($ids){
                            return $q->whereIn('id', $ids);
                        });
                        $featured=$featured->when($request->dynamic_subs == "one",function($q)use($ids){
                            return $q->whereIn('id', $ids);
                        });
                    }
                    if(isset($request->minimum_price) && isset($request->max_price))
                    {
                        $buy->whereBetween('price', [$request->minimum_price,$request->max_price]);
                    }
                    if ($main_category)
                    {
                        $rent = $rent->whereHas('subCategory.mainCategory', function ($q) use ($main_category)
                        {
                            $q->where('id', $main_category);
                        });
                        $featured = $featured->whereHas('subCategory.mainCategory', function ($q) use ($main_category)
                        {
                            $q->where('id', $main_category);
                        });
                    }
                    if ($sub_category)
                    {
                       $rent=$rent->where('sub_category_id', $sub_category)->where('status', 1);
                       $featured=$featured->where('sub_category_id', $sub_category)->where('status', 1);

                    }
                    if ($location)
                    {
                        $rent= $rent->where('city_id', $location);
                        $featured= $featured->where('city_id', $location);
                    }
                    if ($request->quick_search)
                    {
                        $rent = $rent->where('title', 'LIKE', '%' . $request->quick_search . '%');
                        $featured =$featured->where('title', 'LIKE', '%' . $request->quick_search . '%');
                    }
                    if ($request->sub_cate_id)
                    {
                        $rent = $rent->where('sub_category_id', $request->sub_cate_id);
                        $featured =$featured->where('sub_category_id', $request->sub_cate_id);

                    }

                    if ($request->city != "all" && isset($request->city))
                    {
                        $city_ids = City::where('state_id', $request->city)->pluck('id')->toArray();
                        $rent =  $rent->where('city_id', $request->city);
                        $featured =$featured->where('city_id', $request->city);

                    }
                }
                if($request->quick_search){
                    $rent = $rent->where('title','LIKE','%'.$request->quick_search.'%');
                }

                if(isset($request->sort_by)){
                    if($request->sort_by == "1"){
                        $rent = $rent->orderBy('created_at', 'desc');
                    }
                    if($request->sort_by == "2"){
                        $rent = $rent->orderBy('created_at', 'asc');
                    }
                    if($request->sort_by == "3"){
                        $rent = $rent->orderBy('title', 'asc');
                    }
                    if($request->sort_by == "4"){
                        $rent = $rent->orderBy('title', 'desc');
                    }
                }

                $rent = $rent->get();

            }

            $featured=$featured->get();


            $main = MainCategory::where('major_category_id', 1)->pluck('id')->toArray();
            $subs = SubCategory::whereIn('main_category_id', $main)->get();

            $locations = City::all();
            $states = State::all();

            $categories = MainCategory::where('major_category_id', $major_category_id)->get();

            $venue_category = Accommodation::where('status', '=', '1')->where('accommodation_type', $accommodation_type)->select('sub_category_id', DB::raw('count(*) as total'))
                ->groupBy("sub_category_id")->get();

            $dynamic_mains = DynamicMainCategory::where('major_category_id', $major_category_id)->get();

            $banner = SliderImage::where('major_category_id', $major_category_id)->get();

            $major_category = MajorCategory::find($major_category_id);

            $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
            $influencer_reviews = InfluencerReview::where('status',1)->limit(3)->get();

            $categoryForSidebar = MainCategory::with('subCategory.accommodation')->withCount('accommodation')
            ->where('major_category_id', $major_category_id)->orderBy('accommodation_count', 'DESC')->take(10)->get();
            $allCategory = MajorCategory::with(['mainCategory','mainCategory.subCategory']);
            $home_trend = HomeTrendBanner::all();

            //$datas = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('accommodation_type', 1)->get();



           return view('user.properties.'.$type,get_defined_vars());
        }

    }
    public function accommodationViewMore($slug)
    {

        $venues = Accommodation::with('subCategory', 'city', 'amenities_amenity')->get();
        $data = Accommodation::with('subCategory', 'city', 'amenities', 'landmarks', 'approvedReviews')->where('slug', $slug)
        ->first();

        $type='space';
        $more_info=NULL;
        if(isset($data->id))
        {
          $more_info=MoreInfo::where(['module_id'=>$data->id,'module_name'=>'space'])->get();
        }

        if(isset($data->lat)){

            $venues1 = Accommodation::with('subCategory', 'city', 'amenities_amenity')->select(DB::raw("6371 * acos(cos(radians(" . $data->lat . "))
            * cos(radians(accommodations.lat))
            * cos(radians(accommodations.long) - radians(" . $data->long . "))
            + sin(radians(" . $data->lat . "))
            * sin(radians(accommodations.lat))) AS distance"))->get();
        }else{
            $venues1 = [];
        }

        $similar = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('status', 1)->where('sub_category_id', $data->sub_category_id)->get();
        $events = Events::where('date_time', '>', now())->orderBy('date_time', 'ASC')->take(3)->get();
        $venue_category = Accommodation::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }
        // Avg Rating
        $reviews = Accommodation::where('slug', $slug)->first();
        $avg_rating = round($reviews->approvedreviews()->avg('rating'), 1);
        $count = $data->views + 1;
        $data->views= $count;
        $data->timestamps = false;
        $data->save();


        if(isset($data->lat)){

            $nearbyDistance = DB::table("accommodations")
            ->select(
                "accommodations.id",
                DB::raw("6371 * acos(cos(radians(" . $data->lat . "))
            * cos(radians(accommodations.lat))
            * cos(radians(accommodations.long) - radians(" . $data->long . "))
            + sin(radians(" . $data->lat . "))
            * sin(radians(accommodations.lat))) AS distance"),
                "accommodations.*",
                "sub_categories.name as sub_category",
            )

            // ->with('subCategory')
            ->join('sub_categories', 'sub_categories.id', 'accommodations.sub_category_id')
            ->where('accommodations.status', 1)
            ->groupBy("accommodations.id")
            ->orderBy('distance', 'ASC')
            ->get();

        }else{
            $nearbyDistance = [];
        }

        $recommended = ItemRecommendation::whereHasMorph('item', [Accommodation::class])->where('status', 1)->get();

        $nearby = [];
        foreach ($nearbyDistance  as $near) {
            $aminity = Accommodation::with('subCategory', 'city', 'amenities_amenity')->where('id', $near->id)->first();
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
        return view('user.accommodation.accommodation-view-more',get_defined_vars());
        /*return view('user.accommodation.accommodation-view-more', compact('data','whatsapp', 'venues', 'similar', 'nearby', 'events', 'venue_category', 'avg_rating', 'youtube', 'recommended'));*/
    }

    public function accommodationSearch(Request $request)
    {

        $buy = Accommodation::with('subCategory','city','amenities_amenity')
        ->when(isset($request->quick_search) && $request->quick_search!='',function($q)use($request)
        {
            $q->where('title', 'LIKE', '%' . $request->quick_search . '%');
        })->where('status', 1)->where('accommodation_type',4)->where('assign_featured', 1)->get();

        $rent = Accommodation::with('subCategory','city','amenities_amenity')
        ->when(isset($request->quick_search) && $request->quick_search!='',function($q)use($request)
        {
            $q->where('title', 'LIKE', '%' . $request->quick_search . '%');
        })->where('status', 1)->where('accommodation_type',5)->where('assign_featured', 1)->get();

        if ($request->sort)
        {
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
                $datas1= $datas_query->orderBy('title', 'desc');
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
            $influencer_reviews = InfluencerReview::where('status',1)->limit(3)->get();
            $categoryForSidebar = MainCategory::with('subCategory.accommodation')->withCount('accommodation')->where('major_category_id', 9)->orderBy('accommodation_count', 'DESC')->take(10)->get();

            return view('user.accommodation.accommodation',get_defined_vars());
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
            $datas1= $datas_query->where('city_id', $location);
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
        $influencer_reviews = InfluencerReview::where('status',1)->limit(3)->get();
        $categoryForSidebar = MainCategory::with('subCategory.accommodation')->withCount('accommodation')->where('major_category_id', 9)->orderBy('accommodation_count', 'DESC')->take(10)->get();

        return view('user.accommodation.accommodation',get_defined_vars());
        /*return view('user.accommodation.accommodation', compact('datas', 'categoryForSidebar','datas2', 'datas3', 'featured', 'featured2', 'featured3', 'venue_category', 'categories', 'subs', 'locations', 'dynamic_mains', 'banner', 'major_category', 'states','hot_trends','influencer_reviews'));*/
    }
}
