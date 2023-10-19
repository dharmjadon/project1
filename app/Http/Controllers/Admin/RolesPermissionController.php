<?php

namespace App\Http\Controllers\Admin;

use DB;
use Arr;
use App\Models\User;
use App\Models\Review;
use App\Models\JobUsers;
use App\Models\JobApplied;
use App\Models\EnquireForm;
use App\Models\Newslettter;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class RolesPermissionController extends Controller
{


    function __construct()
    {
        $this->middleware('role_or_permission:Admin|user-manage', ['only' =>
      ['create_permission','save_permission','create_role','save_role','create_user','users','user_edit','user_update','save_user']]);


    }


    //

    public function create_permission(){

        // dd("Not Allowed");
        return view('admin.role_and_permission.create_permission');
    }

    public function save_permission(Request $request){



        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name',
        ]);

        if ($validator->fails()) {
            $validate = $validator->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];
            return back()->with($message);
        }

        $role = Permission::firstOrCreate(['name' => $request->input('name')]);

        $message = [
            'message' => "Permisison has been created successfully",
            'alert-type' => 'success'
        ];
        return back()->with($message);
    }


    public function create_role(){

        $permissions = Permission::all();

        return view('admin.role_and_permission.create_role',compact('permissions'));
    }

    public function save_role(Request $request){


        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            $validate = $validator->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];
            return back()->with($message);
        }


        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        $message = [
            'message' => "Role has been Added",
            'alert-type' => 'success',
        ];

        return back()->with($message);

    }

    public function create_user(){
        $roles = Role::all();
        return view('admin.role_and_permission.create-user',compact('roles'));
    }

    public function users(){
        $users = User::where('user_type','=','1')->get();
        return view('admin.role_and_permission.all-users',compact('users'));
    }

    public function job_seeker_user(){
        $users = User::where('user_type','=','2')->get();
        return view('admin.role_and_permission.job-seeker-users',compact('users'));
    }

    public function client_user(){
        $users = User::where('user_type','=','3')->get();
        return view('admin.role_and_permission.all-client-users',compact('users'));
    }

    public function newsletter_list(){
        $users = Newslettter::orderby('id','desc')->get();
        return view('admin.role_and_permission.all-newsletter',compact('users'));
    }

    public function guest_users(){



       $reviews =  DB::table('reviews')
            ->select('id','name', 'email')
            ->groupBy('email')
            ->get();

      $enquieres = DB::table('enquire_forms')
            ->select('id','name', 'email')
            ->groupBy('email')
            ->get();


            // $results  = $reviews->merge($enquieres);



        return view('admin.role_and_permission.all-guest-user',compact('reviews','enquieres'));
    }

    public function delete_reviews_user(Request $request)
    {
        $obj = Review::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function delete_enquieres_user(Request $request)
    {
        $obj = EnquireForm::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }



    public function all_newsletter(){
        $users = User::where('user_type','=','2')->get();
        return view('admin.role_and_permission.job-seeker-users',compact('users'));
    }

    public function user_edit($id){

         $user =  User::find($id);

         $roles = Role::all();

        return view('admin.role_and_permission.edit-user',compact('user','roles'));
    }

    public function user_update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email,'.$id,
            'name' => 'required',
            // 'password' => 'required',
            'roles' => 'required',
        ]);

        if ($validator->fails()) {
            $validate = $validator->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];
            return back()->with($message);
        }


        $obj = User::find($id);
        $obj->name = $request->input('name');
        $obj->email = $request->input('email');
        if(isset($request->password)){
            $obj->password = bcrypt($request->password);
        }
        if(isset($obj->user_type)){
            $obj->user_type = $obj->user_type;
        }
        else{
            $obj->user_type = 1;
        }
        $obj->email_verified_at = Carbon::now();
        $obj->save();

        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $obj->assignRole($request->input('roles'));



        $message = [
            'message' => "User has been created successfully",
            'alert-type' => 'success',
        ];
        return redirect()->route('admin.users')->with($message);

    }

    public function save_user(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email',
            'name' => 'required',
            'password' => 'required',
            'roles' => 'required',
        ]);

        if ($validator->fails()) {
            $validate = $validator->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];
            return back()->with($message);
        }


        $obj= new User();
        $obj->name = $request->input('name');
        $obj->email = $request->input('email');
        $obj->password = bcrypt($request->input('password'));
        $obj->user_type = "1";
        $obj->email_verified_at = Carbon::now();
        $obj->save();

        $obj->assignRole($request->input('roles'));

        $message = [
            'message' => "User has been created successfully",
            'alert-type' => 'success',
        ];
        return back()->with($message);


    }

    public function delete_admin_user(Request $request){

        $id = $request->id;

        $user = User::find($id);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->delete();

        $message = [
            'message' => "User has been deleted successfully",
            'alert-type' => 'success',
        ];
        return back()->with($message);


    }

    public function delete_jobseeker_user(Request $request){

        $id = $request->id;

        $user = User::find($id);
        $user->delete();

         $job_user  =JobUsers::Where('user_id','=',$id)->first();
         $job_user->delete();

         DB::table('job_applieds')->where('user_id','=',$id)->delete();

        $message = [
            'message' => "User has been deleted successfully",
            'alert-type' => 'success',
        ];
        return back()->with($message);


    }





    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            $validate = $validator->errors();
            $message_password = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];
            return back()->with($message_password);
        }

        if($request->profile_pic) {
            $profile_pic = '';
            $profile_pic = rand(100,100000).'.'.time().'.'.$request->profile_pic->extension();
            $menuPath = config('app.upload_other_path') . $profile_pic;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->profile_pic));
        }

        $obj = User::find($request->id);
        $obj->email = $request->email;
        $obj->mobile_no = $request->mobile;
        $obj->address = $request->address ??$obj->address;
        $obj->company_name = $request->company_name;
        if($request->file('profile_pic')) {
            $obj->profile_picture = $profile_pic;
        }
        $obj->save();

        $message_password = [
            'message' => "Profile Updated successfully",
            'alert-type' => 'success',
        ];
        return back()->with($message_password);
    }

    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required:min|4',
            'repeat_password' => 'required|min:4',
            'new_password' => 'required|same:repeat_password',
        ]);

        if ($validator->fails()) {
            $validate = $validator->errors();
            $message_password = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];
            return back()->with($message_password);
        }

        $user = User::find($request->id);
        if (Hash::check($request->current_password, $user->password)) {

            $user->password = Hash::make($request->new_password);
            $user->save();

            $message_password = [
                'message' => "Password Changed successfully",
                'alert-type' => 'success',
            ];
            return back()->with($message_password);
        }else{
            $message_password = [
                'message' => "Current Password Is Worng",
                'alert-type' => 'error',
            ];
            return back()->with($message_password);
        }

    }




}

