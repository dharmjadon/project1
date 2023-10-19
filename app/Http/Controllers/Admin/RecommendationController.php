<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\MyEvent;
use App\Models\City;
use App\Models\MoreInfo;
use App\Models\Recommendation;
use App\Models\Accommodation;
use App\Models\Motors;
use App\Models\Amenties;
use App\Models\Landmark;
use App\Models\Amenitable;
use App\Models\BuySell;
use App\Models\Concierge;
use App\Models\Directory;
use App\Models\SubCategory;
use App\Models\Landmarkable;
use App\Models\MainCategory;
use Illuminate\Support\Str;
use App\Models\MajorCategory;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Models\Events;
use App\Models\NotificationsInfo;
use App\Models\Venue;
use App\Models\WishListDetails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Helper;
use Log;
use App\Models\Education;
use App\Models\Influencer;
use App\Models\Attraction;
use App\Models\Crypto;

class RecommendationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        
        $venue = Venue::pluck('id')->toArray();
        $events = Events::pluck('id')->toArray();
        $buySell = BuySell::pluck('id')->toArray();
        $directory = Directory::pluck('id')->toArray();
        $education = Education::pluck('id')->toArray();
        $influencer = Influencer::pluck('id')->toArray();
        $crypto = Crypto::pluck('id')->toArray();
        $attraction = Attraction::pluck('id')->toArray();

        $concierge = Concierge::pluck('id')->toArray();
        $space = Accommodation::pluck('id')->toArray();
        $motor = Motors::pluck('id')->toArray();

        $venue_data = Recommendation::with('venueModule')->whereIn('module_id', $venue)->where('module_type', 'venue')
            ->orderBy('id', 'DESC')->get();

        $event_data = Recommendation::with('eventModule')->whereIn('module_id', $events)->where('module_type', 'event')
            ->orderBy('id', 'DESC')->get();

        $buysell_data = Recommendation::with('buySellModule')->whereIn('module_id', $buySell)->where('module_type', 'buysell')
            ->orderBy('id', 'DESC')->get();

        $directory_data = Recommendation::with('directoryModule')->whereIn('module_id', $directory)->where('module_type', 'directory')
            ->orderBy('id', 'DESC')->get();

        $concierge_data = Recommendation::with('conciergeModule')->whereIn('module_id', $concierge)->where('module_type', 'concierge')
            ->orderBy('id', 'DESC')->get();

        $space_data = Recommendation::with('spaceModule')->whereIn('module_id', $space)->where('module_type', 'space')
            ->orderBy('id', 'DESC')->get();

        $motor_data = Recommendation::with('motorsModule')->whereIn('module_id', $motor)->where('module_type', 'motor')
            ->orderBy('id', 'DESC')->get();

        $education_data = Recommendation::with('educationModule')->whereIn('module_id', $education)->where('module_type', 'education')
            ->orderBy('id', 'DESC')->get();

        $crypto_data = Recommendation::with('cryptoModule')->whereIn('module_id', $crypto)->where('module_type', 'crypto')
            ->orderBy('id', 'DESC')->get();

        $attraction_data = Recommendation::with('attractionModule')->whereIn('module_id', $attraction)->where('module_type', 'attraction')
            ->orderBy('id', 'DESC')->get();

        $influencer_data = Recommendation::with('influencerModule')->whereIn('module_id', $influencer)->where('module_type', 'influencer')
            ->orderBy('id', 'DESC')->get();

        $venue_data = (!empty($venue_data)) ? $venue_data : NULL;
        $event_data = (!empty($event_data)) ? $event_data : NULL;
        $buysell_data = (!empty($buysell_data)) ? $buysell_data : NULL;
        $directory_data = (!empty($directory_data)) ? $directory_data : NULL;
        $concierge_data = (!empty($concierge_data)) ? $concierge_data : NULL;
        $space_data = (!empty($space_data)) ? $space_data : NULL;
        $motor_data = (!empty($motor_data)) ? $motor_data : NULL;
        $education_data = (!empty($education_data)) ? $education_data : NULL;
        $crypto_data = (!empty($crypto_data)) ? $crypto_data : NULL;
        $influencer_data = (!empty($influencer_data)) ? $influencer_data : NULL;
        $attraction_data = (!empty($attraction_data)) ? $attraction_data : NULL;

        return view('admin.recommendation.index', get_defined_vars());
    }

    public function recommendation_ajax_tab(Request $request){

            if($request->ajax()){

                $space = Accommodation::pluck('id')->toArray();
                $motor = Motors::pluck('id')->toArray();
                $venue = Venue::pluck('id')->toArray();
                $events = Events::pluck('id')->toArray();
                $buySell = BuySell::pluck('id')->toArray();
                $directory = Directory::pluck('id')->toArray();
                $concierge = Concierge::pluck('id')->toArray();
                $education = Education::pluck('id')->toArray();
                $influencer = Influencer::pluck('id')->toArray();
                $crypto = Crypto::pluck('id')->toArray();
                $attraction = Attraction::pluck('id')->toArray();


                $type = $request->module_name;
                if($type=='education')
                {
                    $data = Recommendation::with('educationModule')->whereIn('module_id', $education)->where('module_type', 'education')->orderBy('id', 'DESC')->get();

                    $view = view('admin.recommendation.career-table.recommend-ajax-tab',compact('data','type'))->render();
                    return response()->json(['html'=>$view]);
                }
                if($type=='influencer')
                {
                    $data = Recommendation::with('influencerModule')->whereIn('module_id', $influencer)->where('module_type', 'influencer')->orderBy('id', 'DESC')->get();

                    $view = view('admin.recommendation.career-table.recommend-ajax-tab',compact('data','type'))->render();
                    return response()->json(['html'=>$view]);
                }
                if($type=='crypto')
                {
                    $data = Recommendation::with('cryptoModule')->whereIn('module_id', $crypto)->where('module_type', 'crypto')->orderBy('id', 'DESC')->get();

                    $view = view('admin.recommendation.career-table.recommend-ajax-tab',compact('data','type'))->render();
                    return response()->json(['html'=>$view]);
                }
                if($type=='attraction')
                {
                    $data = Recommendation::with('attractionModule')->whereIn('module_id', $attraction)->where('module_type', 'attraction')->orderBy('id', 'DESC')->get();

                    $view = view('admin.recommendation.career-table.recommend-ajax-tab',compact('data','type'))->render();
                    return response()->json(['html'=>$view]);
                }
                if($type=='venue')
                {
                    $data = Recommendation::with('venueModule')->whereIn('module_id', $venue)->where('module_type', 'venue')->orderBy('id', 'DESC')->get();

                    $view = view('admin.recommendation.career-table.recommend-ajax-tab',compact('data','type'))->render();
                    return response()->json(['html'=>$view]);
                }

             if($type=='event')
             {

                $data = Recommendation::with('eventModule')->whereIn('module_id', $events)->where('module_type', 'event')
                ->orderBy('id', 'DESC')->get();

                $view = view('admin.recommendation.career-table.recommend-ajax-tab',compact('data','type'))->render();
                return response()->json(['html'=>$view]);
             }
             if($type=='buysell')
             {


                $data = Recommendation::with('buySellModule')->whereIn('module_id', $buySell)->where('module_type', 'buysell')
                ->orderBy('id', 'DESC')->get();

                $view = view('admin.recommendation.career-table.recommend-ajax-tab',compact('data','type'))->render();
                return response()->json(['html'=>$view]);


             }
             if($type=='directory')
             {

                $data = Recommendation::with('directoryModule')->whereIn('module_id', $directory)->where('module_type', 'directory')
                ->orderBy('id', 'DESC')->get();

                $view = view('admin.recommendation.career-table.recommend-ajax-tab',compact('data','type'))->render();
                return response()->json(['html'=>$view]);


             }
             if($type=='concierge')
             {

                $data = Recommendation::with('conciergeModule')->whereIn('module_id', $concierge)->where('module_type', 'concierge')
                ->orderBy('id', 'DESC')->get();

                $view = view('admin.recommendation.career-table.recommend-ajax-tab',compact('data','type'))->render();
                return response()->json(['html'=>$view]);
             }
             if($type=='space')
             {

                $data = Recommendation::with('spaceModule')->whereIn('module_id', $space)->where('module_type', 'space')
            ->orderBy('id', 'DESC')->get();

            $view = view('admin.recommendation.career-table.recommend-ajax-tab',compact('data','type'))->render();
            return response()->json(['html'=>$view]);


             }
             elseif($type=='motor')
             {
                        $data = Recommendation::with('motorsModule')->whereIn('module_id', $motor)->where('module_type', 'motor')
                        ->orderBy('id', 'DESC')->get();

                        $view = view('admin.recommendation.career-table.recommend-ajax-tab',compact('data','type'))->render();
                        return response()->json(['html'=>$view]);
             }



            }
    }

}
