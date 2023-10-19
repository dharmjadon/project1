<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SocialGoogleAccount;
use Laravel\Socialite\Facades\Socialite;

class SocialGoogleAccountService
{
    public function createOrGetUser(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $state = $request->input('state');
        parse_str($state, $custom_data);

        $existUser = User::where('email', $googleUser->email)->first();

        if ($existUser) {
            $user = $existUser;
        } else {
            $user = new User;
            $user->name = $googleUser->name;
            $user->email = $googleUser->email;
            $user->password = bcrypt(rand(1, 10000));
            $user->user_type = isset($custom_data['user_type']) && $custom_data['user_type'] === 'business' ? 4 : 3;
            $user->save();
            $user->markEmailAsVerified();

            $obj = new SocialGoogleAccount();
            $obj->user_id = $user->id;
            $obj->google_id = $googleUser->id;
            $obj->save();
        }

        return $user;
    }
}
