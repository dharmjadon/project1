<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\BuySell;
use App\Models\Concierge;
use App\Models\Directory;
use App\Models\Events;
use App\Models\Influencer;
use App\Models\NotificationsInfo;
use App\Models\Review;
use App\Models\Venue;
use App\Models\WishListDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class pubReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $venues_id_array = Venue::where('created_by','=',Auth::id())->pluck('id')->toArray();
        $events_id_array = Events::where('created_by','=',Auth::id())->pluck('id')->toArray();
        $buysell_id_array = BuySell::where('created_by','=',Auth::id())->pluck('id')->toArray();
        $directoires_id_array = Directory::where('created_by','=',Auth::id())->pluck('id')->toArray();
        $concierges_id_array = Concierge::where('created_by','=',Auth::id())->pluck('id')->toArray();
        $infuencer_id_array = Influencer::where('created_by','=',Auth::id())->pluck('id')->toArray();
        $spaces_id_array = Accommodation::where('created_by','=',Auth::id())->pluck('id')->toArray();

        $datas= Review::with('reviewable')->orderBy('created_at', 'DESC')->get();
        return view('publisher.review.index',compact('datas','spaces_id_array','infuencer_id_array','concierges_id_array','directoires_id_array','venues_id_array','events_id_array','buysell_id_array'));

    }


    public function ajax_render_table(Request $request){



        if($request->module_name=="venue"){

            $venues_id_array = Venue::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= Review::with('reviewable')->where('reviewable_type', 'App\Models\Venue')->whereIn('reviewable_id', $venues_id_array)->orderBy('created_at', 'DESC')->get();

            $view = view('publisher.review.ajax_review_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="event"){

            $event_id_array = Events::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= Review::with('reviewable')->where('reviewable_type', 'App\Models\Events')->whereIn('reviewable_id', $event_id_array)->orderBy('created_at', 'DESC')->get();
            $view = view('publisher.review.ajax_review_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);
        }elseif($request->module_name=="buysell"){

            $buysell_id_array = BuySell::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= Review::with('reviewable')->where('reviewable_type', 'App\Models\BuySell')->whereIn('reviewable_id', $buysell_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.review.ajax_review_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="directory"){

            $directoires_id_array = Directory::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= Review::with('reviewable')->where('reviewable_type', 'App\Models\Directory')->whereIn('reviewable_id', $directoires_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.review.ajax_review_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="concierge"){

            $concierges_id_array = Concierge::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= Review::with('reviewable')->where('reviewable_type', 'App\Models\Concierge')->whereIn('reviewable_id', $concierges_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.review.ajax_review_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="influencer"){

            $infuencer_id_array = Influencer::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= Review::with('reviewable')->where('reviewable_type', 'App\Models\Influencer')->whereIn('reviewable_id', $infuencer_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.review.ajax_review_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="accomdation"){

            $spaces_id_array = Accommodation::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= Review::with('reviewable')->where('reviewable_type', 'App\Models\Accommodation')->whereIn('reviewable_id', $spaces_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.review.ajax_review_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }

    }





    public function approveReview(Request $request)
    {
        $obj = Review::find($request->id);
        $obj->approved = $request->status;
        $obj->save();

        $message = [
            'message' => 'Review Approved Successfully',
            'alert-type' => 'success'
        ];
        return response()->json($message);

    }

    public function wishlists(){



        $venues_id_array = Venue::where('created_by','=',Auth::id())->pluck('id')->toArray();
        $events_id_array = Events::where('created_by','=',Auth::id())->pluck('id')->toArray();
        $buysell_id_array = BuySell::where('created_by','=',Auth::id())->pluck('id')->toArray();
        $directoires_id_array = Directory::where('created_by','=',Auth::id())->pluck('id')->toArray();
        $concierges_id_array = Concierge::where('created_by','=',Auth::id())->pluck('id')->toArray();
        $infuencer_id_array = Influencer::where('created_by','=',Auth::id())->pluck('id')->toArray();
        $spaces_id_array = Accommodation::where('created_by','=',Auth::id())->pluck('id')->toArray();


        $datas= WishListDetails::with('item')->orderBy('created_at', 'DESC')->get();

        return view('publisher.wishlist.wishlist',compact('datas','venues_id_array','events_id_array','buysell_id_array','directoires_id_array','concierges_id_array','infuencer_id_array','spaces_id_array'));

    }

    public function wishlist_ajax_render_table(Request $request){

         $module_name = $request->module_name ;

        if($request->module_name=="venue"){

            $venues_id_array = Venue::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= WishListDetails::with('item')->where('item_type', 'App\Models\Venue')->whereIn('item_id', $venues_id_array)->orderBy('created_at', 'DESC')->get();

            $view = view('publisher.wishlist.wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="event"){

            $event_id_array = Events::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= WishListDetails::with('item')->where('item_type', 'App\Models\Events')->whereIn('item_id', $event_id_array)->orderBy('created_at', 'DESC')->get();
            $view = view('publisher.wishlist.wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
            return response()->json(['html'=>$view]);
        }elseif($request->module_name=="buysell"){

            $buysell_id_array = BuySell::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= WishListDetails::with('item')->where('item_type', 'App\Models\BuySell')->whereIn('item_id', $buysell_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.wishlist.wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="directory"){

            $directoires_id_array = Directory::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= WishListDetails::with('item')->where('item_type', 'App\Models\Directory')->whereIn('item_id', $directoires_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.wishlist.wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="concierge"){

            $concierges_id_array = Concierge::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= WishListDetails::with('item')->where('item_type', 'App\Models\Concierge')->whereIn('item_id', $concierges_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.wishlist.wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="influencer"){

            $infuencer_id_array = Influencer::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= WishListDetails::with('item')->where('item_type', 'App\Models\Influencer')->whereIn('item_id', $infuencer_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.wishlist.wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="accomdation"){

            $spaces_id_array = Accommodation::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= WishListDetails::with('item')->where('item_type', 'App\Models\Accommodation')->whereIn('item_id', $spaces_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.wishlist.wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
            return response()->json(['html'=>$view]);

        }


    }


    public function own_wishlist_ajax_render_table(Request $request){

        $module_name = $request->module_name;

        $user_id = Auth::user()->id;

       if($request->module_name=="venue"){


           $datas= WishListDetails::with('item')->where('item_type', 'App\Models\Venue')->where('created_by','=',$user_id)->orderBy('created_at', 'DESC')->get();


           $view = view('publisher.wishlist.own_wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
           return response()->json(['html'=>$view]);

       }elseif($request->module_name=="event"){


           $datas= WishListDetails::with('item')->where('item_type', 'App\Models\Events')->where('created_by','=',$user_id)->orderBy('created_at', 'DESC')->get();

           $view = view('publisher.wishlist.own_wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
           return response()->json(['html'=>$view]);
       }elseif($request->module_name=="buysell"){


           $datas= WishListDetails::with('item')->where('item_type', 'App\Models\BuySell')->where('created_by','=',$user_id)->orderBy('created_at', 'DESC')->get();

           $view = view('publisher.wishlist.own_wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
           return response()->json(['html'=>$view]);

       }elseif($request->module_name=="directory"){


           $datas= WishListDetails::with('item')->where('item_type', 'App\Models\Directory')->where('created_by','=',$user_id)->orderBy('created_at', 'DESC')->get();

           $view = view('publisher.wishlist.own_wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
           return response()->json(['html'=>$view]);

       }elseif($request->module_name=="concierge"){

           $datas= WishListDetails::with('item')->where('item_type', 'App\Models\Concierge')->where('created_by','=',$user_id)->orderBy('created_at', 'DESC')->get();

           $view = view('publisher.wishlist.own_wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
           return response()->json(['html'=>$view]);

       }elseif($request->module_name=="influencer"){


           $datas= WishListDetails::with('item')->where('item_type', 'App\Models\Influencer')->where('created_by','=',$user_id)->orderBy('created_at', 'DESC')->get();

           $view = view('publisher.wishlist.own_wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
           return response()->json(['html'=>$view]);

       }elseif($request->module_name=="accomdation"){


           $datas= WishListDetails::with('item')->where('item_type', 'App\Models\Accommodation')->where('created_by','=',$user_id)->orderBy('created_at', 'DESC')->get();

           $view = view('publisher.wishlist.own_wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
           return response()->json(['html'=>$view]);

       }elseif($request->module_name=="gallery"){


        $datas= WishListDetails::with('item')->where('item_type', 'App\Models\Gallery')->where('created_by','=',$user_id)->orderBy('created_at', 'DESC')->get();

        $view = view('publisher.wishlist.own_wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
        return response()->json(['html'=>$view]);

    }elseif($request->module_name=="news"){


        $datas= WishListDetails::with('item')->where('item_type', 'App\Models\News')->where('created_by','=',$user_id)->orderBy('created_at', 'DESC')->get();

        $view = view('publisher.wishlist.own_wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
        return response()->json(['html'=>$view]);

    }elseif($request->module_name=="recommendation"){


        $datas= WishListDetails::with('item')->where('item_type', 'App\Models\ItemRecommendation')->where('created_by','=',$user_id)->orderBy('created_at', 'DESC')->get();

        $view = view('publisher.wishlist.own_wishlist_ajax_tabs_table',compact('datas','module_name'))->render();
        return response()->json(['html'=>$view]);

    }





   }



    public function own_wishlists(){



        $user_id = Auth::user()->id;
        $datas= WishListDetails::with('item')->orderBy('created_at', 'DESC')->where('created_by','=',$user_id)->get();

        return view('publisher.wishlist.own-wishlits',compact('datas'));

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function ajax_publisher_notification(Request $request) {

        $notifcations = NotificationsInfo::where('read_status','=','0')
                                        ->where('notify_to','=',Auth::user()->id)
                                        ->orderby('id','desc')->get();
        return response()->json($notifcations, 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {
        $obj = Review::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Review deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
