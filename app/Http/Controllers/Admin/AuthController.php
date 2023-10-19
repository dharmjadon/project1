<?php

namespace App\Http\Controllers\Admin;

use Mail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function forgotPassword()
    {
        return view('auth.passwords.forgot');
    }

    public function postForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);

        $token = Str::random(64);

        $email = User::where('email', $request->email)->first();
        if(!$email){
            $message = [
                'message' => 'Invalid Email',
                'alert-type' => 'danger'
            ];
            return back()->with($message);
        }
        $user = User::where('email', $request->email)->update([
            'reset_token' => $token
         ]);

        Mail::send('emails.forgot-password', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        $message = [
            'message' => 'We have e-mailed your password reset link!',
            'alert-type' => 'success'
        ];
        return back()->with($message);
        // return view('auth.passwords.forgot');
    }

    public function resetPassword($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function resetPasswordPost(Request $request)
    {
        // return $request->all();
        $password = $request->get('reset-password-new');
        $updatePassword = \DB::table('users')
                            ->where([
                              'reset_token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword){
            $message = [
                'message' => 'Invalid Token',
                'alert-type' => 'danger'
            ];
            return back()->with($message);
        }
        $user = User::where('reset_token', $request->token)
                    ->update([
                        'password' => Hash::make($password),
                        'reset_token' => '',
                    ]);

        $message = [
            'message' => 'Your password has been changed!',
            'alert-type' => 'success'
        ];
        // return back()->with($message);

        // return redirect('/admin')->with($message);
        return redirect('/')->with($message);
    }
}
