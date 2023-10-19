<?php

namespace App\Http\Controllers\Admin;

use App\Events\MyEvent;
use Helper;
use App\Models\City;
use App\Models\Venue;
use App\Models\Amenties;
use App\Models\Landmark;
use App\Models\Concierge;
use App\Models\MoreInfo;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Support\Str;
use App\Models\MajorCategory;
use Illuminate\Http\Request;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Models\ConciergeReservation;
use App\Http\Controllers\Controller;
use App\Models\NotificationsInfo;
use App\Models\WishListDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;

class ConciergeController extends Controller
{


    function __construct()
    {
        $this->middleware('role_or_permission:Admin|concierge-add', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Admin|concierge-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|concierge-update', ['only' => ['edit', 'update', 'update_status_concierges']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Concierge::with('subCategory', 'city')->orderby('id','desc')->get();
        $auth_user_type = Auth::user()->user_type;
        if ($auth_user_type != 1) {
            $datas  =  $datas->where('created_by', Auth::id());
        }
        return view('admin.concierge.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = MainCategory::where('major_category_id', 5)->get();
        $cities = City::all();
        $landmarks = Landmark::all();
        $amenities = Amenties::all();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id', 5)->get();
        return view('admin.concierge.create', compact('categories', 'cities', 'landmarks', 'amenities', 'dynamic_main_category'));
    }
    public function createVIP()
    {
        return view('admin.concierge.create-vip');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->is_vip == 1) {
            $validator = Validator::make($request->all(), [
                'sub_category_id' => 'required',
                'name' => 'required',
                'city_id' => 'required',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);
        }

        if ($validator->fails()) {
            $validate = $validator->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];
            return back()->with($message);
        }

        $featuredPath = '';
        if($request->featured_img) {

            $image_parts = explode(";base64,", $request->featured_img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $featured = uniqid() .time(). '.png';
            $menuPath = config('app.upload_concierge_path') . $featured;
            Storage::disk('s3')->put($menuPath, $image_base64);
            $featuredPath = $featured;
        }

        $youtube_image = '';
        if ($request->youtube_image) {
            $youtube_image = rand(100, 100000) . '.' . time() . '.' . $request->youtube_image->extension();
            $menuPath = config('app.upload_concierge_path') . $youtube_image;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->youtube_image));
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
                $menuPath = config('app.upload_concierge_path') . $stories;
                Storage::disk('s3')->put($menuPath, $image_base64);
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
                $menuPath = config('app.upload_concierge_path') . $stories;
                Storage::disk('s3')->put($menuPath, $image_base64);
                $data_stories[] = $stories;
            }
        }

        DB::beginTransaction();

