<?php

namespace App\Http\Controllers\Admin;

use App\Models\OtherImage;
use Illuminate\Http\Request;
use App\Constants\OtherImages;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class SignupImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = OtherImage::where('type', 1)->get();
        return view('admin.signup-image-banner.index', compact('datas'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.signup-image-banner.create');
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
            'image' => 'required',
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

        $img= '';
        if($request->image) {
            $img = rand(100,100000).'.'.time().'.'.$request->image->extension();
            $menuPath = config('app.upload_other_path') . $img;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->img));
        }

        $obj = new OtherImage();
        $obj->image = $img;
        $obj->type = 1;
        $obj->save();

        $message = [
            'message' => 'Image Added Successfully',
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
        $data = OtherImage::find($id);
        return view('admin.signup-image-banner.edit', compact('data'));
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
            'image' => 'required',
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

        $img= '';
        if($request->image) {
            $img = rand(100,100000).'.'.time().'.'.$request->image->extension();
            $menuPath = config('app.upload_other_path') . $img;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->img));
        }

        $obj = OtherImage::find($id);
        $obj->image = $img;
        $obj->save();

        $message = [
            'message' => 'Image Updated Successfully',
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
        $obj = OtherImage::find($id);
        $obj->delete();

        $message = [
            'message' => 'Image Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
