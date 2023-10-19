<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DynamicSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = DynamicSubCategory::with('mainCategory')->get();
        return view('admin.dynamic-sub-category.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = DynamicMainCategory::all();
        return view('admin.dynamic-sub-category.create',compact('categories'));
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
            'name' => 'required',
            'main_category' => 'required',
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

        $obj = new DynamicSubCategory();
        $obj->main_category_id = $request->main_category;
        $obj->name = $request->name;
        $obj->save();

        $message = [
            'message' => 'Category Added Successfully',
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
        $data = DynamicSubCategory::find($id);
        return view('admin.dynamic-sub-category.edit',compact('data'));
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
        $obj = DynamicSubCategory::find($id);
        $obj->name = $request->name;
        $obj->save();

        $message = [
            'message' => 'Category Updated Successfully',
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
        $obj = DynamicSubCategory::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
