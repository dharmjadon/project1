<?php

namespace App\Http\Controllers\Admin;

use App\Events\MyEvent;
use App\Models\InfluencerReview;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MainCategory;
use App\Models\NotificationsInfo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class InfluencerReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = InfluencerReview::orderby('id', 'desc')->get();
        return view('admin.influencer-reviews.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $main_category = MainCategory::where('major_category_id',6)->get();

        return view('admin.influencer-reviews.create', compact('main_category'));
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

                 $name =rand(100,100000).'-'.time().'.'.$file->extension();
                 $menuPath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($menuPath, file_get_contents($file));
                 $images[] = $name;
             }
          }

        //   if(!empty($_FILES['image']['name'])) {

        //     $feature_image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        //     $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
        //     move_uploaded_file($_FILES["image"]["tmp_name"], '../public/uploads/influencer-reviews/feature_image/' . $feature_image_name);
        //     $feature_image_path = 'uploads/influencer-reviews/feature_image/' . $feature_image_name;
        // }

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
        $data = InfluencerReview::where('slug', $id)->first();
        $main_category = MainCategory::where('major_category_id',6)->get();

        return view('admin.influencer-reviews.edit', compact('data','main_category'));
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

        //  if(!empty($_FILES['image']['name'])) {

        //     $feature_image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        //     $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
        //     move_uploaded_file($_FILES["image"]["tmp_name"], '../public/uploads/influencer-reviews/feature_image/' . $feature_image_name);
        //     $feature_image_path = 'uploads/influencer-reviews/feature_image/' . $feature_image_name;
        // }


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
    public function destroy(Request $request)
    {
        $obj = InfluencerReview::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Influencer Review deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function UpdateStatusInfluencerReviews(Request $request)
    {
        $obj = InfluencerReview::find($request->id);
        $obj->status = $request->status;
        $obj->save();



         //sending notification
         if($obj->is_publisher=="1"){

            if($request->status=="1"){
                $satuts_label = "Approved";
            }else{
                $satuts_label = "Rejected";
            }

            $description_event = $satuts_label." By Admin";
            $message_event = "Admin {$satuts_label} influencer review";
            $url_now = route('publisher.influencer-reviews.edit', $obj->slug);

            event(new MyEvent($message_event,$description_event,$url_now,"1",$obj->created_by));

            $notification = new NotificationsInfo();
            $notification->title = $message_event;
            $notification->description =  $description_event;
            $notification->notification_for = 1;
            $notification->url = $url_now;
            $notification->notify_to = $obj->created_by;
            $notification->save();

        }

    }

}
