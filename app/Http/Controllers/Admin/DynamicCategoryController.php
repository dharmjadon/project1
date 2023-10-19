<?php

namespace App\Http\Controllers\Admin;

use App\Models\MajorCategory;
use Illuminate\Http\Request;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DynamicCategoryController extends Controller
{


    function __construct()
    {
        $this->middleware('role_or_permission:Admin|dynamic-major-category-add', ['only' => ['main_categories','store','create']]);
        $this->middleware('role_or_permission:Admin|dynamic-major-category-view', ['only' => ['major_category']]);
        $this->middleware('role_or_permission:Admin|dynamic-sub-category-add', ['only' => ['sub_categories','store']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = DynamicMainCategory::with('majorCategory')->get();
        return view('admin.dynamic-main-category.index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = MajorCategory::all();
        return view('admin.dynamic-main-category.create',compact('categories'));
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
            'major_category' => 'required',
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

        $obj = new DynamicMainCategory();
        $obj->major_category_id = $request->major_category;
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
        $data = DynamicMainCategory::find($id);
        return view('admin.dynamic-main-category.edit',compact('data'));
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
        $obj = DynamicMainCategory::find($id);
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
        $obj = DynamicMainCategory::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function major_category()
    {
        $datas = MajorCategory::all();

        return view('admin.dynamic-main-category.major',compact('datas'));
    }

    public function main_categories(Request $request ,$id)
    {
        $major = MajorCategory::find($id);
        $mains = DynamicMainCategory::where('major_category_id',$major->id)->get();

        return view('admin.dynamic-main-category.create',compact('major','mains'));
    }

    public function sub_categories(Request $request ,$id)
    {
        $main = DynamicMainCategory::find($id);
        $subs = DynamicSubCategory::where('main_category_id',$main->id)->get();

        return view('admin.dynamic-sub-category.create',compact('main','subs'));
    }

    public function getSubCategories(Request $request)
    {
        $data = DynamicSubCategory::whereIn('main_category_id', $request->id)->get();
        return response()->json($data, 200);
    }

    public function update_status_dynamic(Request $request)
    {
        $obj = DynamicMainCategory::find($request->id);
        $obj->status = $request->status;
        $obj->save();
    }
}
