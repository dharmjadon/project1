<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Models\Venue;
use App\Models\Events;
use App\Models\BuySell;
use App\Models\Concierge;
use App\Models\Directory;
use App\Models\BookArtist;
use App\Models\Influencer;
use App\Models\EnquireForm;
use App\Models\Accommodation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class pubEnquiryController extends Controller
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
        $book_artist_id_array = BookArtist::where('created_by','=',Auth::id())->pluck('id')->toArray();


        $datas= EnquireForm::with('item')->orderBy('created_at', 'DESC')->get();
        return view('publisher.enquiry.index',compact('datas','venues_id_array','events_id_array','buysell_id_array','directoires_id_array','concierges_id_array','infuencer_id_array','spaces_id_array', 'book_artist_id_array'));

    }


    public function ajax_render_table(Request $request){


        if($request->module_name=="venue"){

            $venues_id_array = Venue::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Venue')->whereIn('item_id', $venues_id_array)->orderBy('created_at', 'DESC')->get();

            $view = view('publisher.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="event"){

            $event_id_array = Events::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Events')->whereIn('item_id', $event_id_array)->orderBy('created_at', 'DESC')->get();
            $view = view('publisher.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);
        }elseif($request->module_name=="buysell"){

            $buysell_id_array = BuySell::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\BuySell')->whereIn('item_id', $buysell_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="directory"){

            $directoires_id_array = Directory::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Directory')->whereIn('item_id', $directoires_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="concierge"){

            $concierges_id_array = Concierge::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Concierge')->whereIn('item_id', $concierges_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="influencer"){

            $infuencer_id_array = Influencer::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Influencer')->whereIn('item_id', $infuencer_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="accomdation"){

            $spaces_id_array = Accommodation::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Accommodation')->whereIn('item_id', $spaces_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="bookartist"){

            $book_artist_id_array = BookArtist::where('created_by','=',Auth::id())->pluck('id')->toArray();
            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\BookArtist')->whereIn('item_id', $book_artist_id_array)->orderBy('created_at', 'DESC')->get();        //    dd($datas);

            $view = view('publisher.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //

        $obj = EnquireForm::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Review deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
