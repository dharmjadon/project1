<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TagBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TagBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tags = TagBlog::all();

        return view('admin.blog-tag.index',compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('admin.blog-tag.create');
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
            'name' => 'required|unique:blog_categories,name',
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

        if(isset($request->slug)){

            $validator = Validator::make($request->all(), [
                'slug' => 'unique:tag_blogs,slug',
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


        $obj = new TagBlog();
        $obj->name =  $request->name;
        if(isset($request->slug)){
            $obj->slug   = Str::slug($request->slug);
        }else{
            $obj->slug   = Str::slug($request->name);
        }

        $obj->description = $request->description;
        $obj->save();
        $message = [
            'message' => 'Tag Added Successfully',
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

        $tag = TagBlog::find($id);

        return view('admin.blog-tag.edit',compact('tag'));
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

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:blog_categories,name,'.$id,
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

        if(isset($request->slug)){

            $validator = Validator::make($request->all(), [
                'slug' => 'unique:tag_blogs,slug,'.$id,
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


        $obj = TagBlog::find($id);
        $obj->name =  $request->name;
        if(isset($request->slug)){
            $obj->slug   = Str::slug($request->slug);
        }else{
            $obj->slug   = Str::slug($request->name);
        }

        $obj->description = $request->description;
        $obj->save();
        $message = [
            'message' => 'Tag Updated Successfully',
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
    public function destroy($id)
    {
        //
    }

    public function delete(Request $request){



        $obj = TagBlog::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Tag Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);

    }


}
