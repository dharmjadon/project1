<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Amenties;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AmentiesController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|amenity-add', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Admin|amenity-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|amenities-edit', ['only' => ['edit','update']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Amenties::all();
        return view('admin.amenties.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $icons = array(
            'name' => "first icon",
            'name' => "second icon"
        );



        return view('admin.amenties.create',compact('icons'));

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
            'icon' => 'required',
            'description' => 'required'

        ]);
        if($validator->fails()) {
            $validate = $validator->errors();

            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];
            return redirect()->back()->with($message);
        }

        $ext2 = pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION);
        $file_path2 = time() . "_" . $request->date . '.' . $ext2;

        $imagePath = config('app.upload_other_path') . 'amenties_icon/' . $file_path2;
        Storage::disk('s3')->put($imagePath, file_get_contents($request->icon));


         $amentites = new Amenties();
         $amentites->icon_name =  $file_path2;
         $amentites->description = $request->description;
         $amentites->save();

         $message = [
            'message' => "Amenity saved successsully",
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
        $data = Amenties::find($id);

        return view('admin.amenties.edit',compact('data'));
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
        $validator = Validator::make($request->all(), [
            'description' => 'required'

        ]);
        if($validator->fails()) {
            $validate = $validator->errors();

            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];
            return redirect()->back()->with($message);
        }

        if($request->icon_name) {
            $fileName = time().'.'.$request->icon_name->extension();
            $imagePath = config('app.upload_other_path') . 'amenties_icon/' . $fileName;
            Storage::disk('s3')->put($imagePath, file_get_contents($request->icon_name));
        }

        $obj = Amenties::find($id);
        $obj->description = $request->description;
        if($request->icon_name) {
            $obj->icon_name = $fileName;
        }
        $obj->save();

        $message = [
            'message' => 'Amenity Updated Successfully',
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
        $obj = Amenties::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Amenity Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