        try{
            $obj = new Concierge();
            $obj->sub_category_id = $request->sub_category_id ?? 0;
            $obj->title = $request->name;
            $obj->slug = Str::slug($request->name, '-');
            $obj->description = $request->description;
            $obj->status_text = $request->status_text;
            $obj->lat = $request->citylat;
            $obj->long = $request->citylong;
            $obj->map_review = $request->map_review;
            $obj->map_rating = $request->map_rating;
            $obj->location = $request->location;
            $obj->city_id = $request->city_id ?? 0;
            $obj->feature_image = $featuredPath;
            $obj->youtube_img = $request->youtube_img;
            if ($request->youtube_img == 1) {
                $obj->video = $request->video;
            } elseif ($request->youtube_img == 2) {
                $obj->video = $youtube_image;
            }
            $obj->price = $request->price;
            $obj->date = $request->date?? null;
            $obj->images = json_encode($data);
            $obj->stories = json_encode($data_stories);
            $obj->whatsapp = $request->whatsapp;
            $obj->contact = $request->contact;
            $obj->email = $request->email;
            $obj->is_vip = $request->is_vip;
            $obj->created_by = Auth::user()->id;

            if (isset($request->featured)) {
                $obj->featured = $request->featured;
            } else {
                $obj->featured = 0;
            }
            if (isset($request->reservation)) {
                $obj->reservation = $request->reservation;
            } else {
                $obj->reservation = 0;
            }
            if (isset($request->dynamic_main_category)) {
                $obj->dynamic_main_ids = json_encode($request->dynamic_main_category);
            }
            if (isset($request->dynamic_sub_category)) {
                $obj->dynamic_sub_ids = json_encode($request->dynamic_sub_category);
            }

            $obj->meta_img_alt = $request->meta_img_alt;
            $obj->meta_img_title = $request->meta_img_title;
            $obj->meta_img_description = $request->meta_img_description;
            $obj->meta_title = $request->meta_title;
            $obj->meta_description = $request->meta_description;
            $obj->meta_tags = $request->meta_tags;

            $obj->save();

            $id  = $obj->id;

            DB::commit();
            $message = [
                'message' => 'Concierge Added Successfully',
                'alert-type' => 'success',
                'primary_id' => $id
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

    public function save_more_info(Request $request){


        $request->module_id=$request->primary_id;
        $request->module_name='concierge';
        $request->user_type='admin';
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

    public function preview($id)
    {
        $data = Concierge::with('subCategory', 'city')->where('id', $id)->first();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data->video, $match) ){
            $youtube = $match[1];
        }else{
            $youtube = '';
        }
        return view('admin.concierge.preview', compact('data','youtube'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Concierge::find($id);
        $more_info=MoreInfo::where(['module_id'=>$data->id,'module_name'=>'concierge'])->get();
        if (Auth::user()->user_type != 1 && Auth::id() != $data->created_by) {
            abort(403);
        }
        $venueMainCategory = SubCategory::where('id', $data->sub_category_id)->value('main_category_id');
        $categories = MainCategory::where('major_category_id', 5)->get();
        $subCategory = SubCategory::where('main_category_id', $venueMainCategory)->get();
        $cities = City::all();
        $landmarks = Landmark::all();
        $amenities = Amenties::all();
        $story_four_images = json_decode($data->stories);
        $dynamic_main_category = DynamicMainCategory::where('major_category_id', 5)->get();
        $main_category_ids = isset($data->dynamic_main_ids) ? json_decode($data->dynamic_main_ids) : [];
        $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id', $main_category_ids)->get();
        return view('admin.concierge.edit',get_defined_vars());
        /*return view('admin.concierge.edit', compact(
            'categories',
            'cities',
            'landmarks',
            'data',
            'story_four_images',
            'subCategory',
            'venueMainCategory',
            'amenities',
            'dynamic_main_category',
            'dynamic_sub_category'
        ));*/
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
        if($request->featured_img) {

            $image_parts = explode(";base64,", $request->featured_img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $featured = uniqid() .time(). '.png';
            $menuPath = config('app.upload_concierge_path') . $featured;
            Storage::disk('s3')->put($menuPath, $image_base64);
            $featuredPath = $featured;
        }

        if ($request->youtube_image) {
            $youtube_image = rand(100, 100000) . '.' . time() . '.' . $request->youtube_image->extension();
            $menuPath = config('app.upload_concierge_path') . $youtube_image;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->youtube_image));
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
                $menuPath = config('app.upload_concierge_path') . $stories;
                Storage::disk('s3')->put($menuPath, $image_base64);
                $data[] = $stories;
            }
        }
        if ($request->old_images) {
            foreach ($request->old_images as $image) {
                $data[] = $image;
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
                $menuPath = config('app.upload_concierge_path') . $stories;
                Storage::disk('s3')->put($menuPath, $image_base64);
                $data_stories[] = $stories;
            }
        }

        if($request->old_stories) {
            foreach($request->old_stories as $stories) {
                $data_stories[] = $stories;
            }
        }


        DB::beginTransaction();

        try{
            $obj = Concierge::find($id);
            $obj->sub_category_id = $request->sub_category_id ?? 0;
            $obj->title = $request->name;
            $obj->description = $request->description;
            $obj->status_text = $request->status_text;
            $obj->lat = $request->citylat;
            $obj->long = $request->citylong;
            $obj->map_review = $request->map_review;
            $obj->map_rating = $request->map_rating;
            $obj->city_id = $request->city_id ?? 0;
            $obj->youtube_img = $request->youtube_img;
            if ($request->youtube_img == 1) {
                $obj->video = $request->video;
            } elseif ($request->youtube_img == 2) {
                if ($request->file('youtube_image')) {
                    $obj->video = $youtube_image;
                }
            }
            if (isset($data)) {
                $obj->images = json_encode($data);
            }
            if(isset($data_stories)) {
                $obj->stories = json_encode($data_stories);
            }
            if ($request->featured_img) {
                $obj->feature_image = $featuredPath;
            }
            if (isset($request->dynamic_main_category)) {
                $obj->dynamic_main_ids = json_encode($request->dynamic_main_category);
            } else {
                $obj->dynamic_main_ids = null;
            }
            if (isset($request->dynamic_sub_category)) {
                $obj->dynamic_sub_ids = json_encode($request->dynamic_sub_category);
            } else {
                $obj->dynamic_sub_ids = null;
            }
            if (isset($request->reservation)) {
                $obj->reservation = $request->reservation;
            }
            $obj->whatsapp = $request->whatsapp;
            $obj->price = $request->price;
            $obj->date = $request->date;
            $obj->contact = $request->contact;
            $obj->email = $request->email;

            $obj->meta_img_alt = $request->meta_img_alt;
            $obj->meta_img_title = $request->meta_img_title;
            $obj->meta_img_description = $request->meta_img_description;
            $obj->meta_title = $request->meta_title;
            $obj->meta_description = $request->meta_description;
            $obj->meta_tags = $request->meta_tags;

            $obj->save();

            // $request->module_id=$id;
            // $request->module_name='concierge';
            // $request->user_type='admin';
            // /*dd($request->input());*/
            // $res=saveCommoncomponent($request);
            // if($res==false)
            // {
            //     //DB::rollback();
            // }
            // if(isset($request->delitems) && isset($request->delitems[0]))
            // {
            //   $d=$request->delitems;
            //   $arr=explode(',',$d[0]);
            //   $delres=deleteMorinfo($arr);

            // }
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

            DB::commit();
            $message = [
                'message' => 'Concierge Updated Successfully',
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
        $obj = Concierge::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Concierge deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function update_status_concierges(Request $request)
    {
        $obj = Concierge::find($request->id);
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
                $message_event = "Admin {$satuts_label} concierge list";
                $url_now = route('publisher.concierge.edit', $obj->id);

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

    public function conciergeReservation(Request $request){
        $datas = ConciergeReservation::with('concierge')->get();
        return view('admin.concierge.concierge-reservation', compact('datas'));
    }
}
