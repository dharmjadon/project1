<?php

namespace App\Http\Controllers\Admin;

use App\Events\MyEvent;
use App\Models\City;
use App\Models\Accommodation;
use App\Models\Amenties;
use App\Models\MoreInfo;
use App\Models\Landmark;
use App\Models\Amenitable;
use App\Models\SubCategory;
use App\Models\Landmarkable;
use App\Models\MainCategory;
use Illuminate\Support\Str;
use App\Models\MajorCategory;
use Illuminate\Http\Request;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use App\Models\NotificationsInfo;
use App\Models\WishListDetails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;
use Helper;
use Log;
use Illuminate\Support\Facades\Storage;
class AccommodationController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|accomadation-add', ['only' => ['create','store','create_office']]);
        $this->middleware('role_or_permission:Admin|accomadation-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|accomadation-update', ['only' => ['edit','update','update_status_attractions']]);


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Accommodation::with('subCategory', 'city')->orderby('id','desc')->orderBy('id','desc')->get();
        $auth_user_type = Auth::user()->user_type;
        if($auth_user_type!=1){
            $datas  =  $datas->where('created_by',Auth::id());
        }
        return view('admin.accommodation.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = MainCategory::where('major_category_id', 9)->get();
        $cities = City::all();
        $landmarks = Landmark::all();
        $amenities = Amenties::all();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',9)->get();
        return view('admin.accommodation.create', compact('categories', 'cities', 'landmarks', 'amenities','dynamic_main_category'));
    }
    public function create_office()
    {
        $categories = MainCategory::where('major_category_id', 9)->get();
        $cities = City::all();
        $landmarks = Landmark::all();
        $amenities = Amenties::all();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',9)->get();
        return view('admin.accommodation.create_office', compact('categories', 'cities', 'landmarks', 'amenities','dynamic_main_category'));
    }
    public function create_party()
    {
        $categories = MainCategory::where('major_category_id', 9)->get();
        $cities = City::all();
        $landmarks = Landmark::all();
        $amenities = Amenties::all();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',9)->get();
        return view('admin.accommodation.create_party_space', compact('categories', 'cities', 'landmarks', 'amenities','dynamic_main_category'));
    }
    public function create_buyProperty()
    {
        $categories = MainCategory::where('major_category_id', 9)->get();
        $cities = City::all();
        $landmarks = Landmark::all();
        $amenities = Amenties::all();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',9)->get();
        return view('admin.accommodation.create_buy', compact('categories', 'cities', 'landmarks', 'amenities','dynamic_main_category'));
    }
    public function create_rentProperty()
    {
        $categories = MainCategory::where('major_category_id', 9)->get();
        $cities = City::all();
        $landmarks = Landmark::all();
        $amenities = Amenties::all();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',9)->get();
        return view('admin.accommodation.create_rent', compact('categories', 'cities', 'landmarks', 'amenities','dynamic_main_category'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try
        {


            // DB::beginTransaction();

            // return $request->all();
            $validator = Validator::make($request->all(), [
            //     'name' => 'required',
            //     'description' => 'required',
                'name' => 'required|unique:accommodations,title',
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'city_id' => 'required',
                // 'citylong' => 'required',
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

            $featured = '';
            if($request->featured_img){
            $featured = rand(100,100000).'.'.time().'.'.$request->featured_img->extension();
            $imageFullPath = config('app.upload_accommodation_path') . $featured;
            Storage::disk('s3')->put($imageFullPath, file_get_contents($request->featured_img));
            }

            $icon = '';
            if($request->icon){
            $icon = rand(100,100000).'.'.time().'.'.$request->icon->extension();
            $imageFullPath = config('app.upload_accommodation_path') . $icon;
            Storage::disk('s3')->put($imageFullPath, file_get_contents($request->icon));
            }

            $data = [];
            if($request->images)
            {
                $image_parts = explode(";base64,", $request->images);
                foreach($image_parts as $key => $file) {
                    if($key == 0){
                        continue;
                    }
                    $image_base64 = base64_decode($image_parts[$key]);
                    $stories = uniqid() .time(). '.png';
                    $imageFullPath = config('app.upload_accommodation_path') . $stories;
                    Storage::disk('s3')->put($imageFullPath, $image_base64);
                    $data[] = $stories;
                }
            }

            $data_stories = [];
            if($request->stories)
            {
                $image_parts = explode(";base64,", $request->stories);
                foreach($image_parts as $key => $file) {
                    if($key == 0){
                        continue;
                    }
                    $image_base64 = base64_decode($image_parts[$key]);
                    $stories = uniqid() .time(). '.png';
                    $imageFullPath = config('app.upload_accommodation_path') . $stories;
                    Storage::disk('s3')->put($imageFullPath, $image_base64);
                    $data_stories[] = $stories;
                }
            }


            $obj = new Accommodation();
            $obj->sub_category_id = $request->sub_category_id;
            $obj->title= $request->name;
            $obj->description = $request->description;
            $obj->location = $request->location;
            $obj->lat = $request->citylat;
            $obj->long = $request->citylong;
            $obj->city_id = $request->city_id;
            $obj->feature_image = $featured;
            $obj->icon = $icon;
            //$obj->start_time = $request->start_time;
            //$obj->end_time = $request->end_time;
            $obj->price = $request->price;
            $obj->areasqft = $request->areasqft;
            $obj->video = $request->video;
            $obj->amenity_id = 1;
            $obj->landmark_id = 1;
            $obj->status_text = $request->status_text;
            if(isset($data)){
                $obj->images = json_encode($data);
            }


            $obj->whatsapp = $request->whatsapp;
            $obj->contact = $request->contact;
            $obj->email = $request->email;
            if(isset($request->max_capacity)){
                $obj->max_capacity = $request->max_capacity;
            }
            $obj->created_by = Auth::user()->id;
            $obj->slug = Str::slug($request->name, '-');


            if(isset($request->accommodation_type)&& $request->accommodation_type==2){
                $obj->accommodation_type = 2;
            }
            if(isset($request->accommodation_type)&& $request->accommodation_type==3){
                $obj->accommodation_type = 3;
            }
            if(isset($request->accommodation_type)&& $request->accommodation_type==4){
                $obj->accommodation_type = 4;
            }
            if(isset($request->accommodation_type)&& $request->accommodation_type==5){
                $obj->accommodation_type = 5;
            }


            if(isset($request->dynamic_main_category)){
                $obj->dynamic_main_ids = json_encode($request->dynamic_main_category);
            }
            if(isset($request->dynamic_sub_category)){
                $obj->dynamic_sub_ids = json_encode($request->dynamic_sub_category);
            }
            if(isset($request->assign_featured)){
                $obj->assign_featured = $request->assign_featured;
            }else{
                $obj->assign_featured = 0;
            }
            if(count($data_stories)>0){
                $obj->stories = json_encode($data_stories);
            }

            $obj->meta_img_alt = $request->meta_img_alt;
            $obj->meta_img_title = $request->meta_img_title;
            $obj->meta_img_description = $request->meta_img_description;
            $obj->meta_title = $request->meta_title;
            $obj->meta_description = $request->meta_description;
            $obj->meta_tags = $request->meta_tags;

            $obj->save();


            if($request->amenities){
            foreach($request->amenities as $amenity) {
                $amenitable = new Amenitable(['amenity_id' => $amenity]);
                $venue = Accommodation::find($obj->id);
                $venue->amenities()->save($amenitable);
            }
        }
        if($request->landmark){
            foreach($request->landmark as $key => $value) {
                if(isset($value['description']) != null || isset($value['name']) != null) {
                $landmarkable = new Landmarkable(['landmark_id' => $value['name'], 'description' =>  $value['description']]);
                $venue = Accommodation::find($obj->id);
                $venue->landmarks()->save($landmarkable);
                }
            }
        }

        $id = $obj->id;

        // $request->module_id=$obj->id;
        // $request->module_name='space';
        // $request->user_type='admin';
        // $res=saveCommoncomponent($request);
        // if($res==false)
        // {
        //     $message = ['message' => 'Property Save Unsuccessful','alert-type' => 'danger'];
        //     DB::rollback();
        //     return redirect()->back()->with($message);
        // }
        // DB::commit();
            if(isset($request->accommodation_type)&& $request->accommodation_type==1){
            $message = [
                'message' => 'Accommodation Saved Successfully',
                'alert-type' => 'success',
                'primary_id' => $id
            ];}
            elseif(isset($request->accommodation_type)&& $request->accommodation_type==2){
                $message = [
                    'message' => 'Office Saved Successfully',
                    'alert-type' => 'success',
                    'primary_id' => $id
                ];

            } elseif(isset($request->accommodation_type)&& $request->accommodation_type==3){
                $message = [
                    'message' => 'Party Space Saved Successfully',
                    'alert-type' => 'success',
                    'primary_id' => $id
                ];

            }
            elseif(isset($request->accommodation_type)&& $request->accommodation_type==4){
                $message = [
                    'message' => 'Buy Porperty Saved Successfully',
                    'alert-type' => 'success',
                    'primary_id' => $id
                ];

            }
            elseif(isset($request->accommodation_type)&& $request->accommodation_type==5){

                $message = [
                    'message' => 'Property Rent Saved Successfully',
                    'alert-type' => 'success',
                    'primary_id' => $id
                ];
            }


            return redirect()->back()->with($message);


        }
        catch(\Exception $e)
        {
            DB::rollback();
            Log::info('Space edit failed ID:'.$request->module_id.' Date:'.date('Y-m-d H:i:s').' Response: '.json_encode($e->getMessage()));
            $message = ['message' =>$e->getMessage(),'alert-type' => 'danger'];
            return redirect()->back()->with($message);
        }

    }

    public function save_more_info(Request $request){

        $request->module_id= $request->primary_id;
        $request->module_name='space';
        $request->user_type='admin';
        $res=saveCommoncomponent($request);
        if($res==false)
        {
            $message = ['message' => 'Property Save Unsuccessful','alert-type' => 'danger'];
            // DB::rollback();
            return redirect()->back()->with($message);
        }


        if(isset($request->delitems) && isset($request->delitems[0]))
        {
            $d=$request->delitems;
            $arr=explode(',',$d[0]);
            $delres=deleteMorinfo($arr);
        }


        $message = [
            'message' => 'Property more info Saved Successfully',
            'alert-type' => 'success',
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

        $data = Accommodation::with('amenities', 'landmarks')->where('slug', $id)->first();

        if (Auth::user()->user_type != 1 && Auth::id() != $data->created_by) {
            abort(403);
        }
        $venueMainCategory = SubCategory::where('id', $data->sub_category_id)->value('main_category_id');
        $categories = MainCategory::where('major_category_id', 9)->get();
        $subCategory = SubCategory::where('main_category_id', $venueMainCategory)->get();
        $more_info=MoreInfo::where(['module_id'=>$data->id,'module_name'=>'space'])->get();

        $cities = City::all();
        $landmarks = Landmark::all();
        $amenities = Amenties::all();
        $four_images = json_decode($data->images);
        $story_four_images = json_decode($data->stories);
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',9)->get();
        $main_category_ids = isset($data->dynamic_main_ids) ? json_decode($data->dynamic_main_ids) : [];
        $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id',$main_category_ids)->get();
        return view('admin.accommodation.edit',get_defined_vars());

        /*return view('admin.accommodation.edit',compact('categories','cities','landmarks','data','subCategory','venueMainCategory','amenities','four_images',
            'story_four_images','dynamic_main_category','dynamic_sub_category', 'landmarks'));*/
    }


    public function preview($id)
    {

        $data = Accommodation::with('subCategory', 'city', 'amenities', 'landmarks')->where('slug', $id)->first();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data->video, $match) ){
            $youtube = $match[1];
        }else{
            $youtube = '';
        }
        return view('admin.accommodation.preview', compact('data','youtube'));
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

        try
        {
            if($request->featured_img) {
                $featured = rand(100,100000).'.'.time().'.'.$request->featured_img->extension();
                $imageFullPath = config('app.upload_directory_path') . $featured;
                Storage::disk('s3')->put($imageFullPath, file_get_contents($request->featured_img));
            }

            if($request->icon) {
                $icon = rand(100,100000).'.'.time().'.'.$request->icon->extension();
                $imageFullPath = config('app.upload_directory_path') . $icon;
                Storage::disk('s3')->put($imageFullPath, file_get_contents($request->icon));
            }

            if($request->images)
            {
                $image_parts = explode(";base64,", $request->images);
                foreach($image_parts as $key => $file) {
                    if($key == 0){
                        continue;
                    }
                    $image_base64 = base64_decode($image_parts[$key]);
                    $stories = uniqid() .time(). '.png';
                    $imageFullPath = config('app.upload_job_path') . $stories;
                    Storage::disk('s3')->put($imageFullPath, $image_base64);
                    $data[] = $stories;
                }
            }
            foreach($request->old_images as $image) {
                $data[] = $image;
            }


            $data_stories = [];
            if($request->stories)
            {
                $image_parts = explode(";base64,", $request->stories);
                foreach($image_parts as $key => $file) {
                    if($key == 0){
                        continue;
                    }
                    $image_base64 = base64_decode($image_parts[$key]);
                    $stories = uniqid() .time(). '.png';
                    $imageFullPath = config('app.upload_job_path') . $stories;
                    Storage::disk('s3')->put($imageFullPath, $image_base64);
                    $data_stories[] = $stories;
                }
            }

            if($request->old_stories) {
                foreach($request->old_stories as $stories) {
                    $data_stories[] = $stories;
                }
            }

            DB::beginTransaction();

            $obj = Accommodation::find($id);
            $obj->sub_category_id = $request->sub_category_id;
            $obj->title= $request->name;
            $obj->description = $request->description;
            $obj->lat = $request->citylat;
            $obj->long = $request->citylong;
            $obj->city_id = $request->city_id;
            $obj->video = $request->video;
            $obj->status_text = $request->status_text;

            if(isset($data)) {
                $obj->images = json_encode($data);
            }
            if($request->file('featured_img')){
                $obj->feature_image = $featured;
            }
            if($request->file('icon')){
                $obj->icon = $icon;
            }
            if(isset($data_stories)){
                $obj->stories = json_encode($data_stories);
            }
            $obj->whatsapp = $request->whatsapp;
            $obj->contact = $request->contact;
            $obj->email = $request->email;
            if(isset($request->max_capacity)){
                $obj->max_capacity = $request->max_capacity;
            }
            if(isset($request->dynamic_main_category)) {
                $obj->dynamic_main_ids = json_encode($request->dynamic_main_category);
            }else{
                $obj->dynamic_main_ids = null;
            }
            if(isset($request->dynamic_sub_category)) {
                $obj->dynamic_sub_ids = json_encode($request->dynamic_sub_category);
            }else{
                $obj->dynamic_sub_ids = null;
            }
            if(isset($request->assign_featured)){
                $obj->assign_featured = $request->assign_featured;
            }else{
                $obj->assign_featured = 0;
            }

            $obj->meta_img_alt = $request->meta_img_alt;
            $obj->meta_img_title = $request->meta_img_title;
            $obj->meta_img_description = $request->meta_img_description;
            $obj->meta_title = $request->meta_title;
            $obj->meta_description = $request->meta_description;
            $obj->meta_tags = $request->meta_tags;

            $obj->save();
            // $request->module_id=$id;
            // $request->module_name='space';
            // $request->user_type='admin';
            // /*dd($request->input());*/
            // $res=saveCommoncomponent($request);
            // if($res==false)
            // {
            //     DB::rollback();
            // }

            // if(isset($request->delitems) && isset($request->delitems[0]))
            // {
            //   $d=$request->delitems;
            //   $arr=explode(',',$d[0]);
            //   $delres=deleteMorinfo($arr);

            // }


                //sending notfication to particular for wishlists
                $wishlist_notifications = WishListDetails::where('item_type','App\Models\Accommodation')
                ->where('item_id','=',$id)
                ->where('status','=',"1")
                ->where('created_by','!=',Auth::user()->id)
                ->where('is_notification_need','=',"1")
                ->get();

                $title_msg = "Accommodation Updated";
                $description = $request->name;
                $route  = route('accommodation-view-more', $obj->slug);
                Helper::send_notification_wishlist_guys($wishlist_notifications,$route,$title_msg,$description);
                //sending notfication to particular for wishlists

            Amenitable::where('amenitable_id', $id)->delete();
            if($request->amenities){
                    foreach($request->amenities as $amenity) {
                        $amenitable = new Amenitable(['amenity_id' => $amenity]);
                        $venue = Accommodation::find($id);
                        $venue->amenities()->save($amenitable);
                    }
           }

            Landmarkable::where('landmarkable_id', $id)->delete();
            if($request->landmark){
            foreach($request->landmark as $key => $value) {
                if(isset($value['description']) != null || isset($value['name']) != null) {
                $landmarkable = new Landmarkable(['landmark_id' => $value['name'], 'description' =>  $value['description']]);
                $venue = Accommodation::find($id);
                $venue->landmarks()->save($landmarkable);
                }
            }
        }
        $message=[];
            if( $obj->accommodation_type==1){
                $message = [
                    'message' => 'Accommodation Updated Successfully',
                    'alert-type' => 'success'
                ];
            }
            elseif($obj->accommodation_type==2){
                $message = [
                    'message' => 'Office Updated Successfully',
                    'alert-type' => 'success'
                ];

            }elseif($obj->accommodation_type==3){
                $message = [
                    'message' => 'Party Space Updated Successfully',
                    'alert-type' => 'success'
                ];

            }
            DB::commit();
            return redirect()->back()->with($message);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            Log::info('Space edit failed ID:'.$request->module_id.' Date:'.date('Y-m-d H:i:s').' Response: '.json_encode($e->getMessage()));
            $message = ['message' =>$e->getMessage(),'alert-type' => 'danger'];
            return redirect()->back()->with($message);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Amenitable::where('amenitable_id', $request->id)->where('amenitable_type', 'like', '%Accommodation%')->delete();
        Landmarkable::where('landmarkable_id', $request->id)->where('landmarkable_type', 'like', '%Accommodation%')->delete();
        $obj = Accommodation::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Accommodation Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function update_status_accommodation(Request $request)
    {
        $obj = Accommodation::find($request->id);
        $obj->status = $request->status;
        $obj->save();


        if($obj->is_publisher=="1"){

            if($request->status=="1"){
                $satuts_label = "Approved";
            }else{
                $satuts_label = "Rejected";
            }

            $description_event = $satuts_label." By Admin";
            $message_event = "Admin {$satuts_label} accommodation list";
            $url_now = route('publisher.accommodation.edit', $obj->slug);

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
