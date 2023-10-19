<?php

namespace App\Http\Controllers\User;

use App\Events\MyEvent;
use App\Models\City;
use App\Models\Skill;
use App\Models\State;
use App\Models\Nationality;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplied;
use App\Models\JobUsers;
use App\Models\NotificationsInfo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    //

    public function job_seeker_register(){

        $regions = State::all();

        $cities = City::all();
        $national = Nationality::all();

        $skils = Skill::all();

        // dd($national);

        return view('user.job-seeker.register',compact('regions','cities','national','skils'));
    }


    public function profile(){

        $regions = State::all();

        $cities = City::all();
        $national = Nationality::all();
        $skils = Skill::all();

        $user = Auth::user();

        // dd($user->job_user);

        return view('user.job-seeker.profile',compact('regions','cities','national','skils','user'));
    }





    public function save_job_register(Request $request){



        $validator =  Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'profile_image' => 'mimes:jpeg,jpg,png,PNG,gif',
            'cv' => 'mimes:pdf,docx',
            'g-recaptcha-response' => 'required|recaptchav3:fld_recaptcha,0.5'
        ]);
        if($validator->fails()) {
            $validate = $validator->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];
            return redirect()->back()->with($message);
        }



             //profile image upload
             if(!empty($_FILES['cv']['name'])) {

                $feature_image_extension = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
                $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
                $menuPath = config('app.upload_other_path') . $feature_image_name;
                Storage::disk('s3')->put($menuPath, file_get_contents($request->icon));
                $feature_cv_path = $feature_image_name;
            }


          //profile image upload
          if(!empty($_FILES['profile_image']['name'])) {

            $feature_image_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
            $menuPath = config('app.upload_other_path') . $feature_image_name;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->profile_image));
            $feature_profile_path =  $feature_image_name;
        }




        $reg = new JobUsers();
        $reg->frist_name = $request->first_name;
        $reg->last_name = $request->last_name;
        $reg->headline  = $request->headline;
        $reg->current_position   = $request->current_position;
        $reg->portfolio_link = $request->portfolio_link;
        $reg->website_link = $request->website;
        $reg->nationality =  $request->national;
        $reg->region_id = $request->region;
        $reg->city_id = $request->area;

        $reg->email = $request->email;
        $reg->mobile =  $request->mobile;
        $reg->date_of_birth =  $request->date_of_birth;
        $reg->skill =  json_encode($request->skill,true);
        if(isset($feature_cv_path)){
            $reg->cv =  $feature_cv_path;
        }

        if(isset($feature_profile_path)){
            $reg->profile_image =  $feature_profile_path;
        }
        $reg->cover_letter =  $request->cover_letter;
        $reg->save();

        $last_id = $reg->id;

        $user = new User();
        $user->name =  $request->first_name." ".$request->last_name;
        $user->email =  $request->email;
        $user->password =  bcrypt($request->password);
        $user->user_type =  "2";
        $user->save();

        $user_id = $user->id;

         $job_user = JobUsers::find($last_id);
         $job_user->user_id = $user_id;
         $job_user->update();




        $message = [
            'message' => "Account has been create successfully",
            'alert-type' => 'success',
        ];
        return redirect()->back()->with($message);


    }

    public function apply_job(Request $request,$id){

        if(Auth::check()){

            if($request->current_cv){

                $validator =  Validator::make($request->all(), [
                    'application_cover' => 'required',
                    'g-recaptcha-response' => 'required|recaptchav3:fld_recaptcha,0.5'
                ]);
                if($validator->fails()) {
                    $validate = $validator->errors();
                    $message = [
                        'message' => $validate->first(),
                        'alert-type' => 'error',
                        'error' => $validate->first()
                    ];
                    return redirect()->back()->with($message);
                }

                $user_current = Auth::user();

                $if_already_applied = JobApplied::where('user_id','=',$user_current->id)
                                                ->where('job_id','=',$id)->first();
                 if($if_already_applied != null){

                    $message = [
                        'message' => "already applied",
                        'alert-type' => 'error',
                    ];
                    return redirect()->back()->with($message);
                 }



                $job_applied = new JobApplied();
                $job_applied->user_type = "1";
                $job_applied->job_id =  $id;
                $job_applied->user_id = $user_current->id;
                $job_applied->applied_cv =  $user_current->job_user->cv;
                $job_applied->save();


                //sending notifcation
                $job_detail  = Job::find($id);

                $url_now = route('admin.applied_candidate',$id);
                $url_now_publisher = route('publisher.applied_candidate',$id);
                $this->send_notfication_job_to_admin_and_publisher("job",$url_now,"0");
                if($job_detail->is_publisher=="1"){
                    $this->send_notfication_job_to_admin_and_publisher("job",$url_now_publisher,"1",$job_detail->created_by);
                }


                $message = [
                    'message' => "applied successfully",
                    'alert-type' => 'success',
                ];
                return redirect()->back()->with($message);


            }else{ //if not apply by current cv

                $validator =  Validator::make($request->all(), [
                    'application_cover' => 'required',
                    'cv' => 'required|mimes:pdf,docx',
                ]);
                if($validator->fails()) {
                    $validate = $validator->errors();
                    $message = [
                        'message' => $validate->first(),
                        'alert-type' => 'error',
                        'error' => $validate->first()
                    ];
                    return redirect()->back()->with($message);
                }

                $user_current = Auth::user();

                $if_already_applied = JobApplied::where('user_id','=',$user_current->id)
                ->where('job_id','=',$id)->first();
                    if($if_already_applied != null){

                    $message = [
                    'message' => "already applied",
                    'alert-type' => 'error',
                    ];
                    return redirect()->back()->with($message);
                 }

                     //feature image upload
                if(!empty($_FILES['cv']['name'])) {

                    $feature_image_extension = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
                    $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
                    $menuPath = config('app.upload_other_path') . $$feature_image_name;
                    Storage::disk('s3')->put($menuPath, file_get_contents($request->cv));
                    $feature_cv_path = $feature_image_name;
                }

                $job_applied = new JobApplied();
                $job_applied->user_type = "1";
                $job_applied->job_id =  $id;
                $job_applied->user_id = $user_current->id;
                if($feature_cv_path){
                    $job_applied->applied_cv =  $feature_cv_path;
                }else{
                    $job_applied->applied_cv =  $user_current->job_user->cv;
                }

                $job_applied->save();

                $job_user_already = JobUsers::where('user_id','=',Auth::user()->id)->first();

                if($job_user_already==null){

                    $reg = new JobUsers();
                    $reg->frist_name =  Auth::user()->name;
                    $reg->last_name =  Auth::user()->name;
                    $reg->email =  Auth::user()->email;
                    $reg->mobile = Auth::user()->mobile_no;
                    if(isset($feature_cv_path)){
                        $reg->cv =  $feature_cv_path;
                    }
                    $reg->cover_letter =  $request->application_cover;
                    $reg->user_id =  Auth::user()->id;
                    $reg->skill =  '[]';
                    $reg->save();

                    $job_applied->first_name = Auth::user()->name;
                    $job_applied->last_name = Auth::user()->name;
                    $job_applied->last_name =  Auth::user()->mobile_no;
                    $job_applied->last_name =  Auth::user()->email;

                }





                //sending notifcation
                $job_detail  = Job::find($id);

                $url_now = route('admin.applied_candidate',$id);
                $url_now_publisher = route('publisher.applied_candidate',$id);
                $this->send_notfication_job_to_admin_and_publisher("job",$url_now,"0");
                if($job_detail->is_publisher=="1"){
                    $this->send_notfication_job_to_admin_and_publisher("job",$url_now_publisher,"1",$job_detail->created_by);
                }



                $message = [
                    'message' => "applied successfully",
                    'alert-type' => 'success',
                ];
                return redirect()->back()->with($message);





            }

        }else{ //if guest user


            $validator =  Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:job_applieds,email|unique:users,email',
                'nation' => 'required',
                'date_of_birth' => 'required',
                'phone' => 'required',
                'application_cover' => 'required',
                'password' => 'required',
                'cv' => 'required|mimes:pdf,docx',


            ]);
            if($validator->fails()) {
                $validate = $validator->errors();

                // dd($validate);
                $message = [
                    'message' => $validate->first(),
                    'alert-type' => 'error',
                    'error' => $validate->first()
                ];
                return redirect()->back()->with($message);
            }

              //feature image upload
          if(!empty($_FILES['cv']['name'])) {

            $feature_image_extension = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
            $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
            $menuPath = config('app.upload_other_path') . $feature_image_name ;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->cv));
            $feature_cv_path = $feature_image_name;
        }

            $job_applied = new JobApplied();
            $job_applied->user_type = "2";
            $job_applied->job_id =  $id;
            $job_applied->first_name =  $request->first_name;
            $job_applied->last_name =  $request->last_name;
            if($feature_cv_path){
                $job_applied->applied_cv =  $feature_cv_path;
            }
            $job_applied->phone = $request->phone;
            $job_applied->email = $request->email;
            $job_applied->date_of_birth =  $request->date_of_birth;
            $job_applied->nation =  $request->nation;
            $job_applied->cover_letter = $request->application_cover;
            $job_applied->save();

            $job_applied_last = $job_applied->id;



            $reg = new JobUsers();
            $reg->frist_name =  $request->first_name;
            $reg->last_name =  $request->last_name;
            $reg->email =  $request->email;
            $reg->mobile = $request->phone;
            $reg->date_of_birth =  $request->date_of_birth;
            if(isset($feature_cv_path)){
                $reg->cv =  $feature_cv_path;
            }
            $reg->cover_letter =  $request->application_cover;
            $reg->skill =  '[]';
            $reg->save();



        $last_id = $reg->id;

        $user = new User();
        $user->name =  $request->first_name." ".$request->last_name;
        $user->email =  $request->email;
        $user->password =  bcrypt($request->password);
        $user->user_type =  "2";
        $user->save();

        $user_id = $user->id;

         $job_user = JobUsers::find($last_id);
         $job_user->user_id = $user_id;
         $job_user->update();

          $job_applied = JobApplied::find($job_applied_last);
          $job_applied->user_id = $user_id;
          $job_applied->update();


          //sending notifcation
          $job_detail  = Job::find($id);

          $url_now = route('admin.applied_candidate',$id);
          $url_now_publisher = route('publisher.applied_candidate',$id);
          $this->send_notfication_job_to_admin_and_publisher("job",$url_now,"0");
          if($job_detail->is_publisher=="1"){
              $this->send_notfication_job_to_admin_and_publisher("job",$url_now_publisher,"1",$job_detail->created_by);
          }

          $message = [
              'message' => "applied successfully",
              'alert-type' => 'success',
          ];
          return redirect()->back()->with($message);



            // $message = [
            //     'message' => "applied successfully and account created",
            //     'alert-type' => 'success',
            // ];
            // return redirect()->back()->with($message);


        }

    }

    public function update_job_register(Request $request, $id){


        $validator =  Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
            'profile_image' => 'mimes:jpeg,jpg,png,PNG,gif',
            'cv' => 'mimes:pdf,docx',
        ]);
        if($validator->fails()) {
            $validate = $validator->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];
            return redirect()->back()->with($message);
        }



          //profile image upload
          if(!empty($_FILES['profile_image']['name'])) {

            $feature_image_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
            $menuPath = config('app.upload_other_path') . $feature_image_name ;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->profile_image));
            $feature_profile_path = $feature_image_name;
        }



           //feature image upload
           if(!empty($_FILES['cv']['name'])) {

            $feature_image_extension = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
            $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
            $menuPath = config('app.upload_other_path') . $feature_image_name ;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->cv));
            $feature_cv_path = $feature_image_name;
          }

        $reg = JobUsers::where('user_id','=',$id)->first();
        $reg->frist_name = $request->first_name;
        $reg->last_name = $request->last_name;
        $reg->headline  = $request->headline;
        $reg->current_position   = $request->current_position;
        $reg->portfolio_link = $request->portfolio_link;
        $reg->website_link = $request->website;
        $reg->nationality =  $request->national;
        $reg->region_id = $request->region;
        $reg->city_id = $request->area;

        // $reg->email = $request->email;
        $reg->mobile =  $request->mobile;
        $reg->date_of_birth =  $request->date_of_birth;
        $reg->skill =  json_encode($request->skill,true);
        if(isset($feature_cv_path )){
            $reg->cv =  $feature_cv_path;
        }

        if(isset($feature_profile_path)){
            $reg->profile_image =  $feature_profile_path;
        }




        $reg->cover_letter =  $request->cover_letter;
        $reg->save();


        $message = [
            'message' =>  "Profile has been updated",
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);



    }


    public function ajax_render_area(Request $request){

        $sub_cat = City::where('state_id','=',$request->select_v)->get();
        echo json_encode($sub_cat);
        exit;

    }


    public function send_notfication_job_to_admin_and_publisher($category_from,$url_now,$to_whom,$created_by=null)
    {

      if(!isset($created_by)){
        $created_by = 0;
      }

            $description_event = "Applied for job";
            $message_event = "New Application From ".$category_from;
            $url_now = $url_now;

            event(new MyEvent($message_event,$description_event,$url_now,$to_whom,$created_by));

            $notification = new NotificationsInfo();
            $notification->title = $message_event;
            $notification->description =  $description_event;
            $notification->notification_for = $to_whom;
            $notification->url = $url_now;
            $notification->notify_to = $created_by;
            $notification->save();
    }
}


