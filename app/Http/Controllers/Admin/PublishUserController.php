<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\MajorCategory;
use Illuminate\Support\Facades\Validator;

class PublishUserController extends Controller
{
    //

    public function index(){

        $users = User::where('user_type','=','4')->orderby('id','desc')->get();

        $major_cateogry = MajorCategory::all();
        return view('admin.publisher_user.index',compact('users','major_cateogry'));
    }

    public function edit($id){

        $user = User::find($id);

        $roles = Role::where('name','LIKE','%publisher%')->get();

        return view('admin.publisher_user.edit',compact('user','roles'));
    }

    public function publish_user_update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email,'.$id,
            'name' => 'required',
            'status' => 'required',
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
        $obj->status = $request->input('status');
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
        return redirect()->back()->with($message);

    }


}
