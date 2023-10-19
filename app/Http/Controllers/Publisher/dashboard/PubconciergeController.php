<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Events\MyEvent;
use App\Http\Controllers\Controller;
use App\Models\Amenties;
use App\Models\City;
use App\Models\Concierge;
use App\Models\DynamicMainCategory;
use App\Models\DynamicSubCategory;
use App\Models\Landmark;
use Illuminate\Support\Str;
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

class PubconciergeController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|publisher-user-concierge', ['only' =>
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
        $datas = Concierge::with('subCategory', 'city')->where('created_by',Auth::id())->get();
        $auth_user_type = Auth::user()->user_type;

        return view('publisher.concierges.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $categories = MainCategory::where('major_category_id', 5)->get();
        $cities = City::all();
        $landmarks = Landmark::all();
        $amenities = Amenties::all();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',5)->get();
        return view('publisher.concierges.create', compact('categories', 'cities', 'landmarks', 'amenities','dynamic_main_category'));
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
            'sub_category_id' => 'required',
            'name' => 'required',
            'city_id' => 'required',
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
        if($request->featured_img) {

            $featured = rand(100, 100000) . '.' . time() . '.' . $request->featured_img->extension();
            $path = config('app.upload_concierge_path') . $featured;
            Storage::disk('s3')->put($path, file_get_contents($request->featured_img));

        }
        $youtube_image = '';
        if($request->youtube_image) {
            $youtube_image = rand(100, 100000) . '.' . time() . '.' . $request->youtube_image->extension();
            $path = config('app.upload_concierge_path') . $youtube_image;
            Storage::disk('s3')->put($path, file_get_contents($request->youtube_image));
        }

        $data = [];
        if($request->hasfile('images')) {
            foreach($request->file('images') as $file) {

                $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $nameimagePath = config('app.upload_concierge_path') . $name;
                Storage::disk('s3')->put($nameimagePath, file_get_contents($file));

                $data[] = $name;



            }
        }

        $obj = new Concierge();
        $obj->sub_category_id = $request->sub_category_id;
        $obj->status_text = $request->status_text;
        $obj->title= $request->name;
        $obj->slug = Str::slug($request->name);
        $obj->description = $request->description;
        $obj->lat = $request->citylat;
        $obj->long = $request->citylong;
        $obj->map_review = $request->map_review;
        $obj->map_rating = $request->map_rating;
        $obj->location = $request->location;
        $obj->city_id = $request->city_id;
        $obj->feature_image = $featured;
        $obj->youtube_img = $request->youtube_img;
        if($request->youtube_img == 1) {
            $obj->video = $request->video;
        }elseif($request->youtube_img == 2) {
            $obj->video = $youtube_image;
        }
        $obj->price = $request->price;
        $obj->date = $request->date;
        $obj->images = json_encode($data);
        $obj->whatsapp = $request->whatsapp;
        $obj->contact = $request->contact;
        $obj->email = $request->email;
        $obj->created_by = Auth::user()->id;

        if (isset($request->reservation)) {
            $obj->reservation = $request->reservation;
        } else {
            $obj->reservation = 0;
        }

        if(isset($request->featured)){
            $obj->featured = $request->featured;
        }else{
            $obj->featured = 0;
        }
        if(isset($request->dynamic_main_category)){
            $obj->dynamic_main_ids = json_encode($request->dynamic_main_category);
        }
        if(isset($request->dynamic_sub_category)){
            $obj->dynamic_sub_ids = json_encode($request->dynamic_sub_category);
        }
        $obj->is_draft = $request->is_draft;
        $obj->is_publisher = "1";
        $obj->save();

        $id = $obj->id;

        $user = Auth::user();

        if($user->hasRole('publisher-user-concierge')){

        }else{
            $user->assignRole('publisher-user-concierge');
        }



        if($request->is_draft=="0"){

            $description_event = "Submitted by".' '.Auth::user()->name;
            $message_event = "Publisher submitted a new concierge";
            $url_now = route('admin.concierge.edit', $obj->id);

                event(new MyEvent($message_event,$description_event,$url_now,"0"));


            $notification = new NotificationsInfo();
            $notification->title = $message_event;
            $notification->description =  $description_event;
            $notification->notification_for = 0;
            $notification->url = $url_now;
            $notification->save();

        }

        $message = [
            'message' => 'Concierge Added Successfully',
            'alert-type' => 'success',
            'primary_id' => $id
        ];
        return redirect()->back()->with($message);
    }

    public function save_more_info(Request $request){


        $request->module_id=$request->primary_id;
        $request->module_name='concierge';
        $request->user_type='publisher';
        /*dd($request->input());*/
        $res=saveCommoncomponent($request);
        if($res==false)
        {

            $message = [
                'message' => 'Concierge  more info has some issues try again.!',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($message);
        }
        if(isset($request->delitems) && isset($request->delitems[0]))
		{
		  $d=$request->delitems;
		  $arr=explode(',',$d[0]);
		  $delres=deleteMorinfo($arr);

		}


        $message = [
            'message' => 'Concierge more info has been saved Successfully',
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

        $data = Concierge::find($id);
        if (Auth::user()->user_type != 1 && Auth::id() != $data->created_by) {
            abort(403);
        }
        $venueMainCategory = SubCategory::where('id', $data->sub_category_id)->value('main_category_id');
        $categories = MainCategory::where('major_category_id', 5)->get();
        $subCategory = SubCategory::where('main_category_id', $venueMainCategory)->get();

        $more_info = MoreInfo::where(['module_id'=>$data->id,'module_name'=>'concierge'])->get();

        $cities = City::all();
        $landmarks = Landmark::all();
        $amenities = Amenties::all();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',5)->get();
        $main_category_ids = isset($data->dynamic_main_ids) ? json_decode($data->dynamic_main_ids) : [];
        $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id',$main_category_ids)->get();
        return view('publisher.concierges.edit', get_defined_vars());

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



        if($request->featured_img) {

            $featured = rand(100, 100000) . '.' . time() . '.' . $request->featured_img->extension();
            $path = config('app.upload_concierge_path') . $featured;
            Storage::disk('s3')->put($path, file_get_contents($request->featured_img));

        }

        if($request->youtube_image) {

            $youtube_image = rand(100, 100000) . '.' . time() . '.' . $request->youtube_image->extension();
            $path = config('app.upload_concierge_path') . $youtube_image;
            Storage::disk('s3')->put($path, file_get_contents($request->youtube_image));

        }

        if($request->images) {
            foreach($request->images as $file) {


                $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $nameimagePath = config('app.upload_concierge_path') . $name;
                Storage::disk('s3')->put($nameimagePath, file_get_contents($file));

                $data[] = $name;


            }
        }
        if($request->old_images) {
            foreach($request->old_images as $image) {
                $data[] = $image;
            }
        }

        $obj = Concierge::find($id);
        $obj->sub_category_id = $request->sub_category_id;
        $obj->title= $request->name;
        $obj->description = $request->description;
        $obj->status_text = $request->status_text;
        $obj->lat = $request->citylat;
        $obj->long = $request->citylong;
        $obj->map_review = $request->map_review;
        $obj->map_rating = $request->map_rating;
        $obj->city_id = $request->city_id;
        $obj->youtube_img = $request->youtube_img;
        if($request->youtube_img == 1) {
            $obj->video = $request->video;
        }elseif($request->youtube_img == 2) {
            if($request->file('youtube_image')){
                $obj->video = $youtube_image;
            }
        }
        if(isset($data)) {
            $obj->images = json_encode($data);
        }
        if($request->file('featured_img')){
            $obj->feature_image = $featured;
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
        $obj->whatsapp = $request->whatsapp;
        $obj->price = $request->price;
        $obj->date = $request->date;
        $obj->contact = $request->contact;
        $obj->email = $request->email;


        if (isset($request->reservation)) {
            $obj->reservation = $request->reservation;
        }

        if($obj->is_draft=="1" && $request->is_draft=="0"){

            $description_event = "Submitted by".' '.Auth::user()->name;
            $message_event = "Publisher submitted a new concierge";
            $url_now = route('admin.concierge.edit', $obj->id);

                event(new MyEvent($message_event,$description_event,$url_now,"0"));
            $notification = new NotificationsInfo();
            $notification->title = $message_event;
            $notification->description =  $description_event;
            $notification->notification_for = 0;
            $notification->url = $url_now;
            $notification->save();
        }


        //sending notfication to particular for wishlists
        $wishlist_notifications = WishListDetails::where('item_type','App\Models\Concierge')
        ->where('item_id','=',$id)
        ->where('status','=',"1")
        ->where('created_by','!=',Auth::user()->id)
        ->where('is_notification_need','=',"1")
        ->get();

        $title_msg = "Concierge Updated";
        $description = $request->name;
        $route  = route('concierge-more', $obj->slug);
        Helper::send_notification_wishlist_guys($wishlist_notifications,$route,$title_msg,$description);
        //sending notfication to particular for wishlists


        $obj->is_draft = $request->is_draft;
        $obj->is_publisher = "1";


        $obj->save();
        $message = [
            'message' => 'Concierge Updated Successfully',
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
        //

        $obj = Concierge::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Concierge deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);

    }
}
