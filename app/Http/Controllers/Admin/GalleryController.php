<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Venue;
use App\Models\Gallery;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WishListDetails;
use Helper;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|gallery-add', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Admin|gallery-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|gallery-edit', ['only' => ['edit','update','updateStatusGallery']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Gallery::with('subCategory','city')->get();
        return view('admin.gallery.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = MainCategory::all();
        $cities = City::all();
        return view('admin.gallery.create', compact('categories', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $featured = '';
        if($request->file('featured_img')){
            $featured = rand(100,100000).'.'.time().'.'.$request->featured_img->extension();
            $menuPath = config('app.upload_other_path') . $featured;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->featured_img));
        }

        $data = [];
        foreach($request->file('images') as $file) {
            $name = rand(100,100000).'.'.time().'.'.$file->extension();
            $menuPath = config('app.upload_other_path') . $name;
            Storage::disk('s3')->put($menuPath, file_get_contents($file));
            $data[] = $name;
        }

        $obj = new Gallery();
        $obj->sub_category_id = $request->sub_category_id;
        $obj->name = $request->name;
        $obj->city_id = $request->city_id;
        $obj->feature_image = $featured;
        $obj->images = json_encode($data);
        $obj->slug = Str::slug($request->name, '-');
        $obj->date = $request->gallery_date;
        $obj->save();

        $message = [
            'message' => 'Gallery Added Successfully',
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
        $data = Gallery::where('id', $id)->first();
        $mainCategory = SubCategory::where('id', $data->sub_category_id)->value('main_category_id');
        $subCategory = SubCategory::where('main_category_id', $mainCategory)->get();

        $categories = MainCategory::all();
        $cities = City::all();

        $four_images = json_decode($data->images);

        return view('admin.gallery.edit', compact('data', 'cities', 'categories', 'subCategory', 'mainCategory', 'four_images'));
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
        $featured = '';
        if($request->featured_img) {
            $featured = rand(100,100000).'.'.time().'.'.$request->featured_img->extension();
            $menuPath = config('app.upload_other_path') . $featured;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->featured_img));
        }

        $data = [];
        if($request->images) {
            foreach($request->images as $file) {
                $name = rand(100,100000).'.'.time().'.'.$file->extension();
                $menuPath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($menuPath, file_get_contents($file));
                $data[] = $name;
            }
        }
        if($request->old_images) {
            foreach($request->old_images as $image) {
                $data[] = $image;
            }
        }

        $obj = Gallery::find($id);
        $obj->sub_category_id = $request->sub_category_id;
        $obj->name = $request->name;
        $obj->city_id = $request->city_id;
        if($request->featured_img) {
            $obj->feature_image = $featured;
        }
        $obj->images = json_encode($data);
        $obj->slug = Str::slug($request->name, '-');
        $obj->date = $request->gallery_date;
        $obj->save();

         //sending notfication to particular for wish lists
         $wishlist_notifications = WishListDetails::where('item_type','App\Models\Gallery')
         ->where('item_id','=',$id)
         ->where('status','=',"1")
         ->where('is_notification_need','=',"1")
         ->where('created_by','!=',Auth::user()->id)
         ->get();

         $title_msg = "Gallery Updated";
         $description = $request->title;
         $route  = route('gallery-view-more', $obj->slug);
         Helper::send_notification_wishlist_guys($wishlist_notifications,$route,$title_msg,$description);
         //sending notfication to particular for wish lists



        $message = [
            'message' => 'Gallery Updated Successfully',
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
        $obj = Gallery::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Gallery deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function updateStatusGallery(Request $request)
    {
        $obj = Gallery::find($request->id);
        $obj->active = $request->status;
        $obj->save();
    }
}
