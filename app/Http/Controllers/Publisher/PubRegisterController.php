<?php

namespace App\Http\Controllers\Publisher;

use App\Models\OtherImage;
use App\Models\User;
use Carbon\Carbon;
use App\Models\MiscData;
use App\Models\PublisherFaq;
use Illuminate\Http\Request;
use App\Constants\MiscDataConst;
use App\Models\PublisherLoginBanner;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class PubRegisterController extends Controller
{
    //

    public function register()
    {
        $obj = MiscData::find(MiscDataConst::PUBLISHER_VIDEO_GUIDANCE);
        return view('publisher.register', compact('obj'));
    }

    public function complete_registeration()
    {
        $faqs = PublisherFaq::where('status', 1)->get();
        return view('publisher.registered_new', compact('faqs'));
    }

    public function complete_reg_save(Request $request)
    {
        // dd($request);

        $validator = Validator::make($request->all(), [
            'company_name' => 'required|unique:users,company_name',
            'company_address' => 'required',
            // 'mobile_number' => 'required',
            // 'select_listing' => 'required',
            // 'mobile' => 'required',
            'reg_id' => 'required'
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


        $user_id = Crypt::decrypt($request->reg_id);

        $user_detail = User::find($user_id);
        if ($user_detail == null) {
            $message = [
                'message' => "something went contact admin",
                'alert-type' => 'error',
            ];
            return back()->with($message);

        }
        $user_detail->company_name = $request->company_name;
        $user_detail->address = $request->company_address;
        // $user_detail->publisher_category_type  = $request->select_listing;
        // $user_detail->mobile_no  = $request->mobile;
        $user_detail->status = "1";
        $user_detail->update();


        $message = [
            'message' => "You are regisered successfully",
            'alert-type' => 'success',
        ];
        return redirect('/publisher/complete-registration?complete=yes')->with($message);


    }

    public function verify_otp(Request $request)
    {

        $user_id = Crypt::decrypt($request->reg_id);
        $otp = $request->otp;
        $user_detail = User::where('id', '=', $user_id)->where('otp', '=', $otp)->first();

        if ($user_detail != null) {
            $user_detail->otp_status = "1";
            // $user_detail->otp = null;
            $user_detail->update();

            return "1";
            exit;
        } else {

            return "2";
            exit;
        }

    }

    public function resend_otp(Request $request)
    {

        $user_id = Crypt::decrypt($request->reg_id);
        $otp = $request->otp;
        $user_detail = User::where('id', '=', $user_id)->first();

        if ($user_detail != null) {

            $otp_digits = $this->get_otp_number(6);

            $msg_ab = "Your OTP is " . $otp_digits . " for registeration.  \nPlease don't share your OTP.";

            $str_number = ltrim($user_detail->mobile_no, '0');

            $mobile_no = "+971" . $str_number;

            $this->sendSms($mobile_no, $msg_ab);

            $email = $user_detail->email;

            \Mail::send('emails.otp', ['otp' => $otp_digits], function ($message) use ($email) {
                $message->to($email);
                $message->subject('OTP');
            });


            $user_detail->otp = $otp_digits;
            $user_detail->update();


            return "1";
            exit;
        } else {

            return "2";
            exit;
        }

    }


    public function login(Request $request)
    {
        $user_type = $request->user_type;
        $data = OtherImage::where('type', 1)->get();
        // return view('user.auth.signup', compact('data'));

        return view('user.auth.register', get_defined_vars());
        /*$images = PublisherLoginBanner::all();
        return view('publisher.login', compact('images'));*/
    }

    public function register_save(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email',
            'name' => 'required',
            'mobile_number' => 'required|unique:users,mobile_no',
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            $validate = $validator->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];
            // dd($message);
            return back()->with($message);
        }

        $otp_digits = $this->get_otp_number(6);

        $msg_ab = "Your OTP is " . $otp_digits . " for registeration.  \nPlease don't share your OTP.";

        $str_number = ltrim($request->mobile_number, '0');

        $mobile_no = "+971" . $str_number;

        $this->sendSms($mobile_no, $msg_ab);

        \Mail::send('emails.otp', ['otp' => $otp_digits], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('OTP');
        });


        $obj = new User();
        $obj->name = $request->input('name') ? $request->input('name') : 'N/A';
        $obj->email = $request->input('email');
        $obj->password = bcrypt($request->input('password'));
        $obj->user_type = "4";
        $obj->status = "0";
        $obj->email_verified_at = Carbon::now();
        $obj->mobile_no = $request->mobile_number;
        $obj->otp = $otp_digits;
        $obj->save();

        // $obj->assignRole($request->input('roles'));

        $message = [
            'message' => "User has been created successfully",
            'alert-type' => 'success',
        ];

        $last_id = Crypt::encrypt($obj->id);

        return redirect('publisher/complete-registration?reg=' . $last_id)->with($message);
        // return back()->with($message);


    }

    public function get_otp_number($digits)
    {

        $four_digit_random_number = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

        return $four_digit_random_number;
    }


    public function sendSms($mobile, $msg)
    {

        $url = 'https://smartsmsgateway.com/api/api_json.php?username=partyfinder&password=RTnvLv9kUT&senderid=PartyFinder&to=' . urlencode($mobile) . '&text=' . urlencode($msg) . '.&type=text';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $err = curl_error($ch);  //if you need
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
