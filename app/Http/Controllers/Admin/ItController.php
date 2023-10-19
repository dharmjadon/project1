<?php

namespace App\Http\Controllers\Admin;

use Helper;
use App\Models\It;
use App\Models\City;
use Intervention\Image;
use App\Models\Amenties;
use App\Models\Landmark;
use App\Models\MoreInfo;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DynamicSubCategory;
use App\Models\PopularPlacesTypes;
use Illuminate\Support\Facades\DB;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ItController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|it-add', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Admin|it-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|it-update', ['only' => ['edit', 'update', 'update_status_it']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $pagetitle = 'Manage Contact Requests';
        $activeTab = 'contact-requests';
        $activeSubTab = 'list-contact-requests';
        $main_category = MainCategory::where('major_category_id', 15)->orderBy('name')->pluck('name', 'id');

        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');
        if ($request->ajax()) {
            $its = It::with('sub_category.mainCategory')->orderby('id', 'desc');
            $auth_user_type = Auth::user()->user_type;
            if ($auth_user_type != 1) {
                $its = $its->where('created_by', Auth::id());
            }
            if ($main_categories) { // && empty($keyword)
                $its->orWhereHas('sub_category.mainCategory', function ($q) use ($main_categories) {
                    $q->whereIn('id', $main_categories);
                });
            }
            // Global search function
            if ($keyword) {
                $its->where(function ($query) use ($keyword) {
                    $query->where('its.title', 'LIKE', '%' . $keyword . '%');
                });
                //"select filed_list from tbale where condition1 and condtion2 and (condition3 or condtion4 or condigiton5) and condition6 orderby column limit 20"
            }
            if (!empty($date_from) && empty($keyword)) {
                $its->where('its.created_at', '>', $date_from . " 00:00:00");
            }

            if (!empty($date_from) && empty($keyword)) {
                $its->where('its.created_at', '<=', $date_to . " 23:59:59");
            }
            // dd($its->toSql());
            $its = $its->latest()->get();

            return DataTables::of($its)
                ->addColumn('active', function ($it) use ($auth_user_type) {
                    if ($auth_user_type === 1) {
                        return '<label class="switch">
                            <input type="checkbox" class="change_status" id="' . $it->id . '" ' . ($it->status == 0 ? 'unchecked' : 'checked') . '>
                            <div class="slider round"></div>
                        </label>';
                    }
                    return '';
                })
                ->addColumn('main_category', function ($it) {
                    return isset($it->sub_category->mainCategory) ? $it->sub_category->mainCategory->name : '';
                })
                ->addColumn('sub_category', function ($it) {
                    return isset($it->sub_category) ? $it->sub_category->name : '';
                })
                ->addColumn('publisher_name', function ($it) {
                    if ($it->is_publisher == "1") {
                        return $it->publish_by->name;
                    }
                    return 'N/A';
                })
                ->addColumn('action', function ($it) {
                    $btn = '<div class="btn-group" role="group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('admin.it.edit', $it->id) . '">
                                        <i data-feather="edit"></i> Edit </a>
                                <a class="dropdown-item" href="' . route('admin.it.show', $it->slug) . '">
                                    <i data-feather="eye"></i> Preview </a>
                                <a class="dropdown-item btn-icon modal-btn" data-id="' . $it->id . '" data-target="#danger" data-toggle="modal" type="button" href="javascript:void(0)">
                                  <i data-feather="trash-2"></i> Delete </a>
                            </div>
                        </div>';
                    return $btn;

                })
                ->rawColumns(['active', 'action'])
                ->make(true);
        }

        return view('admin.it.index', get_defined_vars());
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
        $cities = City::all();
        $array_social_name = Helper::get_social_name();
        $main_category = MainCategory::where('major_category_id', 15)->get();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id', 15)->get();
        // return $main_category;


        return view('admin.it.create', get_defined_vars());
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
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'company_name' => 'required|unique:its,company_name',
            'sub_category' => 'required',
            'city' => 'required',
            'featured_image' => 'required',
        ]);
        // return $request->all();
        if ($validation->fails()) {
            # code...
            $validate = $validation->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];

            return redirect()->back()->with($message);
        }

        //Validation for four images
        if ($request->hasFile('images')) {
            # code...
            $images = $request->file('images');

            if (count($images) > 4) {
                # code...
                $message = [
                    'message' => "images should not be more than 4",
                    'alert-type' => 'error',
                ];

                return redirect()->back()->with($message);
            } elseif (count($images) < 4) {
                # code...
                $message = [
                    'message' => "images should not be less than 4",
                    'alert-type' => 'error',
                ];

                return redirect()->back()->with($message);
            }
        }

        //Four images upload after validation
        $data = [];
        if ($request->images) {
            # code...
            $image_parts = explode(";base64,", $request->images);
            foreach ($image_parts as $key => $file) {
                # code...
                if ($key == 0) {
                    # code...
                    continue;
                }
                $image_base64 = base64_decode($image_parts[$key]);
                $stories = uniqid() . time() . '.png';
                $imageFullPath = config('app.upload_it_path') . $stories;
                Storage::disk('s3')->put($imageFullPath, $image_base64);
                $data[] = $stories;
            }
        }

        //four srories images upload
        $data_stories = [];
        if ($request->stories) {

            $image_parts = explode(";base64,", $request->stories);
            foreach ($image_parts as $key => $file) {
                if ($key == 0) {
                    continue;
                }
                $image_base64 = base64_decode($image_parts[$key]);
                $stories = uniqid() . time() . '.png';
                $imageFullPath = config('app.upload_it_path') . $stories;
                Storage::disk('s3')->put($imageFullPath, $image_base64);
                $data_stories[] = $stories;
            }
        }

        //logo upload
        $icon = '';
        if ($request->icon) {
            // $icon = rand(100,100000).'.'.time().'.'.$request->icon->extension();
            $image_parts = explode(";base64,", $request->icon);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $icon = uniqid() . time() . '.png';
            $imageFullPath = config('app.upload_it_path') . $icon;
            Storage::disk('s3')->put($imageFullPath, $image_base64);
            $logoPath = $icon;
        }

        //feature image upload
        $featured = '';
        if ($request->featured_image) {
            // $featured = rand(100,100000).'.'.time().'.'.$request->featured_img->extension();
            $image_parts = explode(";base64,", $request->featured_image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $featured = uniqid() . time() . '.png';
            $imageFullPath = config('app.upload_it_path') . $featured;
            Storage::disk('s3')->put($imageFullPath, $image_base64);
            $featuredPath = $featured;
        }

        //
        $youtube_image_name = '';
        if ($request->youtube_image) {
            $youtube_image_name = rand(100, 100000) . '.' . time() . '.' . $request->youtube_image->extension();
            $youtube_image_path = config('app.upload_it_path') . $youtube_image_name;
            Storage::disk('s3')->put($youtube_image_path, file_get_contents($request->youtube_image));
        }


        //amenties work
        $amenties_ids = $request->amentie_array;
        $now_array = explode(",", $amenties_ids);

        $amentie_save_array = array();

        foreach ($now_array as $ids) {
            $amentie_name = "amentie_icon_" . $ids;
            if (isset($request->$amentie_name)) {
                $amentie_save_array[] = $request->$amentie_name;
            }
        }

        //landmark
        $landmark_ids = $request->landmark_array;
        $land_array = explode(",", $landmark_ids);

        $lanmark_save_array = array();

        foreach ($land_array as $ids) {
            $land_mark_icon = "landmarkicon_" . $ids;
            $landmark_heading = "landmarkheading_" . $ids;
            $landmark_desc = "landmarkdesc_" . $ids;

            if (isset($request->$land_mark_icon)) {
                $array_to_landmark = array(
                    'landmark_icon' => $request->$land_mark_icon,
                    'landmark_heading' => $request->$landmark_heading,
                    'landmark_desc' => $request->$landmark_desc,
                );
                $lanmark_save_array[] = $array_to_landmark;
            }
        }



        DB::beginTransaction();
        try {
            # code...
            $it = new It();
            $it->sub_category_id = $request->sub_category;
            $it->title = $request->title;
            $it->slug = Str::slug($request->title);
            $it->status = $request->status;
            $it->is_featured = $request->is_featured;
            $it->date_time = $request->date_time;
            $it->city_id = $request->city;
            $it->created_by = Auth::user()->id;
            $it->images = json_encode($data);
            $it->feature_image = $featured;
            $it->views = $request->views;
            $it->logo = $icon;
            $it->location = $request->location;
            $it->lat = $request->lat;
            $it->long = $request->long;
            $it->company_name = $request->company_name;
            $it->about_company = $request->about_company;
            $it->company_website = $request->company_website;
            // $it->social_links = $request->social_links;
            $it->reviews = $request->reviews;
            $it->map_review = $request->map_review;
            $it->map_rating = $request->map_rating;
            $it->sub_category_id = $request->sub_category;
            if (isset($request->dynamic_main_category)) {
                $it->dynamic_main_ids = json_encode($request->dynamic_main_category);
            }
            if (isset($request->dynamic_sub_category)) {
                $it->dynamic_sub_ids = json_encode($request->dynamic_sub_category);
            }
            $it->is_popular = $request->is_popular;
            $it->stories = json_encode($data_stories);
            $it->status_text = $request->status_text;

            $it->youtube_img = $request->youtube_img;
            if ($request->youtube_img == 1) {
                $it->video = $request->video;
            } elseif ($request->youtube_img == 2) {
                $it->video = $youtube_image_name;
            }
            $it->amenties = json_encode($amentie_save_array, JSON_UNESCAPED_SLASHES);
            $it->landmarks = json_encode($lanmark_save_array, JSON_UNESCAPED_SLASHES);

            $it->description = $request->description;
            $it->whatsapp = $request->whatsapp;
            $it->email = $request->email;
            $it->mobile = $request->mobile;
            $it->meta_title = $request->meta_title;
            $it->meta_description = $request->meta_description;
            $it->meta_tags = $request->meta_tags;

            $it->save();
            $id = $it->id;

            DB::commit();
            $message = [
                'message' => "IT has been save successfully",
                'alert-type' => 'success',
                'primary_id' => $id
            ];

        } catch (\Throwable $e) {
            # code...
            DB::rollback();
            $message = [
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ];
        }
        return redirect()->back()->with($message);
    }

    public function save_more_info(Request $request)
    {

        $id = $request->primary_id;

        $request->module_id = $id;
        $request->module_name = 'it';
        $request->user_type = 'admin';
        /*dd($request->input());*/
        $res = saveCommoncomponent($request);
        if ($res == false) {
            // DB::rollback();

            $message = [
                'message' => "IT more info has some issues try again.!",
                'alert-type' => 'error',
            ];

            return redirect()->back()->with($message);

        }
        if (isset($request->delitems) && isset($request->delitems[0])) {
            $d = $request->delitems;
            $arr = explode(',', $d[0]);
            $delres = deleteMorinfo($arr);

        }

        $message = [
            'message' => "IT more info has been save successfully",
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
    public function show(It $it)
    {
        $data = It::with('subCategory', 'city')->where('slug', $it->id)->first();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }

        return view('admin.it.preview', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $it = It::find($id);

        if (Auth::user()->user_type != 1 && Auth::id() != $it->created_by) {
            abort(403);
        }
        $more_info = MoreInfo::where(['module_id' => $it->id, 'module_name' => 'it'])->get();
        $four_images = json_decode($it->images);
        $story_four_images = json_decode($it->stories);

        $amenties = Amenties::all();
        $landmarks = Landmark::all();

        $cities = City::all();

        $main_category = MainCategory::where('major_category_id', '=', '15')->get();
        $subcatgories = SubCategory::where('main_category_id', '=', isset($it->sub_category->mainCategory) ? $it->sub_category->mainCategory->id : '')->get();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id', 15)->get();
        $main_category_ids = isset($it->dynamic_main_ids) ? json_decode($it->dynamic_main_ids) : [];
        $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id', $main_category_ids)->get();


        return view('admin.it.edit', get_defined_vars());
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
        $validation = Validator::make($request->all(), [
            'title' => 'required|unique:its,title,' . $id,
            'company_name' => 'required|unique:its,company_name',
            'sub_category' => 'required',
            'city' => 'required',
            'featured_image' => 'required',
        ]);
        // return $request->all();
        if ($validation->fails()) {
            # code...
            $validate = $validation->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];

            return redirect()->back()->with($message);
        }

        //Validation for four images
        if ($request->hasFile('images')) {
            # code...
            $images = $request->file('images');

            if (count($images) > 4) {
                # code...
                $message = [
                    'message' => "images should not be more than 4",
                    'alert-type' => 'error',
                ];

                return redirect()->back()->with($message);
            } elseif (count($images) < 4) {
                # code...
                $message = [
                    'message' => "images should not be less than 4",
                    'alert-type' => 'error',
                ];

                return redirect()->back()->with($message);
            }
        }

        //Four images upload after validation
        $data = [];
        if ($request->images) {
            # code...
            $image_parts = explode(";base64,", $request->images);
            foreach ($image_parts as $key => $file) {
                # code...
                if ($key == 0) {
                    # code...
                    continue;
                }
                $image_base64 = base64_decode($image_parts[$key]);
                $stories = uniqid() . time() . '.png';
                $imageFullPath = config('app.upload_it_path') . $stories;
                Storage::disk('s3')->put($imageFullPath, $image_base64);
                $data[] = $stories;
            }
        }

        //four srories images upload
        $data_stories = [];
        if ($request->stories) {

            $image_parts = explode(";base64,", $request->stories);
            foreach ($image_parts as $key => $file) {
                if ($key == 0) {
                    continue;
                }
                $image_base64 = base64_decode($image_parts[$key]);
                $stories = uniqid() . time() . '.png';
                $imageFullPath = config('app.upload_it_path') . $stories;
                Storage::disk('s3')->put($imageFullPath, $image_base64);
                $data_stories[] = $stories;
            }
        }

        //logo upload
        $icon = '';
        if ($request->icon) {
            // $icon = rand(100,100000).'.'.time().'.'.$request->icon->extension();
            $image_parts = explode(";base64,", $request->icon);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $icon = uniqid() . time() . '.png';
            $imageFullPath = config('app.upload_it_path') . $icon;
            Storage::disk('s3')->put($imageFullPath, $image_base64);
            $logoPath = $icon;
        }

        //feature image upload
        $featured = '';
        if ($request->featured_image) {
            // $featured = rand(100,100000).'.'.time().'.'.$request->featured_img->extension();
            $image_parts = explode(";base64,", $request->featured_image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $featured = uniqid() . time() . '.png';
            $imageFullPath = config('app.upload_it_path') . $featured;
            Storage::disk('s3')->put($imageFullPath, $image_base64);
            $featuredPath = $featured;
        }

        //
        $youtube_image_name = '';
        if ($request->youtube_image) {
            $youtube_image_name = rand(100, 100000) . '.' . time() . '.' . $request->youtube_image->extension();
            $youtube_image_path = config('app.upload_it_path') . $youtube_image_name;
            Storage::disk('s3')->put($youtube_image_path, file_get_contents($request->youtube_image));
        }


        //amenties work
        $amenties_ids = $request->amentie_array;
        $now_array = explode(",", $amenties_ids);

        $amentie_save_array = array();

        foreach ($now_array as $ids) {
            $amentie_name = "amentie_icon_" . $ids;
            if (isset($request->$amentie_name)) {
                $amentie_save_array[] = $request->$amentie_name;
            }
        }

        //landmark
        $landmark_ids = $request->landmark_array;
        $land_array = explode(",", $landmark_ids);

        $lanmark_save_array = array();

        foreach ($land_array as $ids) {
            $land_mark_icon = "landmarkicon_" . $ids;
            $landmark_heading = "landmarkheading_" . $ids;
            $landmark_desc = "landmarkdesc_" . $ids;

            if (isset($request->$land_mark_icon)) {
                $array_to_landmark = array(
                    'landmark_icon' => $request->$land_mark_icon,
                    'landmark_heading' => $request->$landmark_heading,
                    'landmark_desc' => $request->$landmark_desc,
                );
                $lanmark_save_array[] = $array_to_landmark;
            }
        }



        DB::beginTransaction();
        try {
            # code...
            $it = It::find($id);
            $it->sub_category_id = $request->sub_category;
            $it->title = $request->title;
            $it->slug = Str::slug($request->title);
            $it->status = $request->status;
            $it->is_featured = $request->is_featured;
            $it->date_time = $request->date_time;
            $it->city_id = $request->city;
            $it->created_by = Auth::user()->id;
            $it->images = json_encode($data);
            $it->feature_image = $featured;
            $it->views = $request->views;
            $it->logo = $icon;
            $it->location = $request->location;
            $it->lat = $request->lat;
            $it->long = $request->long;
            $it->company_name = $request->company_name;
            $it->about_company = $request->about_company;
            $it->company_website = $request->company_website;
            // $it->social_links = $request->social_links;
            $it->reviews = $request->reviews;
            $it->map_review = $request->map_review;
            $it->map_rating = $request->map_rating;
            $it->sub_category_id = $request->sub_category;
            if (isset($request->dynamic_main_category)) {
                $it->dynamic_main_ids = json_encode($request->dynamic_main_category);
            }
            if (isset($request->dynamic_sub_category)) {
                $it->dynamic_sub_ids = json_encode($request->dynamic_sub_category);
            }
            $it->is_popular = $request->is_popular;
            $it->stories = json_encode($data_stories);
            $it->status_text = $request->status_text;

            $it->youtube_img = $request->youtube_img;
            if ($request->youtube_img == 1) {
                $it->video = $request->video;
            } elseif ($request->youtube_img == 2) {
                $it->video = $youtube_image_name;
            }
            $it->amenties = json_encode($amentie_save_array, JSON_UNESCAPED_SLASHES);
            $it->landmarks = json_encode($lanmark_save_array, JSON_UNESCAPED_SLASHES);

            $it->description = $request->description;
            $it->whatsapp = $request->whatsapp;
            $it->email = $request->email;
            $it->mobile = $request->mobile;
            $it->meta_title = $request->meta_title;
            $it->meta_description = $request->meta_description;
            $it->meta_tags = $request->meta_tags;

            $it->save();
            $id = $it->id;

            DB::commit();
            $message = [
                'message' => "IT has been save successfully",
                'alert-type' => 'success',
                'primary_id' => $id
            ];

        } catch (\Throwable $e) {
            # code...
            DB::rollback();
            $message = [
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function update_status_it()
    {
        # code...
    }
}