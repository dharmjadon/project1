<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    protected $redirectTo = "/admin/dashboard";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }



    protected function login(Request $request)
    {


        $password_user = $request->password;



        $user = User::where('email','=',$request->email)->first();

        if($user != null){

            if (Auth::attempt(['email' => $request->email, 'password' => $password_user])) {

                if($user->user_type=="2" || $user->user_type=="3"){
                    return  redirect("/client/applied-job");
                    //   return  redirect($this->redirectTo);
                }else{
                    // The user is being remembered...
                    return  redirect($this->redirectTo);
                }


            }else{
                $message = [
                    'message' => 'User Name or Password is incorrect',
                    'alert-type' => 'error',
                ];
                return back()->with($message);
                // \Session::put('error', 'User Name or Password is incorrect');
                // return back();
            }

         }else{
            $message = [
                'message' => 'User Name or Password is incorrect',
                'alert-type' => 'error',
            ];
            return back()->with($message);
            // \Session::put('error', 'User Name or Password is incorrect');
            // return back();
          }


    }


}
