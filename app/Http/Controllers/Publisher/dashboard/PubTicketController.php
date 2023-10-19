<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Events\MyEvent;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\DynamicMainCategory;
use App\Models\DynamicSubCategory;
use App\Models\MainCategory;
use App\Models\NotificationsInfo;
use App\Models\SubCategory;
use App\Models\TicketBanner;
use App\Models\Tickets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PubTicketController extends Controller
{


    function __construct()
    {
        $this->middleware('role_or_permission:Admin|publisher-user-tickets', ['only' =>
      ['index','edit','update','destroy','ticket_banner']]);


    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $datas =  Tickets::where('created_by',Auth::id())->get();

        return view('publisher.tickets.index',compact('datas'));
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

        return view('publisher.tickets.create',compact('main_category','cities','dynamic_main_category'));
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

        if($request->hasfile('media'))
        {
           foreach($request->file('media') as $file)
           {
               $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
               $nameimagePath = config('app.upload_ticket_path') . $name;
               Storage::disk('s3')->put($nameimagePath, file_get_contents($file));

               $media[] = $name;

           }
        }

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
        if(isset($media)){
            $obj->media = json_encode($media);
        }
        $obj->description = $request->description;
        $obj->phone = $request->phone;
        $obj->whatsapp = $request->whatsapp;
        $obj->email = $request->email;

        $obj->is_draft = $request->is_draft;
        $obj->is_publisher = "1";
        $obj->created_by = Auth::id();

        $obj->save();

        $user = Auth::user();

        if($user->hasRole('publisher-user-tickets')){

        }else{
            $user->assignRole('publisher-user-tickets');
        }


            //sending notification to admin
            if($request->is_draft=="0"){

                $description_event = "Submitted by"." ".Auth::user()->name;
                $message_event = 'Publisher Submitted a new ticket';
                $url_now = route('admin.tickets.edit', $obj->id);

                    event(new MyEvent($message_event,$description_event,$url_now,"0"));


                $notification = new NotificationsInfo();
                $notification->title = $message_event;
                $notification->description =  $description_event;
                $notification->notification_for = 0;
                $notification->url =  $url_now;
                $notification->save();

            }

        $message = [
            'message' => 'Ticket Added Successfully',
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

        $datas=Tickets::find($id);
        if (Auth::id() != $datas->created_by) {
            abort(403);
        }
        $main_category=MainCategory::where('major_category_id',8)->get();
        $subcatgories   = SubCategory::where('main_category_id','=',$datas->subCategory->mainCategory->id ?? '')->get();
        $cities=City::all();

        $dynamic_main_category = DynamicMainCategory::where('major_category_id',8)->get();
        $main_category_ids = isset($datas->dynamic_main_ids) ? json_decode($datas->dynamic_main_ids) : [];
        $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id',$main_category_ids)->get();

        return view('publisher.tickets.edit',compact('main_category','datas','subcatgories', 'cities','dynamic_main_category','dynamic_sub_category'));
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


        //

        if($request->hasfile('media'))
        {
           foreach($request->file('media') as $file)
           {
               $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
               $nameimagePath = config('app.upload_ticket_path') . $name;
               Storage::disk('s3')->put($nameimagePath, file_get_contents($file));

               $images[] = $name;
           }
        }
        else{
            $images_edit=$request->media2;
        }

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


         //sending notification to admin
         if($obj->is_draft=="1" && $request->is_draft=="0"){


            $description_event = "Submitted by"." ".Auth::user()->name;
            $message_event = 'Publisher Submitted a new ticket';
            $url_now = route('admin.tickets.edit', $obj->id);

                event(new MyEvent($message_event,$description_event,$url_now,"0"));


            $notification = new NotificationsInfo();
            $notification->title = $message_event;
            $notification->description =  $description_event;
            $notification->notification_for = 0;
            $notification->url =  $url_now;
            $notification->save();

        }

        $obj->is_draft = $request->is_draft;
        $obj->is_publisher = "1";
        $obj->created_by = Auth::id();





        $obj->save();





        $message = [
            'message' => 'Ticket Updated Successfully',
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

        $obj = Tickets::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Ticket deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);

    }

    public function ticket_banner()
    {
        $banner = TicketBanner::first();
        return view('publisher.tickets.banner',compact('banner'));
    }



}
