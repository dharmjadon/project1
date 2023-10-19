<?php

namespace App\Services;

class SocialFacebookAccountService
{
    public function sendOtp($mobile, $msg)
    {

        $url = 'https://smartsmsgateway.com/api/api_json.php?username=partyfinder&password=RTnvLv9kUT&senderid=PartyFinder&to=' . urlencode($mobile) . '&text=' . urlencode($msg) . '.&type=text';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $err = curl_error($ch); //if you need
        curl_close($ch);

        // dd($response);
        $now_array = json_decode($response, true);

        if ($now_array['data']['status'] == "SUCCESS") {
            return true;
        } else {
            return $now_array;
        }

    }
}
