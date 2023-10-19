<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\MyEvent;
use App\Models\City;
use App\Models\MoreInfo;

use App\Models\Venue;
use App\Models\Events;
use App\Models\Directory;
use App\Models\Concierge;
use App\Models\BuySell;
use App\Models\Crypto;

use App\Models\Career;
use App\Models\Accommodation;
use App\Models\Motors;
use App\Models\Amenties;
use App\Models\Landmark;
use App\Models\Amenitable;
use App\Models\SubCategory;
use App\Models\Landmarkable;
use App\Models\MainCategory;
use Illuminate\Support\Str;
use App\Models\MajorCategory;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Models\NotificationsInfo;
use App\Models\WishListDetails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Helper;
use Log;
use Illuminate\Support\Facades\Storage;
class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $id=Auth::user()->id;
      $space=Accommodation::where('created_by',$id)->pluck('id')->toArray();
      $motor=Motors::where('created_by',$id)->pluck('id')->toArray();

	  $venue=Venue::where('created_by',$id)->pluck('id')->toArray();
	  $conciger=Concierge::where('created_by',$id)->pluck('id')->toArray();
	  $directory=Directory::where('created_by',$id)->pluck('id')->toArray();
	  $event=Events::where('created_by',$id)->pluck('id')->toArray();
	  $buysell=BuySell::where('created_by',$id)->pluck('id')->toArray();
    $crypto=Crypto::where('created_by',$id)->pluck('id')->toArray();


      $space_data=Career::with('spaceModule')->whereIn('module_id',$space)->where('module_type','space')
        ->orderBy('id','DESC')->get();

      $motor_data=Career::with('motorsModule')->whereIn('module_id',$motor)->where('module_type','motor')
        ->orderBy('id','DESC')->get();

	  $venue_data=Career::with('venueModule')->whereIn('module_id',$venue)->where('module_type','venue')
        ->orderBy('id','DESC')->get();
	  $conciger_data=Career::with('conciergeModule')->whereIn('module_id',$conciger)->where('module_type','concierge')
        ->orderBy('id','DESC')->get();
	  $directory_data=Career::with('directoryModule')->whereIn('module_id',$directory)->where('module_type','directory')
        ->orderBy('id','DESC')->get();
	  $event_data=Career::with('eventModule')->whereIn('module_id',$event)->where('module_type','event')
        ->orderBy('id','DESC')->get();
	  $buysell_data=Career::with('buysellModule')->whereIn('module_id',$buysell)->where('module_type','buysell')
        ->orderBy('id','DESC')->get();
         $crypto_data=Career::with('cryptoModule')->whereIn('module_id',$crypto)->where('module_type','crypto')
        ->orderBy('id','DESC')->get();

      /*$space_data=(!empty($space_data))?$space_data:NULL;
      $motor_data=(!empty($motor_data))?$motor_data:NULL;
	  $venue_data=(!empty($venue_data))?venue_data:NULL;
	  $conciger_data=(!empty($conciger_data))?conciger_data:NULL;
	  $directory_data=(!empty($directory_data))?directory_data:NULL;
	  $event_data=(!empty($event_data))?event_data:NULL;
	  $buysell_data=(!empty($buysell_data))?buysell_data:NULL;*/

        return view('admin.career.index',get_defined_vars());
    }
    public function buyMotor()
    {
      $mcategory = MainCategory::with('subCategory')->where('major_category_id',config('global.motor_major_id'))->get();

      $cities = City::all();
      $dynamic_main_category = DynamicMainCategory::where('major_category_id',config('global.motor_major_id'))->get();

      $main_category_ids = [];

      $landmarks = Landmark::all();
      $amenities = Amenties::all();
      $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id',$main_category_ids)->get();

      return view('admin.motors.buyMotor',get_defined_vars());
    }
    public function rentMotor()
    {
      $mcategory = MainCategory::with('subCategory')->where('major_category_id',config('global.motor_major_id'))->get();

      $cities = City::all();
      $dynamic_main_category = DynamicMainCategory::where('major_category_id',config('global.motor_major_id'))->get();
      $landmarks = Landmark::all();
      $amenities = Amenties::all();
      $main_category_ids = [];
      $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id',$main_category_ids)->get();

      return view('admin.motors.rentMotor',get_defined_vars());
    }
    public function saveMotors(Request $request)
    {

        try
        {
           $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'city_id' => 'required',
            ]);

            if ($validator->fails())
            {
                $validate = $validator->errors();
                $message = ['message' => $validate->first(),'alert-type' => 'error','error' => $validate->first()];
                return back()->with($message);
            }

            $featured = '';
            if($request->featured_img)
            {
                $featured = rand(100,100000).'.'.time().'.'.$request->featured_img->extension();
                $imagePath = config('app.upload_other_path') . $featured;
                Storage::disk('s3')->put($imagePath, file_get_contents($request->featured_img));
            }

            $icon = '';
            if($request->icon)
            {
                $icon = rand(100,100000).'.'.time().'.'.$request->icon->extension();
                $imagePath = config('app.upload_other_path') . $icon;
                Storage::disk('s3')->put($imagePath, file_get_contents($request->icon));
            }

            $data = [];
            if($request->images)
            {
                $image_parts = explode(";base64,", $request->images);
                foreach($image_parts as $key => $file)
                {
                    if($key == 0)
                    {
                        continue;
                    }
                    $image_base64 = base64_decode($image_parts[$key]);
                    $stories = uniqid() .time(). '.png';
                    $imagePath = config('app.upload_other_path') . $stories;
                    Storage::disk('s3')->put($imagePath, $image_base64);
                    $data[] = $stories;
                }
            }

            $data_stories = [];
            if($request->stories)
            {
                $image_parts = explode(";base64,", $request->stories);
                foreach($image_parts as $key => $file)
                {
                    if($key == 0)
                    {
                      continue;
                    }
                    $image_base64 = base64_decode($image_parts[$key]);
                    $stories = uniqid() .time(). '.png';
                    $imagePath = config('app.upload_other_path') . $stories;
                    Storage::disk('s3')->put($imagePath, $image_base64);
                    $data_stories[] = $stories;
                }
            }


            $obj =(isset($request->vid) && $request->vid!='')?Motors::find($request->vid):new Motors();
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
            $obj->video = $request->video;
            $obj->amenity_id = 1;
            $obj->landmark_id = 1;
            $obj->status_text = $request->status_text;
            if(isset($data))
            {
              $obj->images = json_encode($data);
            }


            $obj->whatsapp = $request->whatsapp;
            $obj->contact = $request->contact;
            $obj->email = $request->email;

            $obj->created_by = Auth::user()->id;
            $obj->slug = Str::slug($request->name, '-');
            $obj->accommodation_type = $request->accommodation_type;

            if(isset($request->dynamic_main_category))
            {
              $obj->dynamic_main_ids = json_encode($request->dynamic_main_category);
            }
            if(isset($request->dynamic_sub_category))
            {
              $obj->dynamic_sub_ids = json_encode($request->dynamic_sub_category);
            }
            $obj->assign_featured = 0;
            if(isset($request->assign_featured))
            {
              $obj->assign_featured = $request->assign_featured;
            }
            if(count($data_stories)>0)
            {
              $obj->stories = json_encode($data_stories);
            }

            /*Motor Filters*/

              if(isset($request->brand))
              {
                $obj->motor_brand_id = $request->brand;
              }
              if(isset($request->year))
              {
                $obj->motor_year = $request->year;
              }
              if(isset($request->kilometer))
              {
                $obj->motor_km = $request->kilometer;
              }
              if(isset($request->body_type))
              {
                $obj->motor_bodytype = $request->body_type;
              }
              if(isset($request->feul_type))
              {
                $obj->motor_fueltype = $request->feul_type;
              }
              if(isset($request->engine_power))
              {
                $obj->motor_powers = $request->engine_power;
              }
              if(isset($request->regional_space))
              {
                $obj->motor_regionalspace = $request->regional_space;
              }
              if(isset($request->sellert_type))
              {
                $obj->motor_sellertype = $request->sellert_type;
              }
              if(isset($request->transmission_type))
              {
                $obj->motor_transmission = $request->transmission_type;
              }
              if(isset($request->badges))
              {
                $obj->motor_badges = $request->badges;
              }
              if(isset($request->exports_status))
              {
                $obj->motor_export_status = $request->exports_status;
              }
              if(isset($request->colors))
              {
                $obj->motor_color = $request->colors;
              }
              if(isset($request->doors))
              {
                $obj->motor_doors = $request->doors;
              }
              if(isset($request->tech_feature))
              {
                $obj->motor_techfeature = $request->tech_feature;
              }
              if(isset($request->extras))
              {
                $obj->motor_extras = $request->extras;
              }
              if(isset($request->warranty))
              {
                $obj->motor_warranty = $request->warranty;
              }
              if(isset($request->ads_post))
              {
                $obj->motor_ads_posted = $request->ads_post;
              }
              if(isset($request->num_cylinder))
              {
                $obj->motor_num_cylinders = $request->num_cylinder;
              }
              if(isset($request->steering_side))
              {
                $obj->motor_stringside = $request->steering_side;
              }
              if(isset($request->other_filter))
              {
                $obj->motor_other = $request->other_filter;
              }


            /*End Motor Filters*/



            $obj->meta_img_alt = $request->meta_img_alt;
            $obj->meta_img_title = $request->meta_img_title;
            $obj->meta_img_description = $request->meta_img_description;
            $obj->meta_title = $request->meta_title;
            $obj->meta_description = $request->meta_description;
            $obj->meta_tags = $request->meta_tags;

            $obj->save();

            $request->module_id=$obj->id;
            $request->module_name='motors';
            $request->user_type='admin';
            $res=saveCommoncomponent($request);
            if($res==false)
            {
                $message = ['message' => 'Property Save Unsuccessful','alert-type' => 'danger'];
                DB::rollback();
                return redirect()->back()->with($message);
            }

            if($request->amenities)
            {
                if(isset($request->vid) && $request->vid!='')
                {
                  Amenitable::where('amenitable_id', $request->vid)->delete();
                }
                foreach($request->amenities as $amenity)
                {
                  $amenitable = new Amenitable(['amenity_id' => $amenity]);
                  $venue = Motors::find($obj->id);
                  $venue->amenities()->save($amenitable);
                }
            }
            if($request->landmark)
            {
              if(isset($request->vid) && $request->vid!='')
              {
                Landmarkable::where('landmarkable_id', $request->vid)->delete();
              }
                foreach($request->landmark as $key => $value)
                {
                  if(isset($value['description']) != null || isset($value['name']) != null)
                  {
                    $landmarkable = new Landmarkable(['landmark_id' => $value['name'], 'description' =>  $value['description']]);
                    $venue = Motors::find($obj->id);
                    $venue->landmarks()->save($landmarkable);
                  }
                }
            }

            /*$isUpdateMsg['fail']=($vid)? 'Update Unsuccessful' : 'Add Unsuccessful';
            $isUpdateMsg['success']=($vid)? 'Update Successful' : 'Add Successful';

            $msg['status']=$res;
            $msg['msg']=($res)? $isUpdateMsg['success'] : $isUpdateMsg['fail'];
            return redirect()->back()->with($message); */

            $message=[];
            if( $obj->accommodation_type==1)
            {
              $message = ['message' => 'Motors Buy Updated Successfully','alert-type' => 'success'];
            }
            elseif($obj->accommodation_type==2)
            {
              $message = ['message' => 'Motors Rent Updated Successfully','alert-type' => 'success'];
            }
            return redirect()->back()->with($message);
        }
        catch(\Exception $e)
        {
           Log::info('Motors add/update faild ID  response : '.json_encode($e->getMessage()));
        }
    }
    public function update_status_motors(Request $request)
    {
        $obj = Motors::find($request->id);
        $obj->status = $request->status;
        $obj->save();


        if($obj->is_publisher=="1")
        {

            if($request->status=="1"){
                $satuts_label = "Approved";
            }else{
                $satuts_label = "Rejected";
            }

            $description_event = $satuts_label." By Admin";
            $message_event = "Admin {$satuts_label} Motors list";
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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $data = Motors::with('amenities','landmarks','subCategory.mainCategory')->where('slug', $id)->first();

        $mcategory = MainCategory::with('subCategory')->where('major_category_id',config('global.motor_major_id'))->get();
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
        $story_four_images = json_decode($data->stories);
        $dynamic_main_category = DynamicMainCategory::where('major_category_id',9)->get();
        $main_category_ids = isset($data->dynamic_main_ids) ? json_decode($data->dynamic_main_ids) : [];
        $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id',$main_category_ids)->get();

        if(isset($data->id))
        {
          $more_info=MoreInfo::where(['module_id'=>$data->id,'module_name'=>'motors'])->get();
        }


        if(isset($data->accommodation_type) && $data->accommodation_type=='1')
        {
          return view('admin.motors.buyMotor',get_defined_vars());
        }
        else
        {
          return view('admin.motors.rentMotor',get_defined_vars());
        }


        /*return view('admin.accommodation.edit',compact('categories','cities','landmarks','data','subCategory','venueMainCategory','amenities','four_images','story_four_images','dynamic_main_category','dynamic_sub_category', 'landmarks'));*/
    }
    public function preview($id)
    {

        $data = Motors::with('subCategory', 'city', 'amenities', 'landmarks')->where('slug', $id)->first();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data->video, $match) ){
            $youtube = $match[1];
        }else{
            $youtube = '';
        }
        return view('admin.motors.preview', compact('data','youtube'));
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Amenitable::where('amenitable_id', $request->id)->where('amenitable_type', 'like', '%Motors%')->delete();
        Landmarkable::where('landmarkable_id', $request->id)->where('landmarkable_type', 'like', '%Motors%')->delete();
        $obj = Motors::find($request->id);
        $obj->delete();

        $message=['message'=>'Motors Deleted Successfully','alert-type'=>'success'];
        return redirect()->back()->with($message);
    }
}
