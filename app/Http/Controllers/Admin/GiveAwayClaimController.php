<?php

namespace App\Http\Controllers\Admin;

use App\Models\GiveAway;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClaimGiveAwayAnswers;
use App\Models\GiveAwayClaim;
use App\Models\MainCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class GiveAwayClaimController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|blog-add', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Admin|blog-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|blog-update', ['only' => ['edit','update']]);
        // $this->middleware('role_or_permission:Admin|applied-candidate-view', ['only' => ['applied_candidate']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = GiveAwayClaim::with(['giveaway'])->get();

        return view('admin.give-away-claim.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $main_category = MainCategory::where('major_category_id',6)->get();

        return view('admin.give-away.create', compact('main_category'));
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
            'title' => 'required|unique:give_aways,title',
            'description' => 'required',
            'image' => 'required'
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

        if($request->hasfile('image'))
        {
           foreach($request->file('image') as $file)
           {

                $name =rand(100,100000).'-'.time().'.'.$file->extension();
                $menuPath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($menuPath, file_get_contents($file));
                $images[] = $name;
           }
        }

          //feature image upload
        //   if(!empty($_FILES['image']['name'])) {

        //     $feature_image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        //     $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
        //     move_uploaded_file($_FILES["image"]["tmp_name"], '../public/uploads/blog/feature_image/' . $feature_image_name);
        //     $feature_image_path = 'uploads/blog/feature_image/' . $feature_image_name;

        $obj = new GiveAway();
        $obj->title = $request->title;
        $obj->content = $request->description;
        $obj->publisher = $request->publisher;
        $obj->main_category_id = $request->main_category_id;

        if(isset($images)){
            $obj->feature_image = json_encode($images);
        }
        $obj->publish_date = $request->publish_date ?? Date('y:m:d', strtotime('+10 days'));
        $obj->slug = Str::slug($request->title, '-');

        $obj->save();
        $message = [
            'message' => 'Give Away Added Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function  save_claim_answer(Request $request){

        // dd($request);

        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'primary_id' => 'required',
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


        if($request->hasfile('image'))
        {
           foreach($request->file('image') as $file)
           {
                $name =rand(100,100000).'-'.time().'.'.$file->extension();
                $menuPath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($menuPath, file_get_contents($file));
                $images[] =  $name;

            //    array_push($images, $destinationPath.$name);
           }
        }



        $obj = new ClaimGiveAwayAnswers();
        $obj->description = $request->description;
        $obj->give_away_claim_id = $request->primary_id;
        if(isset($images)){
        $obj->images = json_encode($images,JSON_UNESCAPED_SLASHES);
        }
        $obj->created_by = Auth::user()->id;
        $obj->save();

        $primary_id = $request->primary_id;

        $claim = GiveAwayClaim::find($primary_id);
        $claim->status = "1";
        $claim->update();

        $message = [
            'message' => 'GiveAway  Claim has been submitted Successfully',
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
        $data = GiveAway::where('slug', $id)->first();
        $main_category = MainCategory::where('major_category_id',6)->get();

        return view('admin.give-away.edit', compact('data','main_category'));
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
            'title' => 'required|unique:give_aways,title,'.$id,
            'description' => 'required',
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

         //feature image upload
        //  if(!empty($_FILES['image']['name'])) {

        //     $feature_image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        //     $feature_image_name = rand(100,100000).''.time() . "_" . $request->date . '.' . $feature_image_extension;
        //     move_uploaded_file($_FILES["image"]["tmp_name"], '../public/uploads/blog/feature_image/' . $feature_image_name);
        //     $feature_image_path = 'uploads/blog/feature_image/' . $feature_image_name;
        // }
        if($request->hasfile('image'))
        {
           foreach($request->file('image') as $file)
           {

                $name =rand(100,100000).'-'.time().'.'.$file->extension();
                $menuPath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($menuPath, file_get_contents($file));
                $images[] = $name;
           }
        }else{
        if($request->old_images) {
            foreach($request->old_images as $image) {
                $images_edit[] = $image;
            }
        }
    }
// dd($images);
        $obj = GiveAway::find($id);
        $obj->title = $request->title;
        $obj->content = $request->description;
        $obj->publisher = $request->publisher;
        $obj->main_category_id = $request->main_category_id;
        // if(isset($feature_image_path)){
        //     $obj->feature_image = $feature_image_path;
        // }

        if(isset($images)){
            $obj->feature_image = json_encode($images);
        }
        else{
            $obj->feature_image = $images_edit;
        }

        $obj->publish_date = $request->publish_date ?? Date('y:m:d', strtotime('+10 days'));
        $obj->slug = Str::slug($request->title, '-');

        $obj->save();
        $message = [
            'message' => 'Give Aways Updated Successfully',
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
        $obj = GiveAwayClaim::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Give Away Claim deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
