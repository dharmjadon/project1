<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Events\MyEvent;
use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Amenitable;
use App\Models\Amenties;
use Illuminate\Support\Str;
use App\Models\City;
use App\Models\DynamicMainCategory;
use App\Models\DynamicSubCategory;
use App\Models\Landmark;
use App\Models\Landmarkable;
use App\Models\MainCategory;
use App\Models\MoreInfo;
use App\Models\NotificationsInfo;
use App\Models\SubCategory;
use App\Models\WishListDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Helper;
use Illuminate\Support\Facades\Storage;

class PubAccomodationController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|publisher-user-accommodation', ['only' =>
      ['index','edit','update','destroy']]);


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $datas = Accommodation::with('subCategory', 'city')->where('created_by',Auth::id())->get();

        return view('publisher.accommodation.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = MainCategory::where('major_category_id', 9)->get();
        $cities = City::all();
        $landmarks = Landmark::all();
        $amenities = Amenties::all();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',9)->get();


        return view('publisher.accommodation.create', compact('categories', 'cities', 'landmarks', 'amenities','dynamic_main_category'));
    }

    public function office_create(){

        $categories = MainCategory::where('major_category_id', 9)->get();
        $cities = City::all();
        $landmarks = Landmark::all();
        $amenities = Amenties::all();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',9)->get();
        return view('publisher.accommodation.create_office', compact('categories', 'cities', 'landmarks', 'amenities','dynamic_main_category'));
    }



    // public function create_office(){

    //     $categories = MainCategory::where('major_category_id', 9)->get();
    //     $cities = City::all();
    //     $landmarks = Landmark::all();
    //     $amenities = Amenties::all();
    //     $dynamic_main_category = DynamicMainCategory::where('major_category_id',9)->get();
    //     return view('admin.accommodation.create_office', compact('categories', 'cities', 'landmarks', 'amenities','dynamic_main_category'));
    // }

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
            'name' => 'required',
            'landmark' => 'required',
            'amenities' => 'required',
            'citylat' => 'required',
            'citylong' => 'required',
            'price'  => 'required'
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

            $featured = rand(100, 100000) . '.' . time() . '.' . $request->featured_img->extension();
            $path = config('app.upload_accommodation_path') . $featured;
            Storage::disk('s3')->put($path, file_get_contents($request->featured_img));

        }



        $icon = '';
        if($request->icon){

            $icon = rand(100, 100000) . '.' . time() . '.' . $request->icon->extension();
            $path = config('app.upload_accommodation_path') . $icon;
            Storage::disk('s3')->put($path, file_get_contents($request->icon));

        }




        $data = [];
        foreach($request->file('images') as $file) {

            $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
            $nameimagePath = config('app.upload_accommodation_path') . $name;
            Storage::disk('s3')->put($nameimagePath, file_get_contents($file));

            $data[] = $name;

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
		$obj->images = json_encode($data);
        $obj->whatsapp = $request->whatsapp;
        $obj->contact = $request->contact;
        $obj->email = $request->email;
        $obj->created_by = Auth::user()->id;
        $obj->slug = Str::slug($request->name, '-');

        if(isset($request->accommodation_type)&& $request->accommodation_type==2){
            $obj->accommodation_type = 2;
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
        $obj->is_draft = $request->is_draft;
        $obj->is_publisher = "1";
        $obj->save();

         $id = $obj->id;

        foreach($request->amenities as $amenity) {
            $amenitable = new Amenitable(['amenity_id' => $amenity]);
            $venue = Accommodation::find($obj->id);
            $venue->amenities()->save($amenitable);
        }

        foreach($request->landmark as $key => $value) {
            $landmarkable = new Landmarkable(['landmark_id' => $value['name'], 'description' =>  $value['description']]);
            $venue = Accommodation::find($obj->id);
            $venue->landmarks()->save($landmarkable);
        }




        $user = Auth::user();

        if($user->hasRole('publisher-user-accommodation')){

        }else{
            $user->assignRole('publisher-user-accommodation');
        }

        if(isset($request->accommodation_type)&& $request->accommodation_type==2){

             //sending notification to admin
         if($request->is_draft=="0"){
            $description_event = "Submitted by".' '.Auth::user()->name;
            $message_event = "Publisher submitted a new office";
            $url_now = route('admin.accommodation.edit', $obj->slug);


                event(new MyEvent($message_event,$description_event,$url_now,"0"));


            $notification = new NotificationsInfo();
            $notification->title = $message_event;
            $notification->description =  $description_event;
            $notification->notification_for = 0;
            $notification->url =  $url_now;
            $notification->save();
         }

        $message = [
            'message' => 'Office Saved Successfully',
            'alert-type' => 'success',
            'primary_id' => $id,
        ];
        }else{
            $message = [
                'message' => 'Accommodation Saved Successfully',
                'alert-type' => 'success',
                'primary_id' => $id,
            ];

                 //sending notification to admin
         if($request->is_draft=="0"){
            $description_event = "Submitted by".' '.Auth::user()->name;
            $message_event = "Publisher submitted a new accommodation";
            $url_now = route('admin.accommodation.edit', $obj->slug);


                event(new MyEvent($message_event,$description_event,$url_now,"0"));

            $notification = new NotificationsInfo();
            $notification->title = $message_event;
            $notification->description =  $description_event;
            $notification->notification_for = 0;
            $notification->url =  $url_now;
            $notification->save();

         }

        }
        return redirect()->back()->with($message);
    }

    public function save_more_info(Request $request){

        $request->module_id= $request->primary_id;
        $request->module_name='space';
        $request->user_type='publisher';
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
        //


        $data = Accommodation::with('amenities', 'landmarks')->where('slug', $id)->first();
        if (Auth::user()->user_type != 1 && Auth::id() != $data->created_by) {
            abort(403);
        }
        $venueMainCategory = SubCategory::where('id', $data->sub_category_id)->value('main_category_id');
        $categories = MainCategory::where('major_category_id', 9)->get();
        $subCategory = SubCategory::where('main_category_id', $venueMainCategory)->get();
        $cities = City::all();
        $landmarks = Landmark::all();
        $amenities = Amenties::all();
        $four_images = json_decode($data->images);

        $more_info=MoreInfo::where(['module_id'=>$data->id,'module_name'=>'space'])->get();

        $dynamic_main_category = DynamicMainCategory::where('major_category_id',9)->get();
        $main_category_ids = isset($data->dynamic_main_ids) ? json_decode($data->dynamic_main_ids) : [];
        $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id',$main_category_ids)->get();
        return view('publisher.accommodation.edit',compact('categories','more_info','cities','landmarks','data','subCategory','venueMainCategory','amenities','four_images',
            'dynamic_main_category','dynamic_sub_category', 'landmarks'));

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

        if($request->featured_img){
            $featured = rand(100, 100000) . '.' . time() . '.' . $request->featured_img->extension();
            $path = config('app.upload_accommodation_path') . $featured;
            Storage::disk('s3')->put($path, file_get_contents($request->featured_img));
        }


        if($request->icon){
            $icon = rand(100, 100000) . '.' . time() . '.' . $request->icon->extension();
            $path = config('app.upload_accommodation_path') . $icon;
            Storage::disk('s3')->put($path, file_get_contents($request->icon));
        }





        if($request->images) {
            foreach($request->images as $file) {

                $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $nameimagePath = config('app.upload_accommodation_path') . $name;
                Storage::disk('s3')->put($nameimagePath, file_get_contents($file));

                $data[] = $name;

            }
        }
        foreach($request->old_images as $image) {
            $data[] = $image;
        }

        $obj = Accommodation::find($id);
        $obj->sub_category_id = $request->sub_category_id;
        $obj->title= $request->name;
        $obj->description = $request->description;
        $obj->lat = $request->citylat;
        $obj->long = $request->citylong;
        $obj->city_id = $request->city_id;
        $obj->video = $request->video;
        if($request->file('images')) {
            $obj->images = json_encode($data);
        }
        if($request->file('featured_img')){
            $obj->feature_image = $featured;
        }
        if($request->file('icon')){
            $obj->icon = $icon;
        }
        $obj->whatsapp = $request->whatsapp;
        $obj->contact = $request->contact;
        $obj->email = $request->email;
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

        //sending notification
        if( $obj->accommodation_type==1){
            if($obj->is_draft=="1" && $request->is_draft=="0"){

                $description_event = "Submitted by".' '.Auth::user()->name;
                $message_event = "Publisher submitted a new accommodation";
                $url_now = route('admin.accommodation.edit', $obj->slug);

                event(new MyEvent($message_event,$description_event,$url_now,"0"));

                $notification = new NotificationsInfo();
                $notification->title = $message_event;
                $notification->description =  $description_event;
                $notification->notification_for = 0;
                $notification->url =  $url_now;
                $notification->save();

            }

        }else{

            if($obj->is_draft=="1" && $request->is_draft=="0"){

                $description_event = "Submitted by".' '.Auth::user()->name;
                $message_event = "Publisher submitted a new office";
                $url_now = route('admin.accommodation.edit', $obj->slug);

                event(new MyEvent($message_event,$description_event,$url_now,"0"));

                $notification = new NotificationsInfo();
                $notification->title = $message_event;
                $notification->description =  $description_event;
                $notification->notification_for = 0;
                $notification->url =  $url_now;
                $notification->save();

            }
        }


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


        $obj->is_draft = $request->is_draft;
        $obj->is_publisher = "1";

        $obj->save();

        Amenitable::where('amenitable_id', $id)->delete();

        foreach($request->amenities as $amenity) {
            $amenitable = new Amenitable(['amenity_id' => $amenity]);
            $venue = Accommodation::find($id);
            $venue->amenities()->save($amenitable);
        }

        Landmarkable::where('landmarkable_id', $id)->delete();

        foreach($request->landmark as $key => $value) {
            $landmarkable = new Landmarkable(['landmark_id' => $value['name'], 'description' =>  $value['description']]);
            $venue = Accommodation::find($id);
            $venue->landmarks()->save($landmarkable);
        }
        if( $obj->accommodation_type==1)
        $message = [
            'message' => 'Accommodation Updated Successfully',
            'alert-type' => 'success'
        ];
        else{
            $message = [
                'message' => 'Office Updated Successfully',
                'alert-type' => 'success'
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
        //

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
}
