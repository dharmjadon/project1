<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopularPlacesTypes;
use App\Models\ServiceTypeTopTrend;
use App\Models\TopTrends;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TopTrendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $toptrends = TopTrends::all();

        return view('admin.toptrends.index',compact('toptrends'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         $service_types = ServiceTypeTopTrend::all();
         $popular_types = PopularPlacesTypes::all();

        return view('admin.toptrends.create',compact('service_types','popular_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:top_trends,name',
            'image' => 'required',
            'service_type' => 'required',
            'rating' => 'required',
            'origin_type' => 'required',
            'popular_type' => 'required',
            'location' => 'required',
            'citylat' => 'required',
            'citylong' => 'required',
            'link' => 'required',
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

        if(!empty($_FILES['image']['name'])) {
            $feature_image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
            $menuPath = config('app.upload_other_path') . $feature_image_name;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->image));
            $feature_image_path = $feature_image_name;
        }



        $obj = new TopTrends();
        $obj->name = $request->name;
        $obj->image = $feature_image_path;
        $obj->service_type =  json_encode($request->service_type);
        $obj->popular_type =  json_encode($request->popular_type);
        $obj->origin_type =  $request->origin_type;
        $obj->lat =  $request->citylat;
        $obj->long =  $request->citylong;
        $obj->link =  $request->link;
        $obj->adress =  $request->location;
        $obj->rating =  $request->rating;
        $obj->save();
        $message = [
            'message' => 'Top tred Added Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);


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

        $service_types = ServiceTypeTopTrend::all();
        $popular_types = PopularPlacesTypes::all();

         $toptrend = TopTrends::find($id);

         $selected_service_type =  json_decode($toptrend->service_type, true);
         $selected_popular_type =  json_decode($toptrend->popular_type, true);

        return view('admin.toptrends.edit',compact('service_types','popular_types','toptrend','selected_service_type','selected_popular_type'));
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
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:top_trends,name,'.$id,
            'service_type' => 'required',
            'rating' => 'required',
            'origin_type' => 'required',
            'popular_type' => 'required',
            'location' => 'required',
            'citylat' => 'required',
            'citylong' => 'required',
            'link' => 'required',
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


        if(!empty($_FILES['image']['name'])) {
            $feature_image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
            $menuPath = config('app.upload_other_path') . $feature_image_name ;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->image));
            $feature_image_path = $feature_image_name;
        }



        $obj = new TopTrends();
        $obj->name = $request->name;
        if(isset($feature_image_path)){
            $obj->image = $feature_image_path;
        }
        $obj->service_type =  json_encode($request->service_type);
        $obj->popular_type =  json_encode($request->popular_type);
        $obj->origin_type =  $request->origin_type;
        $obj->lat =  $request->citylat;
        $obj->long =  $request->citylong;
        $obj->link =  $request->link;
        $obj->adress =  $request->location;
        $obj->rating =  $request->rating;
        $obj->update();
        $message = [
            'message' => 'Top tred Updated Successfully',
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

    public function delete(Request $request){

         $top_trends = TopTrends::find($request->id);
         $top_trends->delete();

         $message = [
            'message' => 'Top tred deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);


    }



}
