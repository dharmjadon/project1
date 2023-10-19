<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PopularPlaceFaq;
use App\Models\PopularPlaceVenue;
use App\Http\Controllers\Controller;
use App\Models\PopularPlaceSuggestion;
use Illuminate\Support\Facades\Storage;

class PopularPlaceVenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = PopularPlaceVenue::all();
        return view('admin.popular-place-venue.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::all();
        return view('admin.popular-place-venue.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $profile_img = '';
        if($request->profile_img) {
            $profile_img = rand(100,100000).'.'.time().'.'.$request->profile_img->extension();
            $menuPath = config('app.upload_other_path') . $profile_img;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->profile_img));
        }

        $feature_img = '';
        if($request->featured_img) {
            $feature_img = rand(100,100000).'.'.time().'.'.$request->featured_img->extension();
            $menuPath = config('app.upload_other_path') . $feature_img;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->feature_img));
        }

        $metro_img = '';
        if($request->metro_img) {
            $metro_img = rand(100,100000).'.'.time().'.'.$request->metro_img->extension();
            $menuPath = config('app.upload_other_path') . $metro_img;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->metro_img));
        }

        $bus_access = '';
        if($request->bus_access) {
            $bus_access = rand(100,100000).'.'.time().'.'.$request->bus_access->extension();
            $menuPath = config('app.upload_other_path') . $bus_access;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->bus_access));
        }

        $taxi_access = '';
        if($request->taxi_access) {
            $taxi_access = rand(100,100000).'.'.time().'.'.$request->taxi_access->extension();
            $menuPath = config('app.upload_other_path') . $taxi_access;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->taxi_access));
        }

        $data = [];
        if($request->images) {
            foreach($request->images as $image) {
                $imageData = rand(100,100000).'.'.time().'.'.$image->extension();
                $menuPath = config('app.upload_other_path') . $imageData;
                Storage::disk('s3')->put($menuPath, file_get_contents($image));
                $data[] = $imageData;
            }
        }

        $dataVisit = [];
        foreach($request->why_visit as $visit) {
            $dataVisit[] = $visit;
        }

        $obj = new PopularPlaceVenue();
        $obj->title = $request->title;
        $obj->description = $request->description;
        $obj->contact_no = $request->contact;
        $obj->city_id = $request->city_id;
        $obj->featured_image = $feature_img;
        $obj->metro_access = $metro_img;
        $obj->bus_access = $bus_access;
        $obj->taxi_access = $taxi_access;
        $obj->display_image = $profile_img;
        $obj->images = json_encode($data);
        $obj->lat = $request->citylat;
        $obj->long = $request->citylong;
        $obj->location = $request->location;
        $obj->why_visit = json_encode($dataVisit);
        $obj->save();

        if($request->faq) {
            foreach($request->faq as $faq) {
                $data = new PopularPlaceFaq();
                $data->pp_id = $obj->id;
                $data->question = $faq['question'];
                $data->answer = $faq['answer'];
                $data->save();
            }
        }

        $message = [
            'message' => 'Place Added Successfully',
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

    public function popularPlaceSuggestion() {

        $suggestions = PopularPlaceSuggestion::with('popularPlace')->get();
        return view('admin.popular-place-venue.suggestion', compact('suggestions'));
    }
}
