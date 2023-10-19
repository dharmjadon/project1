<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopularPlacesTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PopularPlacesTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $popular_types = PopularPlacesTypes::all();

        return view('admin.popular_places_types.index',compact('popular_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.popular_places_types.create');
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


        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:popular_places_types,name',
            'image' => 'required',
            'icon' => 'required',
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


        if(!empty($_FILES['icon']['name'])) {

            $feature_image_extension = pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION);
            $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
            $menuPath = config('app.upload_other_path') . $feature_image_name;
            Storage::disk('s3')->put($menuPath, file_get_contents($menuPath));
            $feature_image_path_icon = $feature_image_name;
        }



        $obj = new PopularPlacesTypes();
        $obj->name = $request->name;
        $obj->icon = $feature_image_path_icon;
        $obj->background_image = $feature_image_path;
        $obj->save();
        $message = [
            'message' => 'Popular Place Type Added Successfully',
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
        $popular_type = PopularPlacesTypes::find($id);

        return view('admin.popular_places_types.edit',compact('popular_type'));
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
            'name' => 'required|unique:popular_places_types,name,'.$id,

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


        if(!empty($_FILES['icon']['name'])) {

            $feature_image_extension = pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION);
            $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
            $menuPath = config('app.upload_other_path') . $feature_image_name;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->icon));
            $feature_image_path_icon = $feature_image_name;
        }



        $obj = PopularPlacesTypes::find($id);
        $obj->name = $request->name;
        if(isset($feature_image_path_icon)){

            $obj->icon = $feature_image_path_icon;
        }

        if(isset($feature_image_path)){
            $obj->background_image = $feature_image_path;
        }

        $obj->save();
        $message = [
            'message' => 'Popular Place Type Updated Successfully',
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
}
