<?php

namespace App\Http\Controllers\Admin;

use App\InboundSeo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class InbounSeoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = InboundSeo::all();
        return view('admin.inbound-seo.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.inbound-seo.create');
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
            'title' => 'required|unique:blogs,title',
            'description' => 'required',
            'image' => 'required'
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


        if($request->hasfile('image'))
        {
           foreach($request->file('image') as $file)
           {

                $name =rand(100,100000).'-'.time().'.'.$file->extension();
                $menuPath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($menuPath, file_get_contents($file));
                $images[] = $name;
           }
        }

          //feature image upload
        //   if(!empty($_FILES['image']['name'])) {

        //     $feature_image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        //     $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
        //     move_uploaded_file($_FILES["image"]["tmp_name"], '../public/uploads/blog/feature_image/' . $feature_image_name);
        //     $feature_image_path = 'uploads/blog/feature_image/' . $feature_image_name;

        $obj = new InboundSeo();
        $obj->title = $request->title;
        $obj->content = $request->description;
        $obj->publisher = $request->publisher;

        if(isset($images)){
            $obj->feature_image = json_encode($images);
        }
        $obj->publish_date = $request->publish_date;
        $obj->slug = Str::slug($request->title, '-');

        $obj->save();
        $message = [
            'message' => 'Blog Added Successfully',
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
        $data = InboundSeo::where('slug', $id)->first();
        return view('admin.inbound-seo.edit', compact('data'));
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
            'title' => 'required|unique:blogs,title,'.$id,
            'description' => 'required',
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

         //feature image upload
        //  if(!empty($_FILES['image']['name'])) {

        //     $feature_image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        //     $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
        //     move_uploaded_file($_FILES["image"]["tmp_name"], '../public/uploads/blog/feature_image/' . $feature_image_name);
        //     $feature_image_path = 'uploads/blog/feature_image/' . $feature_image_name;
        // }
        if($request->hasfile('image'))
        {
           foreach($request->file('image') as $file)
           {

               $name =rand(100,100000).'-'.time().'.'.$file->extension();
               $menuPath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($menuPath, file_get_contents($file));
               $images[] = $name;
           }
        }else{
        if($request->old_images) {
            foreach($request->old_images as $image) {
                $images_edit[] = $image;
            }
        }
    }
// dd($images);
        $obj = InboundSeo::find($id);
        $obj->title = $request->title;
        $obj->content = $request->description;
        $obj->publisher = $request->publisher;
        // if(isset($feature_image_path)){
        //     $obj->feature_image = $feature_image_path;
        // }

        if(isset($images)){
            $obj->feature_image = json_encode($images);
        }
        else{
            $obj->feature_image = $images_edit;
        }

        $obj->publish_date = $request->publish_date;
        $obj->slug = Str::slug($request->title, '-');

        $obj->save();
        $message = [
            'message' => 'Blog Updated Successfully',
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
        $obj = InboundSeo::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Blog deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
