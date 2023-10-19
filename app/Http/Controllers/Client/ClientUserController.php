<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\EnquireForm;
use App\Models\Job;
use App\Models\JobApplied;
use App\Models\Review;
use App\Models\Skill;
use App\Models\WishListDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientUserController extends Controller
{
    //

    public function enquiry(){

        $user_id  = Auth::user()->id;
        $datas = EnquireForm::where('created_by','=',$user_id)->get();

        return view('client.enquire.all_enquire',compact('datas'));
    }

    public function review(){
        $user_id  = Auth::user()->id;
        $datas = Review::where('created_by','=',$user_id)->get();
        return view('client.review.all_review',compact('datas'));
    }

    public function wishlist(){

    }


    public function applied_job(){

         $applied_job_array = JobApplied::where('user_id','=',Auth::user()->id)->pluck('job_id')->toArray();

        $jobs  = Job::whereIn('id',$applied_job_array)->get();
        return view('client.applied-jobs.all_applied_jobs',compact('jobs'));
    }

    public function applied_detail($id){

        $job = Job::find($id);

        $user_id = Auth::user()->id;

        $applied_details =  isset($job->applied_candidate)  ?  $job->applied_candidate->where('user_id',$user_id)->first() : null;

        $skills = Skill::all();

        $array_status = array("Pending","Selected","Interviewed","Shortlisted","Rejected");

        return view('client.applied-jobs.applied_detail',compact('job','skills','applied_details','array_status'));

    }


    public function reservation_details(){

    }

    public function client_wishlist(){

        $user_id = Auth::user()->id;
        $datas= WishListDetails::with('item')->orderBy('created_at', 'DESC')->where('created_by','=',$user_id)->get();
        return view('client.wishlist.all_wishlist',compact('datas'));
    }

    public function delete_wishlist(Request $request){

        $obj = WishListDetails::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Removed from wishlist',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);

    }

    public function update_wishlist_notification(Request $request){


        $obj =  WishListDetails::find($request->id);
        $obj->is_notification_need = $request->status;
        $obj->save();


    }





}
