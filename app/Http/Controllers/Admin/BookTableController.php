<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Amenties;
use App\Models\Landmark;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use App\Models\BookTable;
use App\Models\State;
use Illuminate\Support\Facades\Validator;
use Helper;
use Illuminate\Support\Facades\Storage;

class BookTableController extends Controller
{


    function __construct()
    {
        $this->middleware('role_or_permission:Admin|book-table-add', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Admin|book-table-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|book-table-edit', ['only' => ['edit','update','update_status_event']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //   dd('kk');
        $book_tables  = BookTable::with('get_category','city')->orderby('id','desc')->get();
        return view('admin.book-table.index',compact('book_tables'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $amenties = Amenties::all();
        $landmarks = Landmark::all();
        $cities = State::all();
        $category = MainCategory::where('major_category_id','=','11')->get();
        return view('admin.book-table.create',compact('amenties','landmarks','category','cities'));

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
            'category_id' => 'required',
            'title' => 'required|unique:book_tables,title',
            'city' => 'required',

        ]);

        // if($validator->fails()) {
        //     $validate = $validator->errors();

        //     $message = [
        //         'message' => $validate->first(),
        //         'alert-type' => 'error',
        //         'error' => $validate->first()
        //     ];
        //     return redirect()->back()->with($message);
        // }


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

        //four iamges upload
        $images = $request->file('images');
        $four_images_array  = [];

        if($request->hasFile('images')) {
            foreach($images as $file)
            {
                $filename = rand(100,100000).''.time() . "_" . $request->date . '.'.$file->extension();
                $imagePath = config('app.upload_other_path') . $filename;
                Storage::disk('s3')->put($imagePath, file_get_contents($file));
                array_push($four_images_array, $filename);
            }
        }


        //logo upload
        $logo_extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $logo_path = rand(100,100000).''.time() . "_" . $request->date . '.' . $logo_extension;
        $imagePath = config('app.upload_other_path') . $logo_path;
        Storage::disk('s3')->put($imagePath, file_get_contents($request->logo));

        //menu_image upload
        // $menu_image_extension = pathinfo($_FILES['menu_image']['name'], PATHINFO_EXTENSION);
        // $menu_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $menu_image_extension;

        // move_uploaded_file($_FILES["menu_image"]["tmp_name"], '../public/uploads/book_tables/menu_image/' . $menu_image_name);
        // $menu_image_path = 'uploads/book_tables/menu_image/' . $menu_image_name;

        //feature image upload
        $feature_image_extension = pathinfo($_FILES['feature_image']['name'], PATHINFO_EXTENSION);
        $feature_image_path  = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
        $imagePath = config('app.upload_other_path') . $feature_image_path ;
        Storage::disk('s3')->put($imagePath, file_get_contents($request->feature_image));

        if(!empty($_FILES['youtube_image']['name'])) {
            $youtube_image_extension = pathinfo($_FILES['youtube_image']['name'], PATHINFO_EXTENSION);
            $youtube_image_path = rand(100,100000).''.time() . "_" . $request->date . '.' . $youtube_image_extension;
            $imagePath = config('app.upload_other_path') . $youtube_image_path;
            Storage::disk('s3')->put($imagePath, file_get_contents($request->youtube_image));
        }

        //amenties work
        $amenties_ids = $request->amentie_array;
        $now_array = explode(",",$amenties_ids);

        $amentie_save_array = array();

        foreach($now_array as $ids){
            $amentie_name = "amentie_icon_".$ids;
            if(isset($request->$amentie_name)){
                $amentie_save_array [] = $request->$amentie_name;
            }
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


        $book_table = new  BookTable();
        $book_table->category_id = $request->category;
        $book_table->title = $request->title;
        $book_table->lat = $request->citylat;
        $book_table->lng = $request->citylong;
        $book_table->map_review = $request->map_review;
        $book_table->map_rating = $request->map_rating;
        $book_table->location_name = $request->location;
        $book_table->city_id = $request->city;
        $book_table->description = $request->description;
        $book_table->images = json_encode($four_images_array,JSON_UNESCAPED_SLASHES);
        $book_table->feature_image = $feature_image_path;
        $book_table->youtube_img = $request->youtube_img;
        if($request->youtube_img == 1) {
            $book_table->video = $request->video;
        }elseif($request->youtube_img == 2) {
            $book_table->video = $youtube_image_path;
        }
        $book_table->logo = $logo_path;
        // $book_table->menu_image = $menu_image_path;

        $book_table->amenties = json_encode($amentie_save_array,JSON_UNESCAPED_SLASHES);
        $book_table->landmarks = json_encode($lanmark_save_array,JSON_UNESCAPED_SLASHES);
        $book_table->price = $request->price;
        $book_table->whatsapp = $request->whatsapp;
        $book_table->email = $request->email;
        $book_table->mobile = $request->mobile;
        $book_table->assign_featured = $request->assign_featured?$request->assign_featured:0;
        $book_table->slug   = Str::slug($request->title);

        $book_table->save();
        $message = [
            'message' => "Book Table has been save successfully",
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
        $book_table = BookTable::find($id);

        $four_images = json_decode($book_table->images);
        $amenties_event = json_decode($book_table->amenties);
        $landmark_event = json_decode($book_table->landmarks);

        $amenties = Amenties::all();
        $landmarks = Landmark::all();

        $cities = State::all();

        $category = MainCategory::where('major_category_id','=','10')->get();
        // $subcatgories   = SubCategory::where('main_category_id','=',isset($book_table->get_subcat->mainCategory) ? $book_table->get_subcat->mainCategory->id : '')->get();

        // $dynamic_main_category = DynamicMainCategory::where('major_category_id',2)->get();
        // $main_category_ids = isset($book_table->dynamic_main_ids) ? json_decode($book_table->dynamic_main_ids) : [];
        // // $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id',$main_category_ids)->get();

        return view('admin.book-table.edit',compact('four_images','book_table','landmark_event','amenties','amenties_event','category','landmarks',
                'cities'));
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
            'category' => 'required',
            'title' => 'required|unique:book_tables,title,'.$id,
            'city' => 'required',

        ]);
        // if($validator->fails()) {
        //     $validate = $validator->errors();

        //     $message = [
        //         'message' => $validate->first(),
        //         'alert-type' => 'error',
        //         'error' => $validate->first()
        //     ];
        //     return redirect()->back()->with($message);
        // }

         //four iamges upload

        $four_images_array  = [];
        if($request->hasFile('images')) {

                $images = $request->file('images');
                foreach($images as $file)
                {
                    $filename = rand(100,100000).''.time() . "_" . $request->date . '.'.$file->extension();
                    $imagePath = config('app.upload_other_path') . $filename ;
                    Storage::disk('s3')->put($imagePath, file_get_contents($file));
                    array_push($four_images_array, $filename);
                }
        }
        if($request->old_images) {
            foreach($request->old_images as $image) {
                array_push($four_images_array, $image);
            }
        }

         //video upload

        if(!empty($_FILES['logo']['name'])) {
            $logo_extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $logo_path = rand(100,100000).''.time() . "_" . $request->date . '.' . $logo_extension;
            $imagePath = config('app.upload_other_path') . $logo_path;
            Storage::disk('s3')->put($imagePath, file_get_contents($request->logo));
        }
         //menu_image upload

        // if(!empty($_FILES['menu_image']['name'])) {
        //     $menu_image_extension = pathinfo($_FILES['menu_image']['name'], PATHINFO_EXTENSION);
        //     $menu_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $menu_image_extension;
        //     move_uploaded_file($_FILES["menu_image"]["tmp_name"], '../public/assets/uploads/book_tables/menu_image/' . $menu_image_name);
        //     $menu_image_path = 'assets/uploads/book_tables/menu_image/' . $menu_image_name;
        // }

        //feature image upload
        if(!empty($_FILES['feature_image']['name'])) {

            $feature_image_extension = pathinfo($_FILES['feature_image']['name'], PATHINFO_EXTENSION);
            $feature_image_path= rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
            $imagePath = config('app.upload_other_path') . $feature_image_path;
            Storage::disk('s3')->put($imagePath, file_get_contents($request->feature_image));
        }

        if(!empty($_FILES['youtube_image']['name'])) {
            $youtube_image_extension = pathinfo($_FILES['youtube_image']['name'], PATHINFO_EXTENSION);
            $youtube_image_path= rand(100,100000).''.time() . "_" . $request->date . '.' . $youtube_image_extension;
            $imagePath = config('app.upload_other_path') . $feature_image_path;
            Storage::disk('s3')->put($imagePath, file_get_contents($request->youtube_image));
        }

        //amenties work
        $amenties_ids = $request->amentie_array;
        $now_array = explode(",",$amenties_ids);
        $amentie_save_array = array();

        foreach($now_array as $ids){
            $amentie_name = "amentie_icon_".$ids;
            if(isset($request->$amentie_name)){
                $amentie_save_array [] = $request->$amentie_name;
            }
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

        $book_table = BookTable::find($id);
        $book_table->category_id	 = $request->category;
        $book_table->title = $request->title;
        $book_table->lat = $request->citylat;
        $book_table->lng	= $request->citylong;
        $book_table->map_review = $request->map_review;
        $book_table->map_rating = $request->map_rating;
        $book_table->location_name = $request->location;
        $book_table->city_id = $request->city;
        $book_table->description = $request->description;
        if(isset($four_images_array)){
            $book_table->images = json_encode($four_images_array,JSON_UNESCAPED_SLASHES);
        }
        if(isset($feature_image_path)){
            $book_table->feature_image = $feature_image_path;
        }
        if(isset($logo_path)){
            $book_table->logo = $logo_path;
        }
        // if(isset($menu_image_path)){
        //     $book_table->menu_image = $menu_image_path;
        // }
        $book_table->youtube_img = $request->youtube_img;
        if($request->youtube_img == 1) {
            $book_table->video = $request->video;
        }elseif($request->youtube_img == 2) {
            if(isset($youtube_image_path)){
                $book_table->video = $youtube_image_path;
            }
        }
        $book_table->amenties = json_encode($amentie_save_array,JSON_UNESCAPED_SLASHES);
        $book_table->landmarks = json_encode($lanmark_save_array,JSON_UNESCAPED_SLASHES);
        $book_table->price = $request->price;
        $book_table->whatsapp = $request->whatsapp;
        $book_table->email = $request->email;
        $book_table->mobile = $request->mobile;
        $book_table->assign_featured = $request->assign_featured?$request->assign_featured:0;
        $book_table->slug   = Str::slug($request->title);
        $book_table->save();

        $message = [
            'message' => "Book Table has been update successfully",
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
        $book_table = BookTable::find($request->id);
        $book_table->delete();

        $message = [
            'message' => 'Book Table Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function ajax_render_subcategory(Request $request)
    {
        $sub_cat = SubCategory::where('main_category_id','=',$request->select_v)->get();
        echo json_encode($sub_cat);
        exit;
    }

    public function update_status_book_table(Request $request)
    {
        $book_table = BookTable::find($request->id);
        $book_table->status = $request->status;
        $book_table->save();
    }
}
