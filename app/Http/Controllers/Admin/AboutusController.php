<?php

namespace App\Http\Controllers\Admin;

use App\Models\AboutUs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AboutusController extends Controller
{


    function __construct()
    {
        $this->middleware('role_or_permission:Admin|about-us', ['only' => ['index','store','edit','update','destroy']]);
        $this->middleware('role_or_permission:Admin|career', ['only' => ['create','update']]);
        $this->middleware('role_or_permission:Admin|privacy-policy', ['only' => ['privacy_policy','update']]);
        $this->middleware('role_or_permission:Admin|terms-conditions', ['only' => ['terms_conditions','update']]);
        $this->middleware('role_or_permission:Admin|city_guides', ['only' => ['index','store','edit','update','destroy']]);
        $this->middleware('role_or_permission:Admin|report_fraude', ['only' => ['index','store','edit','update','destroy']]);
        $this->middleware('role_or_permission:Admin|cookies_policy', ['only' => ['index','store','edit','update','destroy']]);
        $this->middleware('role_or_permission:Admin|investor_relation', ['only' => ['index','store','edit','update','destroy']]);


    }





    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $who_are = AboutUs::where('type',1)->first();
        $mission = AboutUs::where('type',2)->first();
        $vision = AboutUs::where('type',3)->first();
        $what_do = AboutUs::where('type',4)->first();
        $we_are = AboutUs::where('type',5)->first();
        $why_us = AboutUs::where('type',6)->first();
        $venue = AboutUs::where('type',7)->first();
        $event = AboutUs::where('type',8)->first();
        $buy_sell = AboutUs::where('type',9)->first();
        $directory = AboutUs::where('type',10)->first();
        $concierge = AboutUs::where('type',11)->first();
        $influencers = AboutUs::where('type',12)->first();
        $jobs = AboutUs::where('type',13)->first();
        $spaces = AboutUs::where('type',14)->first();
        $meet_up = AboutUs::where('type',15)->first();
        $tickets = AboutUs::where('type',16)->first();
        $attachment = AboutUs::where('type',20)->first();
        return view('admin.about_us.index',compact('who_are','mission','vision','what_do','we_are','why_us','venue','event','buy_sell',
            'directory','concierge','influencers','jobs','spaces','meet_up','tickets','attachment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $career = AboutUs::where('type',17)->first();
        return view('admin.about_us.create',compact('career'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $obj = AboutUs::where('type',$id)->first();

        if($obj == null)
        {
            if($id == 20) {
                $attachment = '';
                if($request->attachment){
                    $attachment = rand(100,100000).'.'.time().'.'.$request->attachment->extension();


                    $imagePath = config('app.upload_other_path') . $attachment;
                    Storage::disk('s3')->put($imagePath, file_get_contents($request->attachment));

                }
                $abc = new AboutUs();
                $abc->type = $id;
                $abc->description = $attachment;
                $abc->save();
            }
            elseif($id==21){
                $abc = new AboutUs();
                $abc->type = $id;
                $abc->description = $request->description;
                $abc->save();
            }

            elseif($id==22){
                $abc = new AboutUs();
                $abc->type = $id;
                $abc->description = $request->description;
                $abc->save();
            }
            elseif($id==23){
                $abc = new AboutUs();
                $abc->type = $id;
                $abc->description = $request->description;
                $abc->save();
            }
            elseif($id==24){
                $abc = new AboutUs();
                $abc->type = $id;
                $abc->description = $request->description;
                $abc->save();
            }
            else {
                $abc = new AboutUs();
                $abc->type = $id;
                $abc->description = $request->description;
                $abc->save();
            }
        }

        else{
            if($id == 20) {
                $attachment = '';
                if($request->attachment){
                    $attachment = rand(100,100000).'.'.time().'.'.$request->attachment->extension();


                    $imagePath = config('app.upload_other_path') . $attachment;
                    Storage::disk('s3')->put($imagePath, file_get_contents($request->attachment));

                }
                $obj->description = $attachment;
                $obj->save();
            }
            elseif($id==21){
                $obj->description = $request->description;
                $obj->save();
            }
            elseif($id==22){
                $obj->description = $request->description;
                $obj->save();
            }
            elseif($id==23){
                $obj->description = $request->description;
                $obj->save();
            }
            elseif($id==24){
                $obj->description = $request->description;
                $obj->save();
            }
            else {
                $obj->description = $request->description;
                $obj->save();
            }
        }

        $message = [
            'message' => 'Added Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function privacy_policy()
    {
        $privacy = AboutUs::where('type',18)->first();
        return view('admin.about_us.privacy_policy',compact('privacy'));
    }

    public function terms_conditions()
    {
        $terms = AboutUs::where('type',19)->first();
        return view('admin.about_us.terms_conditions',compact('terms'));
    }

    public function city_guide()
    {
        //21 city guide
        $city_guide = AboutUs::where('type',21)->first();
        return view('admin.about_us.city_guide',compact('city_guide'));
    }

    public function report_fraude()
    {
        //22 report_fraude
        $report_fraude = AboutUs::where('type',22)->first();
        return view('admin.about_us.report_fraude',compact('report_fraude'));
    }

    public function cookies_policy()
    {
        //23 cookies_policy
        $cookies_policy = AboutUs::where('type',23)->first();
        return view('admin.about_us.cookies_policy',compact('cookies_policy'));
    }
    public function investor_relation()
    {
        //24 investor_relation
        $investor_relation = AboutUs::where('type',24)->first();
        return view('admin.about_us.investor_relation',compact('investor_relation'));
    }


}
