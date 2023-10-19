<?php

namespace App\Http\Controllers\Admin;

use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|sub-category-add', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Admin|sub-category-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|sub-category-update', ['only' => ['edit', 'update']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $datas = SubCategory::with('mainCategory')->get();
        return view('admin.sub-category.index', compact('datas'));
    }

    public function sub_category_ajax_tab(Request $request)
    {

        if ($request->ajax()) {

            $sub_category = SubCategory::with('mainCategory')->get();

            $datas = $sub_category->where('mainCategory.major_category_id', '=', $request->module_name);

            $view = view('admin.sub-category.sub-category-ajax-tab', compact('datas'))->render();
            return response()->json(['html' => $view]);

        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {

        $categories = MainCategory::all();
        return view('admin.sub-category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

        $icon = '';
        $icon = rand(100, 100000) . '.' . time() . '.' . $request->icon->extension();
        $menuPath = config('app.upload_other_path') . $icon;
        Storage::disk('s3')->put($menuPath, file_get_contents($request->icon));

        if (!empty($_FILES['image']['name'])) {

            $feature_image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $feature_image_name = rand(100, 100000) . '' . time() . '_' . $request->date . '.' . $feature_image_extension;
            $menuPath = config('app.upload_other_path') . $feature_image_name;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->image));
            $feature_image_path = $feature_image_name;
        }

        $obj = new SubCategory();
        $obj->main_category_id = $request->main_category;
        $obj->name = $request->name;
        $obj->slug = Str::slug($request->name);
        $obj->icon = $icon;
        if (isset($feature_image_path)) {
            $obj->image = $feature_image_path;
        }
        $obj->page_heading = $request->page_heading ?? $request->name;
        $obj->page_title = $request->page_title ?? $request->name . ' - ' . config('app.name');
        $obj->meta_description = $request->meta_description ?? null;
        $obj->meta_keywords = $request->meta_keywords ?? null;
        $obj->is_top_search = $request->is_top_search ?? 0;
        $obj->is_popular_search = $request->is_popular_search ?? 0;
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        //
        $datas = SubCategory::find($id);
        $categories = MainCategory::all();
        return view('admin.sub-category.edit', compact('categories', 'datas'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {

        if (!empty($_FILES['icon']['name'])) {
            $icon = '';
            $icon = rand(100, 100000) . '.' . time() . '.' . $request->icon->extension();
            $menuPath = config('app.upload_other_path') . $icon;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->icon));
        } else {
            $icon = $request->icon2;
        }

        if (!empty($_FILES['image']['name'])) {

            $feature_image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $feature_image_name = rand(100, 100000) . '' . time() . '_' . $request->date . '.' . $feature_image_extension;
            $menuPath = config('app.upload_other_path') . $feature_image_name;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->image));
            $feature_image_path = $feature_image_name;
        } else {
            $feature_image_path = $request->image2;
        }

        $obj = SubCategory::find($id);
        $obj->main_category_id = $request->main_category;
        $obj->name = $request->name;
        $obj->slug = Str::slug($request->name);
        $obj->icon = $icon;
        if (isset($feature_image_path)) {
            $obj->image = $feature_image_path;
        }
        $obj->page_heading = $request->page_heading ?? $request->name;
        $obj->page_title = $request->page_title ?? $request->name. ' - '.config('app.name');
        $obj->meta_description = $request->meta_description ?? null ;
        $obj->meta_keywords = $request->meta_keywords ?? null;
        $obj->is_top_search = $request->is_top_search ?? 0;
        $obj->is_popular_search = $request->is_popular_search ?? 0;
        $obj->save();
        $message = [
            'message' => 'Sub Category Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {
        $obj = SubCategory::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function getSubCategories(Request $request)
    {
        $data = SubCategory::where('main_category_id', $request->id)->get();
        return response()->json($data, 200);
    }
}
