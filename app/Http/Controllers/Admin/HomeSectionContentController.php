<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSectionContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class HomeSectionContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data = HomeSectionContent::find(1);
        // dd($data);
        return view('admin.home-section-content.create',compact('data'));
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
        if(isset($request->primary_id)){

            $validator = Validator::make($request->all(), [
                'left_title' => 'required',
                'right_title' => 'required',
                'right_description' => 'required',
                'left_description' => 'required',
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
        }else{

            $validator = Validator::make($request->all(), [
                'left_title' => 'required',
                'right_title' => 'required',
                'right_description' => 'required',
                'left_description' => 'required',
                'thumbnail' => 'required',
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


        }

         //feature image upload
         $featuredPath = null;
         if($request->thumbnail) {

            $featuredPath = rand(100,100000).'.'.time().'.'.$request->thumbnail->extension();
            $menuPath = config('app.upload_other_path') . $featuredPath;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->thumbnail));

           //  $file->move(public_path() . '/uploads/book-artist', $name);

         }
         if(isset($request->primary_id)){
            $homesection =  HomeSectionContent::find($request->primary_id);
         }else{
            $homesection = new HomeSectionContent();
         }



         $homesection->left_side_title =  $request->left_title;
         $homesection->left_side_content =  $request->left_description;
         if(isset($featuredPath)){
            $homesection->left_side_image = $featuredPath;
         }

         $homesection->button_label   =  $request->button_label;
         $homesection->button_link  =  $request->button_link;
         $homesection->right_side_title  =  $request->right_title;
         $homesection->right_content =  $request->right_description;
         $homesection->save();


         $message = [
            'message' => 'Home section Content Added Successfully',
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
    public function destroy($id)
    {
        //
    }
}
