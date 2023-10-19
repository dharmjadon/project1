<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\OtherImage;
use Illuminate\Http\Request;
use App\Models\SocialGoogleAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use App\Services\SocialGoogleAccountService;
use App\Services\SocialFacebookAccountService;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function signup(Request $request)
    {
        $user_type = $request->user_type;
        $data = OtherImage::where('type', 1)->get();
        // return view('user.auth.signup', compact('data'));

        return view('user.auth.register', get_defined_vars());
    }

    public function postSignup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $validate = $validator->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'danger',
                'error' => $validate->first(),
            ];
            return back()->with($message)->withInput();
        }

        $otp = rand(pow(10, 6), pow(10, 5)-1);

        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->user_type = 3;
        $user->status = 0;
        $user->password = bcrypt($request->password);
        $user->otp = $otp;
        $user->save();


        \Mail::send('emails.otp', ['otp' => $otp], function($message) use($request){
            $message->to($request->email);
            $message->subject('OTP');
        });

        $id = Crypt::encrypt($user->id);
        return redirect('/complete-signup?id=' . $id);

    }

    public function completeSignup()
    {
        return view('user.auth.complete-signup');
    }

    public function postOtp(Request $request)
    {
        $id = Crypt::decrypt($request->id);

        $user = User::find($id);
        if($request->otp == $user->otp){
            $user->status = 1;
            $user->save();

            Auth::loginUsingId($user->id);
            return redirect('/client/enquiry');
        } else{
            $message = [
                'message' => 'Wrong OTP ENtered',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($message);
        }


    }

    /**
     * Create a redirect method to facebook api.
     *
     * @return void
     */
    public function facebookRedirect(Request $request)
    {
        return Socialite::driver('facebook')->stateless()->with([
            'state' => "user_type=".$request->get('utype', 'profile')
        ])->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
    public function facebookCallback(Request $request, SocialFacebookAccountService $service)
    {
        $user = $service->createOrGetUser($request);
        auth()->login($user);
        if($user->user_type === 4) {
            return redirect()->to('/publisher/dashboard');
        } else {
            return redirect()->to('/client/applied-job');
        }
    }

    public function redirectToGoogle(Request $request)
    {
        return Socialite::driver('google')->with([
            'state' => "user_type=".$request->get('utype', 'profile')
        ])->redirect();
    }

    public function handleGoogleCallback(Request $request, SocialGoogleAccountService $service)
    {
        $user = $service->createOrGetUser($request);
        Auth::loginUsingId($user->id);
        if($user->user_type === 4) {
            return redirect()->to('/publisher/dashboard');
        } else {
            return redirect()->to('/client/applied-job');
        }
    }

}
