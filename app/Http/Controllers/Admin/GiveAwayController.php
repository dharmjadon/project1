<?php

namespace App\Http\Controllers\Admin;

use App\Models\GiveAway;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Amenties;
use App\Models\City;
use App\Models\DynamicMainCategory;
use App\Models\DynamicSubCategory;
use App\Models\Landmark;
use App\Models\MainCategory;
use App\Models\PopularPlacesTypes;
use App\Models\SubCategory;
use App\Models\WishListDetails;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Helper;
use Illuminate\Support\Facades\Storage;
use DB;

class GiveAwayController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|blog-add', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Admin|blog-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|blog-update', ['only' => ['edit','update']]);
        // $this->middleware('role_or_permission:Admin|applied-candidate-view', ['only' => ['applied_candidate']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = GiveAway::all();
        return view('admin.give-away.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $main_category = MainCategory::where('major_category_id',12)->get();

        $amenties = Amenties::all();
        $landmarks = Landmark::all();
        $cities = City::all();
        $main_category = MainCategory::where('major_category_id','=','12')->get();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',12)->get();

        $popular_types =  PopularPlacesTypes::all();



        return view('admin.give-away.create',compact('popular_types','amenties','landmarks','main_category','cities','dynamic_main_category'));
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
            'main_category' => 'required',
            'sub_category' => 'required',
            'city' => 'required',
            'title' => 'required|unique:give_aways,title',
            'publish_date' => 'required',



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



          //valdition images 4
        if($request->hasFile('images')) {

            $images = $request->file('images');
            if(count($images)>4){

                $message = [
                    'message' => "images should not be more than 4",
                    'alert-type' => 'error',
                ];
                return redirect()->back()->with($message);

            }elseif(count($images)< 4){

                $message = [
                    'message' => "images should not be less than 4",
                    'alert-type' => 'error',
                ];
                return redirect()->back()->with($message);


            }
        }

         //valdition images 4



        //four iamges upload
        $images = $request->file('images');
        $four_images_array  = [];
        if($request->hasFile('images')) {
            foreach($images as $file)
            {
                $filename = rand(100,100000).''.time() . "_" . $request->date . '.'.$file->extension();
                $imageFullPath = config('app.upload_giveaway_path') . $filename;
                Storage::disk('s3')->put($imageFullPath, file_get_contents($file));
                array_push($four_images_array, $filename);
            }
        }




        //feature image upload
        $feature_image_path = "";
        if($request->feature_image){
            $feature_image_extension = pathinfo($_FILES['feature_image']['name'], PATHINFO_EXTENSION);
            $feature_image_path = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
            $imageFullPath = config('app.upload_giveaway_path') . $feature_image_path;
            Storage::disk('s3')->put($imageFullPath, file_get_contents($request->feature_image));
        }





        if(!empty($_FILES['youtube_image']['name'])) {
            $youtube_image_extension = pathinfo($_FILES['youtube_image']['name'], PATHINFO_EXTENSION);
            $youtube_image_path = rand(100,100000).''.time() . "_" . $request->date . '.' . $youtube_image_extension;
            $imageFullPath = config('app.upload_giveaway_path') . $youtube_image_path;
            Storage::disk('s3')->put($imageFullPath, file_get_contents($request->youtube_image));
        }


        //landmark
        $landmark_ids = $request->landmark_array;
        $land_array = explode(",",$landmark_ids);

        $lanmark_save_array = array();

        foreach($land_array as $ids){
            $land_mark_icon = "landmarkicon_".$ids;
            $landmark_heading = "landmarkheading_".$ids;
            $landmark_desc = "landmarkdesc_".$ids;

            if(isset($request->$land_mark_icon)){
              $array_to_landmark = array(
                  'landmark_icon' => $request->$land_mark_icon,
                  'landmark_heading' => $request->$landmark_heading,
                  'landmark_desc' => $request->$landmark_desc,
              );
              $lanmark_save_array [] = $array_to_landmark;
            }
        }

        DB::beginTransaction();

        try{
            $event = new  GiveAway();
            $event->sub_category_id = $request->sub_category;
            $event->title = $request->title;
            $event->lat = $request->citylat;
            $event->lng = $request->citylong;
            $event->location_name = $request->location;
            $event->city_id = $request->city;
            $event->content = $request->description;
            $event->images = json_encode($four_images_array,JSON_UNESCAPED_SLASHES);
            $event->feature_image = $feature_image_path;
            $event->youtube_img = $request->youtube_img;
            if($request->youtube_img == 1) {
                $event->video = $request->video;
            }elseif($request->youtube_img == 2) {
                $event->video = $youtube_image_path;
            }
            $event->publish_date = $request->publish_date;
            $event->landmarks = json_encode($lanmark_save_array,JSON_UNESCAPED_SLASHES);


            $event->slug   = Str::slug($request->title);
            $event->created_by = Auth::user()->id;

            $event->meta_title = $request->meta_title;
            $event->meta_description = $request->meta_description;
            $event->meta_tags = $request->meta_tags;
            $event->status = "0";

            $event->save();

            DB::commit();
            $message = [
                'message' => "Give away has been save successfully",
                'alert-type' => 'success',
            ];
        }catch (\Exception $e) {
            DB::rollback();
            $message = [
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ];
        }
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



        $data = GiveAway::where('slug', $id)->first();


        if (Auth::user()->user_type != 1 && Auth::id() != $data->created_by) {
            abort(403);
        }



        $four_images =  isset($data->images) ? json_decode($data->images) : [];
        $landmark_event = isset($data->landmarks) ? json_decode($data->landmarks) : [];


        $landmarks = Landmark::all();

        $cities = City::all();

        $main_category = MainCategory::where('major_category_id','=','12')->get();
        $subcatgories   = SubCategory::where('main_category_id','=',isset($data->get_subcat->mainCategory) ? $data->get_subcat->mainCategory->id : '')->get();

        $dynamic_main_category = DynamicMainCategory::where('major_category_id',12)->get();
        $main_category_ids = isset($data->dynamic_main_ids) ? json_decode($data->dynamic_main_ids) : [];
        $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id',$main_category_ids)->get();



        return view('admin.give-away.edit', compact('data','cities','main_category','dynamic_sub_category','main_category_ids','dynamic_main_category','subcatgories','four_images','landmarks','landmark_event'));
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
            'main_category' => 'required',
            'sub_category' => 'required',
            'city' => 'required',
            'title' => 'required|unique:give_aways,title,'.$id,
            'publish_date' => 'required',

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



        $four_images_array  = [];
        if($request->hasFile('images')) {

                $images = $request->file('images');
                foreach($images as $file)
                {
                    $filename = rand(100,100000).''.time() . "_" . $request->date . '.'.$file->extension();
                    $imageFullPath = config('app.upload_book_artist_path') . $filename;
                    Storage::disk('s3')->put($imageFullPath, file_get_contents($file));
                    array_push($four_images_array, $filename);
                }
        }
        if($request->old_images) {
            foreach($request->old_images as $image) {
                array_push($four_images_array, $image);
            }
        }





        //feature image upload
        if(!empty($_FILES['feature_image']['name'])) {

            $feature_image_extension = pathinfo($_FILES['feature_image']['name'], PATHINFO_EXTENSION);
            $feature_image_path = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
            $imageFullPath = config('app.upload_giveaway_path') . $feature_image_path;
            Storage::disk('s3')->put($imageFullPath, file_get_contents($request->feature_image));
        }



        if(!empty($_FILES['youtube_image']['name'])) {
            $youtube_image_extension = pathinfo($_FILES['youtube_image']['name'], PATHINFO_EXTENSION);
            $youtube_image_path = rand(100,100000).''.time() . "_" . $request->date . '.' . $youtube_image_extension;
            $imageFullPath = config('app.upload_giveaway_path') . $youtube_image_path;
            Storage::disk('s3')->put($imageFullPath, file_get_contents($request->youtube_image));
        }


        //landmark
        $landmark_ids = $request->landmark_array;
        $land_array = explode(",",$landmark_ids);

        $lanmark_save_array = array();

        foreach($land_array as $ids){
            $land_mark_icon = "landmarkicon_".$ids;
            $landmark_heading = "landmarkheading_".$ids;
            $landmark_desc = "landmarkdesc_".$ids;

            if(isset($request->$land_mark_icon)){
              $array_to_landmark = array(
                  'landmark_icon' => $request->$land_mark_icon,
                  'landmark_heading' => $request->$landmark_heading,
                  'landmark_desc' => $request->$landmark_desc,
              );
              $lanmark_save_array [] = $array_to_landmark;
            }
        }

        DB::beginTransaction();

        try{
            $data = GiveAway::find($id);
            $data->sub_category_id	 = $request->sub_category;
            $data->title = $request->title;
            $data->lat = $request->citylat;
            $data->lng	= $request->citylong;

            $data->location_name = $request->location;
            $data->city_id = $request->city;
            $data->content = $request->description;
            if(isset($four_images_array)){
                $data->images = json_encode($four_images_array,JSON_UNESCAPED_SLASHES);
            }
            if(isset($stories_four_images_array)){
                $data->stories = json_encode($stories_four_images_array,JSON_UNESCAPED_SLASHES);
            }
            if(isset($feature_image_path)){
                $data->feature_iamge = $feature_image_path;
            }


            $data->publish_date = $request->publish_date;
            $data->youtube_img = $request->youtube_img;
            if($request->youtube_img == 1) {
                $data->video = $request->video;
            }elseif($request->youtube_img == 2) {
                if(isset($youtube_image_path)){
                    $data->video = $youtube_image_path;
                }
            }
            $data->landmarks = json_encode($lanmark_save_array,JSON_UNESCAPED_SLASHES);


            $data->slug   = Str::slug($request->title);

            if(isset($request->dynamic_main_category)) {
                $data->dynamic_main_ids = json_encode($request->dynamic_main_category);
            }else{
                $data->dynamic_main_ids = null;
            }
            if(isset($request->dynamic_sub_category)) {
                $data->dynamic_sub_ids = json_encode($request->dynamic_sub_category);
            }else{
                $data->dynamic_sub_ids = null;
            }


            $data->meta_title = $request->meta_title;
            $data->meta_description = $request->meta_description;
            $data->meta_tags = $request->meta_tags;



            $data->save();


            //sending notfication to particular for wish lists
            $wishlist_notifications = WishListDetails::where('item_type','App\Models\GiveAway')
            ->where('item_id','=',$id)
            ->where('status','=',"1")
            ->where('created_by','!=',Auth::user()->id)
            ->where('is_notification_need','=',"1")
            ->get();

            $title_msg = "Event Updated";
            $description = $request->title;
            $route  = route('give-away', $data->slug);
            Helper::send_notification_wishlist_guys($wishlist_notifications,$route,$title_msg,$description);
            //sending notfication to particular for wish lists

            DB::commit();

            $message = [
                'message' => "GiveAway has been update successfully",
                'alert-type' => 'success'
            ];
        }catch (\Exception $e) {
            DB::rollback();
            $message = [
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ];
        }
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
        $obj = GiveAway::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Give Away deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function update_status_giveaway(Request $request)
    {
        $obj =  GiveAway::find($request->id);
        $obj->status = $request->status;
        $obj->save();


    }


}
