<?php

namespace App\Services;

use App\Models\SocialFacebookAccount;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Laravel\Socialite\Facades\Socialite;

class SocialFacebookAccountService
{
    public function createOrGetUser(Request $request)
    {
        $providerUser = Socialite::driver('facebook')->stateless()->user();
        $state = $request->input('state');
        parse_str($state, $custom_data);

        $account = SocialFacebookAccount::whereProvider('facebook')
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {

            $account = new SocialFacebookAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => 'facebook'
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {

                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                    'user_type' => isset($custom_data['user_type']) && $custom_data['user_type'] === 'business' ? 4 : 3,
                    'password' => bcrypt(rand(1,10000)),
                ]);
                $user->markEmailAsVerified();
            }

            $account->user()->associate($user);
            $account->save();

            return $user;
        }
    }
}
