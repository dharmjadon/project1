<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\HomeRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller
{
    public function index()
    {

        return view('user.home-register.index');
    }

    public function postRegister(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required',
            'company_name' => 'required',
            'email' => 'unique:home_registers,email',
            'category' => 'required'
        ]);



        $otp = rand(pow(10, 4), pow(10, 5) - 1);

        \Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('OTP');
        });


        $obj = new HomeRegister();
        $obj->name = $request->name;
        $obj->company_name = $request->company_name;
        $obj->email = $request->email;
        $obj->mobile_no = $request->mobile_no;
        $obj->major_category_id = $request->category;
        $obj->otp = $otp;
        $obj->save();

        $id = Crypt::encrypt($obj->id);
        return redirect('/home-verification?id=' . $id);
    }

    public function otpVerify(){
        return view('user.home-register.otp');
    }

    public function postOtp(Request $request){
        $id = Crypt::decrypt($request->id);

        $user = HomeRegister::find($id);
        if($request->otp == $user->otp){
            $user->verified = 1;
            $user->save();


            $array_category = array(
                '',
                'Venue',
                'Events',
                'Buy & Sell',
                'Directory',
                'Concierge',
                'Influencer',
                'Jobs',
                'Tickets',
                'Property',
                'Attraction',
                'Book Artist',
                'Give Away',
                'Motors'
            );

            $register_array = array(
                'name' => $user->name,
                'company_name' => $user->company_name,
                'email' => $user->email,
                'mobile_no' => $user->mobile_no,
                'category' => $array_category[$user->major_category_id],
            );


            \Mail::send('emails.register-bussines', ['details' => $register_array], function ($message) use ($request) {
                $message->to("myfinderapi@gmail.com");
                $message->subject('New Bussiness Registration');
            });



            return redirect('/welcome/home');
        } else{
            $message = [
                'message' => 'Wrong OTP Entered',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($message);
        }
    }

    public function welcome(Request $request){
        return view('user.home-register.welcome');
    }
}
