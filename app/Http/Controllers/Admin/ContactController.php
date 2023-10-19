<?php

namespace App\Http\Controllers\Admin;

use App\Models\OtherBanner;
use App\Models\SliderBanner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = SliderBanner::all();
        return view('admin.contact.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $datas = SliderBanner::find($id);
        $bannerImages = OtherBanner::where('slider_id', $id)->get();
        return view('admin.contact.edit', compact('datas', 'bannerImages'));
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
            // 'img' => 'required',
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

        $data = [];
        if ($request->hasfile('img')) {
            foreach($request->img as $file) {
                $name = rand(100,100000).'.'.time().'.'.$file->extension();
                $menuPath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($menuPath, file_get_contents($file));
                $data[] = $name;
            }
        }


        if($request->old_images) {
            foreach($request->old_images as $image) {
                $data[] = $image;
            }
        }

        $obj = SliderBanner::find($id);
        $obj->img = json_encode($data);
        $obj->meta_title = $request->meta_title;
        $obj->meta_description = $request->meta_description;
        $obj->meta_tags = $request->meta_tags;
        $obj->save();

        foreach($request->banner as $key => $value) {

            if(!empty($value['banner_image'])) {
                $image = '';
                $image = rand(100,100000).'.'.time().'.'.$value['banner_image']->extension();
                $menuPath = config('app.upload_other_path') . $image;
                Storage::disk('s3')->put($menuPath, file_get_contents($value['banner_image']));
            }

            if(isset($value['id'])){
                $slider = OtherBanner::find($value['id']);
                if(isset($value['banner_image'])){
                    $slider->image = $image;
                }
                if(isset($value['url'])){
                    $slider->url = $value['url'];
                }
                if(isset($value['heading'])){
                    $slider->heading = $value['heading'];
                }
                if(isset($value['description'])){
                    $slider->description = $value['description'];
                }
                $slider->save();
            }else{
                $slider = new OtherBanner();
                $slider->slider_id = $id;
                if(isset($value['banner_image'])){
                    $slider->image = $image;
                }

                $slider->url = $value['url'];
                $slider->heading = $value['heading'];
                $slider->description = $value['description'];
                $slider->save();
            }

        }

        $message = [
            'message' => 'Image Updated Successfully',
            'alert-type' => 'success',
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
