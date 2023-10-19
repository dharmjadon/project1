<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeTrendBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class HomeTrendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = HomeTrendBanner::all();
        return view('admin.home-trend-banner.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.home-trend-banner.create');
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
            'heading' => 'required',
            'image' => 'required',
            'url' => 'required'
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

        $name = rand(100,100000).'-'.time().'.'.$request->image->extension();
        $menuPath = config('app.upload_other_path') . $name;
        Storage::disk('s3')->put($menuPath, file_get_contents($request->image));

        $obj = new HomeTrendBanner();
        $obj->heading= $request->heading;
        $obj->image = $name;
        $obj->url = $request->url;
        $obj->save();

        $message = [
            'message' => 'Banner Added Successfully',
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
        $data = HomeTrendBanner::where('id', $id)->first();
        return view('admin.home-trend-banner.edit', compact('data'));
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
            'heading' => 'required',
            'url' => 'required'
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

        if($request->image){
            $name = rand(100,100000).'-'.time().'.'.$request->image->extension();
            $menuPath = config('app.upload_other_path') . $name;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->image));
        }


        $obj = HomeTrendBanner::find($id);
        $obj->heading = $request->heading;
        if($request->image){
            $obj->image = $name;
        }
        $obj->url = $request->url;
        $obj->save();

        $message = [
            'message' => 'Banner Updated Successfully',
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
