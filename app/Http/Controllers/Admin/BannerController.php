<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $home_banners = Banner::where('banner_type',1)->get();
        $promotion_banners = Banner::where('banner_type',2)->get();
        $footer_banners = Banner::where('banner_type',3)->get();
        return view('admin.banner.index',compact('home_banners','promotion_banners','footer_banners'));
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

        if($id == 1) {
            foreach($request->banner as $key => $value) {

                if(!empty($value['banner_image'])) {
                    $image = '';
                    $image = rand(100,100000).'.'.time().'.'.$value['banner_image']->extension();
                    $imagePath = config('app.upload_other_path') . $image;
                    Storage::disk('s3')->put($imagePath, file_get_contents($value['banner_image']));
                }

                if(isset($value['id'])){
                    $banner = Banner::find($value['id']);
                    if(isset($value['banner_image'])){
                        $banner->image = $image;
                    }
                    $banner->url = $value['url'];
                    $banner->title = $value['title'];
                    $banner->description = $value['description'];
                    $banner->save();
                }else{
                    $banner = new Banner();
                    $banner->banner_type = $id;
                    if(isset($value['banner_image'])){
                        $banner->image = $image;
                    }
                    $banner->url = $value['url'];
                    $banner->title = $value['title'];
                    $banner->description = $value['description'];
                    $banner->save();
                }
            }

            $message = [
                'message' => 'Banner Added Successfully',
                'alert-type' => 'success'
            ];
            return redirect()->back()->with($message);
        }elseif($id == 2) {
            foreach($request->promotion as $key => $value) {

                if(!empty($value['banner_image'])) {
                    $image = '';
                    $image = rand(100,100000).'.'.time().'.'.$value['banner_image']->extension();
                    $imagePath = config('app.upload_other_path') . $image;
                    Storage::disk('s3')->put($imagePath, file_get_contents($value['banner_image']));
                }

                if(isset($value['id'])){
                    $banner = Banner::find($value['id']);
                    if(isset($value['banner_image'])){
                        $banner->image = $image;
                    }
                    $banner->url = $value['url'];
                    $banner->title = $value['title'];
                    $banner->description = $value['description'];
                    $banner->save();
                }else{
                    $banner = new Banner();
                    $banner->banner_type = $id;
                    if(isset($value['banner_image'])){
                        $banner->image = $image;
                    }
                    $banner->url = $value['url'];
                    $banner->title = $value['title'];
                    $banner->description = $value['description'];
                    $banner->save();
                }
            }

            $message = [
                'message' => 'Banner Added Successfully',
                'alert-type' => 'success'
            ];
            return redirect()->back()->with($message);
        }elseif($id == 3) {
            foreach($request->footer as $key => $value) {

                if(!empty($value['banner_image'])) {
                    $image = '';
                    $image = rand(100,100000).'.'.time().'.'.$value['banner_image']->extension();
                    $imagePath = config('app.upload_other_path') . $image;
                    Storage::disk('s3')->put($imagePath, file_get_contents($value['banner_image']));
                }

                if(isset($value['id'])){
                    $banner = Banner::find($value['id']);
                    if(isset($value['banner_image'])){
                        $banner->image = $image;
                    }
                    $banner->url = $value['url'];
                    $banner->title = $value['title'];
                    $banner->description = $value['description'];
                    $banner->save();
                }else{
                    $banner = new Banner();
                    $banner->banner_type = $id;
                    if(isset($value['banner_image'])){
                        $banner->image = $image;
                    }
                    $banner->url = $value['url'];
                    $banner->title = $value['title'];
                    $banner->description = $value['description'];
                    $banner->save();
                }
            }

            $message = [
                'message' => 'Banner Added Successfully',
                'alert-type' => 'success'
            ];
            return redirect()->back()->with($message);
        }
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

    public function delete_banner(Request $request)
    {
        $banner = Banner::find($request->id);
        $banner->delete();

        $message = [
            'message' => 'Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
