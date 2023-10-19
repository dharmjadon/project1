<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Specification;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MotorSpecificationController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Admin|motor-specification-add', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Admin|motor-specification-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|motor-specification-edit', ['only' => ['edit','update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Specification::all();
        return view('admin.motors.specification.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.motors.specification.create');
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



           $fileName = rand(100, 100000) . '.' . time() . '.' . $request->icon->extension();
            $fileName_path = config('app.upload_other_path') . $fileName;
            Storage::disk('s3')->put($fileName_path, file_get_contents($request->icon));


        $obj = new Specification();
        $obj->name = $request->name;
        $obj->icon = $fileName;
        $obj->save();

        $message = [
            'message' => 'Specification Added Successfully',
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
        $data = Specification::find($id);
        $images = json_decode($data->images);

        return view('admin.motors.specification.edit',compact('data', 'images'));
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

        if($request->icon) {
            // $fileName = time().'.'.$request->icon->extension();
            // $request->icon->move(public_path('uploads'), $fileName);

            $fileName = rand(100, 100000) . '.' . time() . '.' . $request->icon->extension();
            $fileName_path = config('app.upload_other_path') . $fileName;
            Storage::disk('s3')->put($fileName_path, file_get_contents($request->icon));
        }


        $obj = Specification::find($id);
        $obj->name = $request->name;
        if($request->icon) {
            $obj->icon = $fileName;
        }
        $obj->save();

        $message = [
            'message' => 'Specification Updated Successfully',
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
        $obj = Specification::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Specification Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
