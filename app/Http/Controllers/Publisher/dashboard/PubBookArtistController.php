<?php

namespace App\Http\Controllers\Publisher\dashboard;

use Helper;
use App\Models\Job;
use App\Models\BookArtist;
use App\Models\City;
use App\Models\Skill;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// use Helper;

class PubBookArtistController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $book_artists = BookArtist::with('category')->where('created_by',Auth::id())->orderby('id', 'desc')->get();
        $auth_user_type = Auth::user()->user_type;

        return view('publisher.book-artists.index', compact('book_artists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::all();
        $array_social_name = Helper::get_social_name();
        $main_category = MainCategory::where('major_category_id', 11)->get();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id', 7)->get();
        return view('publisher.book-artists.create', compact('cities', 'array_social_name', 'main_category', 'dynamic_main_category'));
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
            'name' => 'required|unique:book_artists,name',
            'id_name' => 'required',
            'category' => 'required',
            'city_id' => 'required',
            // 'speciality' => 'required',
            // 'genre' => 'required',
            // 'languages' => 'required',
            // 'social_link_1' => 'required',
            // 'social_name_1' => 'required',
            // 'about_company' => 'required',
            // 'company_employee' => 'required',
            // 'citylat' => 'required',
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





        //landmark
        $socail_array_name = $request->social_name_array;
        $socail_array = explode(",", $socail_array_name);
        $socail_name_save_array = array();
        foreach ($socail_array as $ids) {
            $social_name = "social_name_" . $ids;
            $social_link = "social_link_" . $ids;
            if (isset($request->$social_name)) {
                $array_to_soacial_name = array(
                    'social_name' => $request->$social_name,
                    'social_link' => $request->$social_link,
                );
                $socail_name_save_array[] = $array_to_soacial_name;
            }
        }

        //videos
        // $vidioe_array_name = $request->vidioes_array;
        // $vidioe_array = explode(",", $vidioe_array_name);
        // $vidieo_save_array = array();
        $vidieo_save_array = $request->vidioe_array;

        // foreach ($request->vidioes_array as $ids) {
        //     // $vidioes = "vidioes_" . $ids;
        //     if (isset($ids)) {
        //         $array_to_vidioes = array(
        //             'vidioes' => $ids
        //         );
        //         $vidieo_save_array[] = $array_to_vidioes;
        //     }
        // }

        //audios
        $audio_save_array = $request->audio_array;
        // $audio_array = explode(",", $audio_array_name);
        // $audio_save_array = array();
        // foreach ($audio_array_name as $ids) {
        //     // $audios = "audios_" . $ids;
        //     if (isset($ids)) {
        //         $array_to_audios = array(
        //             'audios' => $ids
        //         );
        //         $audio_save_array[] = $array_to_audios;
        //     }
        // }

        if ($request->hasFile('feature_images')) {
            $feature_name_data = [];
            foreach ($request->file('feature_images') as $file) {
                // $feature_name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                // $file->move(public_path() . '/uploads/book-artist/feature-image', $feature_name);


                $feature_name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $imageFullPath = config('app.upload_book_artist_path') . $feature_name;
                Storage::disk('s3')->put($imageFullPath, file_get_contents($file));
                $feature_name_data[] = $feature_name;


            }
        }

        if ($request->hasFile('images')) {
            $data = [];
            foreach ($request->file('images') as $file) {
                // $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                // $file->move(public_path() . '/uploads/book-artist', $name);



                $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $imageFullPath = config('app.upload_book_artist_path') . $name;
                Storage::disk('s3')->put($imageFullPath, file_get_contents($file));
                // $feature_name_data[] = $feature_name;
                $data[] = $name;
            }
        }

        $profile = '';
        if ($request->profile) {
            // $profile = rand(100, 100000) . '.' . time() . '.' . $request->profile->extension();
            // $request->profile->move(public_path('uploads/book-artist/profile'), $profile);


            $profile = rand(100, 100000) . '.' . time() . '.' . $request->profile->extension();
            $imageFullPath = config('app.upload_book_artist_path') . $profile;
            Storage::disk('s3')->put($imageFullPath, file_get_contents($request->profile));
        }

        $book_artist = new BookArtist();
        $book_artist->category_id = $request->category;
        $book_artist->name = $request->name;
        $book_artist->id_name = $request->id_name;
        $book_artist->speciality = $request->speciality;
        $book_artist->genre = $request->genre;
        $book_artist->languages = $request->languages;
        $book_artist->facebook = $request->facebook;
        $book_artist->instagram = $request->instagram;
        $book_artist->youtube = $request->youtube;
        $book_artist->profile = $profile;
        $book_artist->location = $request->location;
        $book_artist->city_id = $request->city_id;
        $book_artist->lat = $request->citylat;
        $book_artist->long = $request->citylong;
        $book_artist->phone = $request->phone;
        $book_artist->whatsapp = $request->whatsapp;
        $book_artist->email = $request->email;
        $book_artist->website = $request->website;
        if (isset($data)) {
            $book_artist->images = json_encode($data);
        }
        if (isset($feature_name_data)) {
            $book_artist->feature_image = json_encode($feature_name_data);
        }
        $book_artist->social_links = json_encode($socail_name_save_array, JSON_UNESCAPED_SLASHES);
        $book_artist->vidioes = json_encode($vidieo_save_array, JSON_UNESCAPED_SLASHES);
        $book_artist->audios = json_encode($audio_save_array, JSON_UNESCAPED_SLASHES);
        $book_artist->description = $request->description;
        $book_artist->slug = Str::slug($request->id_name . ' ' . $request->name, '-');
        if (isset($request->assign_featured)) {
            $book_artist->featured = $request->assign_featured;
        } else {
            $book_artist->featured = 0;
        }
        $book_artist->created_by = Auth::user()->id;

        $book_artist->meta_img_alt = $request->meta_img_alt;
        $book_artist->meta_img_title = $request->meta_img_title;
        $book_artist->meta_img_description = $request->meta_img_description;
        $book_artist->meta_title = $request->meta_title;
        $book_artist->meta_description = $request->meta_description;
        $book_artist->meta_tags = $request->meta_tags;

        $book_artist->is_draft = $request->is_draft;
        $book_artist->is_publisher = "1";

        $book_artist->save();

        $message = [
            'message' => "Book A Artist has been save successfully",
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
        $book_artist = BookArtist::where('slug', $id)->first();
        if (Auth::user()->user_type != 1 && Auth::id() != $book_artist->created_by) {
            abort(403);
        }
        $cities = City::all();


        $array_social_name  = Helper::get_social_name();

        $vidioes = isset($book_artist->vidioes) ?  json_decode($book_artist->vidioes) : [];

        $audios = isset($book_artist->audios) ?  json_decode($book_artist->audios) : [];

        $socail_links = isset($book_artist->socail_links) ? json_decode($book_artist->socail_links) : [];

        $main_category = MainCategory::where('major_category_id', 11)->get();
        $subcatgories = SubCategory::where('main_category_id', '=', $book_artist->sub_category->mainCategory->id ?? '')->get();

        $dynamic_main_category = DynamicMainCategory::where('major_category_id', 11)->get();
        $main_category_ids = isset($book_artist->dynamic_main_ids) ? json_decode($book_artist->dynamic_main_ids) : [];
        $dynamic_sub_category = DynamicSubCategory::whereIn('main_category_id', $main_category_ids)->get();

        return view('publisher.book-artists.edit', compact(
            'book_artist',
            'cities',
            'array_social_name',
            'vidioes',
            'socail_links',
            'audios',
            'main_category',
            'subcatgories',
            'dynamic_main_category',
            'dynamic_sub_category'
        ));
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
        // dd($request);
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|unique:book_artists,name',
            'id_name' => 'required',
            'category' => 'required',
            'city_id' => 'required',
            // 'speciality' => 'required',
            // 'genre' => 'required',
            // 'languages' => 'required',
            // 'social_link_1' => 'required',
            // 'social_name_1' => 'required',
            // 'about_company' => 'required',
            // 'company_employee' => 'required',
            // 'citylat' => 'required',
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



        //social links
        $socail_array_name = $request->social_name_array;
        $socail_array = explode(",", $socail_array_name);

        $socail_name_save_array = array();

        foreach ($socail_array as $ids) {
            $social_name = "social_name_" . $ids;
            $social_link = "social_link_" . $ids;

            if (isset($request->$social_name)) {
                $array_to_soacial_name = array(
                    'social_name' => $request->$social_name,
                    'social_link' => $request->$social_link,
                );
                $socail_name_save_array[] = $array_to_soacial_name;
            }
        }



        // //videos
        // $vidioe_array_name = $request->vidioes_array;
        // $vidioe_array = explode(",", $vidioe_array_name);
        // $vidieo_save_array = array();

        // foreach ($vidioe_array as $ids) {
        //     $vidioes = "vidioes_" . $ids;
        //     if (isset($request->$vidioes)) {
        //         $array_to_vidioes = array(
        //             'vidioes' => $request->$vidioes
        //         );
        //         $vidieo_save_array[] = $array_to_vidioes;
        //     }
        // }

        // //audios
        // $audio_array_name = $request->audio_array;
        // $audio_array = explode(",", $audio_array_name);
        // $audio_save_array = array();
        // foreach ($audio_array as $ids) {
        //     $audios = "audios_" . $ids;
        //     if (isset($request->$audios)) {
        //         $array_to_audios = array(
        //             'audios' => $request->$audios
        //         );
        //         $audio_save_array[] = $array_to_audios;
        //     }
        // }
        $vidieo_save_array = $request->vidioe_array;
        $audio_save_array = $request->audio_array;

        if ($request->hasFile('feature_images')) {
            $feature_name_data = [];
            foreach ($request->file('feature_images') as $file) {
                // $feature_name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                // $file->move(public_path() . '/uploads/book-artist/feature-image', $feature_name);


                $feature_name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $nameimagePath = config('app.upload_book_artist_path') . $feature_name;
                Storage::disk('s3')->put($nameimagePath, file_get_contents($file));
                $feature_name_data[] = $feature_name;




            }
        }

        if ($request->hasFile('images')) {
            $data = [];
            foreach ($request->file('images') as $file) {

                $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $nameimagePath = config('app.upload_book_artist_path') . $name;
                Storage::disk('s3')->put($nameimagePath, file_get_contents($file));

                $data[] = $name;


            }
        }

        $profile = '';
        if ($request->profile) {
            // $profile = rand(100, 100000) . '.' . time() . '.' . $request->profile->extension();
            // $request->profile->move(public_path('uploads/book-artist/profile'), $profile);


            $profile = rand(100, 100000) . '.' . time() . '.' .  $request->profile->extension();
            $imageFullPath = config('app.upload_book_artist_path') . $profile;
            Storage::disk('s3')->put($imageFullPath, file_get_contents($file));

        }

        $book_artist = BookArtist::find($id);
        $book_artist->category_id = $request->category;
        $book_artist->name = $request->name;
        $book_artist->id_name = $request->id_name;
        $book_artist->speciality = $request->speciality;
        $book_artist->genre = $request->genre;
        $book_artist->languages = $request->languages;
        $book_artist->facebook = $request->facebook;
        $book_artist->instagram = $request->instagram;
        $book_artist->youtube = $request->youtube;
        $book_artist->profile = $profile;
        $book_artist->location = $request->location;
        $book_artist->city_id = $request->city_id;
        $book_artist->lat = $request->citylat;
        $book_artist->long = $request->citylong;
        $book_artist->phone = $request->phone;
        $book_artist->whatsapp = $request->whatsapp;
        $book_artist->email = $request->email;
        $book_artist->website = $request->website;
        if (isset($data)) {
            $book_artist->images = json_encode($data);
        }
        if (isset($feature_name_data)) {
            $book_artist->feature_image = json_encode($feature_name_data);
        }
        $book_artist->social_links = json_encode($socail_name_save_array, JSON_UNESCAPED_SLASHES);
        $book_artist->vidioes = json_encode($vidieo_save_array, JSON_UNESCAPED_SLASHES);
        $book_artist->audios = json_encode($audio_save_array, JSON_UNESCAPED_SLASHES);
        $book_artist->description = $request->description;
        // $book_artist->slug = Str::slug($request->id_name . ' ' . $request->name, '-');
        if (isset($request->assign_featured)) {
            $book_artist->featured = $request->assign_featured;
        } else {
            $book_artist->featured = 0;
        }
        $book_artist->created_by = Auth::user()->id;

        $book_artist->meta_img_alt = $request->meta_img_alt;
        $book_artist->meta_img_title = $request->meta_img_title;
        $book_artist->meta_img_description = $request->meta_img_description;
        $book_artist->meta_title = $request->meta_title;
        $book_artist->meta_description = $request->meta_description;
        $book_artist->meta_tags = $request->meta_tags;

        $book_artist->is_draft = $request->is_draft;
        $book_artist->is_publisher = "1";

        $book_artist->save();


        $message = [
            'message' => "Book An Artist has been update successfully",
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
        $obj = BookArtist::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Book An Artist deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function update_status_book_artist(Request $request)
    {
        $book_artist = BookArtist::find($request->id);
        $book_artist->status = $request->status;
        $book_artist->save();
    }
}
