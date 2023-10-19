<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Review;
use App\Models\EnquireForm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EnquiryController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|enquiry-view', ['only' => ['enquiry']]);

    }

    public function enquiry()
    {

        $datas= EnquireForm::with('item')->orderBy('created_at', 'DESC')->get();
        $buysell_type = array("","Buy","Rent");


        return view('admin.enquiry.index',compact('datas','buysell_type'));

    }

    public function enquiry_ajax_tabs_table(Request $request){


        if($request->module_name=="venue"){


            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Venue')->orderBy('created_at', 'DESC')->get();

            $view = view('admin.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="event"){


            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Events')->orderBy('created_at', 'DESC')->get();
            $view = view('admin.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);
        }elseif($request->module_name=="buysell"){


            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\BuySell')->orderBy('created_at', 'DESC')->get();

            $view = view('admin.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="directory"){


            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Directory')->orderBy('created_at', 'DESC')->get();

            $view = view('admin.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="concierge"){


            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Concierge')->orderBy('created_at', 'DESC')->get();

            $view = view('admin.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="influencer"){


            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Influencer')->orderBy('created_at', 'DESC')->get();

            $view = view('admin.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="accomdation"){


            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Accommodation')->orderBy('created_at', 'DESC')->get();

            $view = view('admin.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="attraction"){


            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Attraction')->orderBy('created_at', 'DESC')->get();

            $view = view('admin.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }

        elseif($request->module_name=="bookartist"){


            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\BookArtist')->orderBy('created_at', 'DESC')->get();


            $view = view('admin.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="ticket"){


            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Tickets')->orderBy('created_at', 'DESC')->get();

            $view = view('admin.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="motor"){


            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Motors')->orderBy('created_at', 'DESC')->get();

            $view = view('admin.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }
        elseif($request->module_name=="crypto"){


            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Crypto')->orderBy('created_at', 'DESC')->get();

            $view = view('admin.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);
        }

        elseif($request->module_name=="talent"){


            $datas= EnquireForm::with('item')->where('item_type', 'App\Models\Talents')->orderBy('created_at', 'DESC')->get();

            $view = view('admin.enquiry.ajax_tabs_table',compact('datas'))->render();
            return response()->json(['html'=>$view]);

        }


    }

    public function todays_enquiry_and_review()
    {
        $enquries= EnquireForm::with('item')->whereDate('created_at',Carbon::today()->toDateString())->get();
        $reviews = Review::with('reviewable')->whereDate('created_at',Carbon::today()->toDateString())->get();
        $auth_user_type = Auth::user()->user_type;
        if($auth_user_type!=1){
            $enquries  =  $enquries->where('item.created_by',Auth::id());
            $reviews  =  $reviews->where('item.created_by',Auth::id());
        }
        return view('admin.enquiry.todays_enquiry',compact('enquries','reviews'));
    }

    public function destroy(Request $request)
    {
        $obj = EnquireForm::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Review deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
