<?php

namespace App\Http\Controllers\Admin;

use App\Events\MyEvent;
use App\Models\City;
use App\Models\Tickets;
use App\Models\SubCategory;
use App\Models\MainCategory;
use App\Models\TicketBanner;
use Illuminate\Http\Request;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use App\Models\NotificationsInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DB;

class TicketsController extends Controller
{


    function __construct()
    {
        $this->middleware('role_or_permission:Admin|ticket-add', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Admin|ticket-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|ticket-edit', ['only' => ['edit','update','updateStatusTicket']]);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $datas=Tickets::orderby('id','desc')->get();
        $auth_user_type = Auth::user()->user_type;
        if($auth_user_type!=1){
            $datas  =  $datas->where('created_by',Auth::id());
        }
        return view('admin.tickets.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',8)->get();
        $main_category=MainCategory::where('major_category_id','=','8')->get();
        $cities=City::all();

        return view('admin.tickets.create',compact('main_category','cities','dynamic_main_category'));
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
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'main_category' => 'required',
            'subCategory' => 'required',
            'title' => 'required',
            // 'description' => 'required',
            'city_id' => 'required',
            // 'price' => 'required',
            // 'phone' => 'required',
            // 'whatsapp' => 'required',
            // 'email' => 'required',


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

        $media = [];
        if($request->images)
        {
            // $folderPath = public_path('uploads/tickets_media/');
            $image_parts = explode(";base64,", $request->images);
            foreach($image_parts as $key => $file) {
                if($key == 0){
                    continue;
                }
                $image_base64 = base64_decode($image_parts[$key]);
                $stories = uniqid() .time(). '.png';
                $imageFullPath = config('app.upload_ticket_path') . $stories;
                Storage::disk('s3')->put($imageFullPath, $image_base64);
                $media[] = $stories;
            }
        }

        DB::beginTransaction();

        try{
            $obj = new Tickets();
            $obj->main_category_id = $request->main_category;
            $obj->sub_category_id = $request->subCategory;
            $obj->title = $request->title;
            $obj->location =  $request->city_id;
            $obj->date_and_time = $request->date_and_time;
            $obj->price = $request->price;
            $obj->featured = $request->featured ? $request->featured : 0;
            $obj->city_id = $request->city_id;
            $obj->website = $request->website;
            if(isset($request->dynamic_main_category)){
                $obj->dynamic_main_ids = json_encode($request->dynamic_main_category);
            }
            if(isset($request->dynamic_sub_category)){
                $obj->dynamic_sub_ids = json_encode($request->dynamic_sub_category);
            }
            $obj->media = json_encode($media);
            $obj->description = $request->description;
            $obj->phone = $request->phone;
            $obj->whatsapp = $request->whatsapp;
            $obj->email = $request->email;

            $obj->meta_img_alt = $request->meta_img_alt;
            $obj->meta_img_title = $request->meta_img_title;
            $obj->meta_img_description = $request->meta_img_description;
            $obj->meta_title = $request->meta_title;
            $obj->meta_description = $request->meta_description;
            $obj->meta_tags = $request->meta_tags;

            $obj->save();
            DB::commit();
            $message = [
                'message' => 'Ticket Added Successfully',
                'alert-type' => 'success'
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
    public function preview($id)
    {
        $data = Tickets::with('subCategory', 'city')->where('id', $id)->first();
        return view('admin.tickets.preview', compact('data'));
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
        $datas=Tickets::find($id);
        if (Auth::user()->user_type != 1 && Auth::id() != $datas->created_by) {
            abort(403);
        }
        $main_category=MainCategory::where('major_category_id',8)->get();
        $subcatgories   = SubCategory::where('main_category_id','=',$datas->subCategory->mainCategory->id ?? '')->get();
        $cities=City::all();

        $dynamic_main_category = DynamicMainCategory::where('major_category_id',8)->get();
        $main_category_ids = isset($datas->dynamic_main_ids) ? json_decode($datas->dynamic_main_ids) : [];
        $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id',$main_category_ids)->get();

        return view('admin.tickets.edit',compact('main_category','datas','subcatgories', 'cities','dynamic_main_category','dynamic_sub_category'));
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

        if($request->images)
        {
            // $folderPath = public_path('uploads/tickets_media/');
            $image_parts = explode(";base64,", $request->images);
            foreach($image_parts as $key => $file) {
                if($key == 0){
                    continue;
                }
                $image_base64 = base64_decode($image_parts[$key]);
                $stories = uniqid() .time(). '.png';
                $imageFullPath = config('app.upload_ticket_path') . $stories;
                Storage::disk('s3')->put($imageFullPath, $image_base64);
                $images[] = $stories;
            }
        }
        else{
            $images_edit=$request->media2;
        }

        DB::beginTransaction();

        try{
            $obj =  Tickets::find($id);
            $obj->main_category_id = $request->main_category;
            $obj->sub_category_id = $request->subCategory;
            $obj->title = $request->title;
            $obj->location =  $request->city_id;
            $obj->date_and_time = $request->date_and_time;
            $obj->price = $request->price;
            $obj->featured = $request->featured;
            $obj->city_id = $request->city_id;
            $obj->website = $request->website;
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

            if(isset($images)){
                $obj->media = json_encode($images);
            }
            else{
                $obj->media = $images_edit;
            }
            $obj->description = $request->description;
            $obj->phone = $request->phone;
            $obj->whatsapp = $request->whatsapp;
            $obj->email = $request->email;

            $obj->meta_img_alt = $request->meta_img_alt;
            $obj->meta_img_title = $request->meta_img_title;
            $obj->meta_img_description = $request->meta_img_description;
            $obj->meta_title = $request->meta_title;
            $obj->meta_description = $request->meta_description;
            $obj->meta_tags = $request->meta_tags;

            $obj->save();

            DB::commit();

            $message = [
                'message' => 'Ticket Updated Successfully',
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
        $obj = Tickets::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Ticket deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function updateStatusTicket(Request $request)
    {
        $obj = Tickets::find($request->id);
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
            $message_event = "Admin {$satuts_label} ticket list";
            $url_now = route('publisher.tickets.edit', $obj->id);

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

    public function banner()
    {
        $banner = TicketBanner::first();
        return view('admin.tickets.banner',compact('banner'));
    }

    public function save_banner(Request $request)
    {
        if($request->hasFile('banner_image'))
        {
            $data = [];
            foreach($request->file('banner_image') as $file) {

                $fileName = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $fileName_path = config('app.upload_ticket_path') . $fileName;
                Storage::disk('s3')->put($fileName_path, file_get_contents($file));

                // $name = rand(100,100000).'.'.time().'.'.$file->extension();
                // $file->move(public_path().'/uploads/ticketbanner', $name);
                $data[] = $fileName;
            }
        }
        if($request->old_images) {
            foreach($request->old_images as $image) {
                $data[] = $image;
            }
        }

        if($request->id == null) {
            $obj = new TicketBanner();
            $obj->date = $request->date;
            $obj->price = $request->price;
            $obj->location = $request->location;
            $obj->url = $request->url;
            if(isset($data)){
                $obj->image = json_encode($data);
            }
            $obj->save();
        }else {
            $obj = TicketBanner::find($request->id);
            $obj->date = $request->date;
            $obj->price = $request->price;
            $obj->location = $request->location;
            $obj->url = $request->url;
            if(isset($data)){
                $obj->image = json_encode($data);
            }
            $obj->save();
        }

        $message = [
            'message' => 'Added successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
