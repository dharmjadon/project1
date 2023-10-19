<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CityController extends Controller
{


    function __construct()
    {
        $this->middleware('role_or_permission:Admin|city-add', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Admin|city-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|city-edit', ['only' => ['edit','update']]);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = City::with('states')->get();
        return view('admin.city.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::all();
        return view('admin.city.create',  compact('states'));
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
            'name' => 'required',
            'state_id' => 'required',
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


        if(isset($request->assign_popular)){

            $validator = Validator::make($request->all(), [
                'popular_image' => 'required',
                'location' => 'required',
                'citylat' => 'required',
                'citylong' => 'required',
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

        }

        if(!empty($_FILES['popular_image']['name'])) {

            $feature_image_extension = pathinfo($_FILES['popular_image']['name'], PATHINFO_EXTENSION);
            $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
            $menuPath = config('app.upload_other_path') . $feature_image_name;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->fpopular_image));
            $feature_image_path = $feature_image_name;
        }

        $obj = new City();
        $obj->state_id = $request->state_id;
        $obj->name = $request->name;
        if(isset($request->assign_popular)){
            $obj->is_popular = $request->assign_popular;
            $obj->popular_image   = $feature_image_path;
            $obj->location =  $request->location;
            $obj->lat =  $request->citylat;
            $obj->lng =  $request->citylong;

        }
        $obj->save();
        $message = [
            'message' => 'City Added Successfully',
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
        $states = State::all();
        $city = City::find($id);
        return view('admin.city.edit', compact('city', 'states'));
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



        if(!empty($_FILES['popular_image']['name'])) {

            $feature_image_extension = pathinfo($_FILES['popular_image']['name'], PATHINFO_EXTENSION);
            $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
            $menuPath = config('app.upload_other_path') . $feature_image_name;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->featured_img));
            $feature_image_path = $feature_image_name;
        }

        if(isset($request->assign_popular)){

            $validator = Validator::make($request->all(), [
                // 'popular_image' => 'required',
                'location' => 'required',
                'citylat' => 'required',
                'citylong' => 'required',
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

        }



        $obj = City::find($id);
        $obj->state_id = $request->state_id;
        $obj->name = $request->name;

        if(isset($feature_image_path)){
            $obj->popular_image  = $feature_image_path;
        }
        if(isset($request->assign_popular)){
            $obj->is_popular = $request->assign_popular;
            $obj->location =  $request->location;
            $obj->lat =  $request->citylat;
            $obj->lng =  $request->citylong;

        }

        $obj->update();
        $message = [
            'message' => 'City Updated Successfully',
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
    public function destroy(Request $request)
    {
        $obj = City::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'City Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

/**
     * Ajax request to get cities of specified Emirates.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCities(Request $request)
    {
        $data = City::where('state_id', $request->id)->get();
        return response()->json($data, 200);
    }
}
