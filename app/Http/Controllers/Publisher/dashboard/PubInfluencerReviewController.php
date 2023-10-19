<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Http\Controllers\Controller;
use App\Models\InfluencerReview;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PubInfluencerReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $user_id = Auth::user()->id;

        $datas = InfluencerReview::where('created_by','=',$user_id)->orderby('id', 'desc')->get();
        return view('publisher.infleuncer-review.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $main_category = MainCategory::where('major_category_id',6)->get();

        return view('publisher.infleuncer-review.create', compact('main_category'));
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
            'name' => 'required',
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




          //feature image upload

          if($request->hasfile('image'))
          {
             foreach($request->file('image') as $file)
             {

                $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $nameimagePath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($nameimagePath, file_get_contents($file));

                $images[] = $name;

             }
          }


        $obj = new InfluencerReview();
        $obj->name = $request->name;
        $obj->content = $request->description;
        $obj->youtube = $request->youtube;
        $obj->company_name = $request->company_name;
        $obj->main_category_id = $request->main_category_id;

        if(isset($images)){
            $obj->feature_image = json_encode($images);
        }
        $obj->publish_date = $request->publish_date?? Date('y:m:d');
        $obj->save();
        $obj->slug = Str::slug($request->name . ' ' . $request->company_name. ' ' . $obj->id, '-');
        $obj->created_by = Auth::user()->id;
        $obj->is_publisher = "1";
        $obj->save();
        $message = [
            'message' => 'Influencer Review Added Successfully',
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
        $data = InfluencerReview::where('slug', $id)->first();
        $main_category = MainCategory::where('major_category_id',6)->get();

        return view('publisher.infleuncer-review.edit', compact('data','main_category'));
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

        $obj = InfluencerReview::find($id);



        if($obj->status=="1"){

            $message = [
                'message' => 'Active review cannot be update',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($message);


        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
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
         if($request->hasfile('image'))
         {
            foreach($request->file('image') as $file)
            {


                $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $nameimagePath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($nameimagePath, file_get_contents($file));

                $images[] = $name;

            }
         }else{
         if($request->old_images) {
             foreach($request->old_images as $image) {
                 $images_edit[] = $image;
             }
         }
     }


        $obj = InfluencerReview::find($id);
        $obj->name = $request->name;
        $obj->content = $request->description;
        $obj->youtube = $request->youtube;
        $obj->company_name = $request->company_name;
        $obj->main_category_id = $request->main_category_id;


        if(isset($images)){
            $obj->feature_image = json_encode($images);
        }
        else{
            $obj->feature_image = $images_edit;
        }
        $obj->publish_date = $request->publish_date;
        // $obj->slug = Str::slug($request->name . ' ' . $request->company_name, '-');

        $obj->save();
        $message = [
            'message' => 'Influencer Review Updated Successfully',
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
