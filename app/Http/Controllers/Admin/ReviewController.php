<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NotificationsInfo;
use App\Models\WishListDetails;

class ReviewController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|view-review', ['only' => ['review']]);
        $this->middleware('role_or_permission:Admin|review-approve', ['only' => ['approveReview']]);

    }


    public function review()
    {

        $datas= Review::with('reviewable')->orderBy('created_at', 'DESC')->get();
        return view('admin.review.index',compact('datas'));

    }

    public  function reviews_ajax_tabs_table(Request $request){

        if($request->ajax()){


            if($request->module_name=="venue"){

                $datas=  Review::with('reviewable')->where('reviewable_type', 'App\Models\Venue')->orderBy('created_at', 'DESC')->get();



                $view = view('admin.review.review-ajax-tab',compact('datas'))->render();
                return response()->json(['html'=>$view]);



            }elseif($request->module_name=="event"){

                $datas=  Review::with('reviewable')->where('reviewable_type', 'App\Models\Events')->orderBy('created_at', 'DESC')->get();

                $view = view('admin.review.review-ajax-tab',compact('datas'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="buysell"){


                $datas=  Review::with('reviewable')->where('reviewable_type','App\Models\BuySell')->orderBy('created_at', 'DESC')->get();



                $view = view('admin.review.review-ajax-tab',compact('datas'))->render();
                return response()->json(['html'=>$view]);


            }elseif($request->module_name=="directory"){

                $datas=  Review::with('reviewable')->where('reviewable_type', 'App\Models\Directory')->orderBy('created_at', 'DESC')->get();

                $view = view('admin.review.review-ajax-tab',compact('datas'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="concierge"){
                $datas=  Review::with('reviewable')->where('reviewable_type', 'App\Models\Concierge')->orderBy('created_at', 'DESC')->get();

                $view = view('admin.review.review-ajax-tab',compact('datas'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="influencer"){
                $datas=  Review::with('reviewable')->where('reviewable_type',  'App\Models\Influencer')->orderBy('created_at', 'DESC')->get();

                $view = view('admin.review.review-ajax-tab',compact('datas'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="accommodation"){
                $datas=  Review::with('reviewable')->where('reviewable_type', 'App\Models\Accommodation')->orderBy('created_at', 'DESC')->get();

                $view = view('admin.review.review-ajax-tab',compact('datas'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="attraction"){
                // dd("in attraction");
                $datas=  Review::with('reviewable')->where('reviewable_type', 'App\Models\Attraction')->orderBy('created_at', 'DESC')->get();
                    // dd($datas);
                $view = view('admin.review.review-ajax-tab',compact('datas'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="motors"){
                $datas=  Review::with('reviewable')->where('reviewable_type', 'App\Models\Motors')->orderBy('created_at', 'DESC')->get();

                $view = view('admin.review.review-ajax-tab',compact('datas'))->render();
                return response()->json(['html'=>$view]);

            }
            elseif($request->module_name=="crypto"){
                $datas=  Review::with('reviewable')->where('reviewable_type', 'App\Models\Crypto')->orderBy('created_at', 'DESC')->get();

                $view = view('admin.review.review-ajax-tab',compact('datas'))->render();
                return response()->json(['html'=>$view]);

            }
            elseif($request->module_name=="talent"){
                $datas=  Review::with('reviewable')->where('reviewable_type', 'App\Models\Talents')->orderBy('created_at', 'DESC')->get();

                $view = view('admin.review.review-ajax-tab',compact('datas'))->render();
                return response()->json(['html'=>$view]);

            }

        }

    }


    public  function wishlists(){


        $datas= WishListDetails::with('item')->orderBy('created_at', 'DESC')->get();
        return view('admin.wishlist.wishlist',compact('datas'));
    }

    public function wishlist_ajax_tab(Request $request){

        if($request->ajax()){


            $module_name = $request->module_name;
            if($request->module_name=="venue"){


                $datas= WishListDetails::with('item')->where('item_type','App\Models\Venue')->orderBy('created_at', 'DESC')->get();

                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="event"){



                $datas= WishListDetails::with('item')->where('item_type','App\Models\Events')->orderBy('created_at', 'DESC')->get();


                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="buysell"){




                $datas= WishListDetails::with('item')->where('item_type','App\Models\BuySell')->orderBy('created_at', 'DESC')->get();

                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();


                return response()->json(['html'=>$view]);


            }elseif($request->module_name=="directory"){



                $datas= WishListDetails::with('item')->where('item_type','App\Models\Directory')->orderBy('created_at', 'DESC')->get();


                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="concierge"){


                $datas= WishListDetails::with('item')->where('item_type','App\Models\Concierge')->orderBy('created_at', 'DESC')->get();


                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="influencer"){


                $datas= WishListDetails::with('item')->where('item_type','App\Models\Influencer')->orderBy('created_at', 'DESC')->get();


                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="accomdation"){


                $datas= WishListDetails::with('item')->where('item_type','App\Models\Accommodation')->orderBy('created_at', 'DESC')->get();


                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="gallery"){
                // dd("in attraction");


                $datas= WishListDetails::with('item')->where('item_type','App\Models\Gallery')->orderBy('created_at', 'DESC')->get();
                    // dd($datas);

                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="recommend"){



                $datas= WishListDetails::with('item')->where('item_type','App\Models\ItemRecommendation')->orderBy('created_at', 'DESC')->get();


                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="news"){

                $datas= WishListDetails::with('item')->where('item_type','App\Models\News')->orderBy('created_at', 'DESC')->get();
                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();
                return response()->json(['html'=>$view]);

            }
            elseif($request->module_name=="motor"){

                $datas= WishListDetails::with('item')->where('item_type','App\Models\Motors')->orderBy('created_at', 'DESC')->get();
                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="crypto"){

                $datas= WishListDetails::with('item')->where('item_type','App\Models\Crypto')->orderBy('created_at', 'DESC')->get();
                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="attraction"){

                $datas= WishListDetails::with('item')->where('item_type','App\Models\Attraction')->orderBy('created_at', 'DESC')->get();
                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();
                return response()->json(['html'=>$view]);

            }elseif($request->module_name=="talent"){

                $datas= WishListDetails::with('item')->where('item_type','App\Models\Talents')->orderBy('created_at', 'DESC')->get();
                $view = view('admin.wishlist.wishlist-ajax-tab',compact('datas','module_name'))->render();
                return response()->json(['html'=>$view]);

            }



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


    public function ajax_admin_notification(Request $request) {

        $notifcations = NotificationsInfo::where('read_status','=','0')->where('notification_for','=','0')
                                        ->orderby('id','desc')->get();
        return response()->json($notifcations, 200);
    }


}
