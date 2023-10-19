<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        if ($request->ajax()) {
            $data = BlogCategory::withCount(['blogs']);
            if ($keyword) {
                $data->where(function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('page_heading', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('meta_title', 'LIKE', '%' . $keyword . '%');
                });
                //"select filed_list from tbale where condition1 and condtion2 and (condition3 or condtion4 or condigiton5) and condition6 orderby column limit 20"
            }
            $data = $data->latest()->get();
            return DataTables::of($data)
                ->addColumn('action', function ($blog) {
                    $btn = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('admin.blog-category.edit', $blog->id) . '">
                                            <i data-feather="edit"></i> Edit </a>
                                    <a class="dropdown-item btn-icon modal-btn" onclick="confirmDelete('.$blog->id.')" type="button" href="javascript:void(0)">
                                                                <i data-feather="trash-2"></i> Delete </a>
                                </div>
                            </div>';

                    return $btn;
                })
                ->editColumn('created_at', function($data){
                    return Carbon::parse($data->created_at, 'Asia/Dubai')->timestamp;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.blog-category.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.blog-category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try{
            $this->validate($request, [
                'name' => 'required',
                'slug' => 'required|unique:blog_categories,slug',
                'meta_title' => 'required',
                'page_heading' => 'required',
                'meta_keywords' => 'required',
                'meta_description' => 'required',
            ]);


            $validatedData = $request->post();
            //$validatedData['canonical_url'] = config('app.url').'/'.$validatedData['slug'];
            $category = BlogCategory::create($validatedData);
            if($category){
                $response['error'] = false;
                $response['msg'] = 'The category "'.$validatedData['name'].'" was created successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'The category "'.$validatedData['name'].'" was not created.';
            }

        }catch(Exception $ex){
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
            $this->log()->error($response['msg']);
        }
        return json_encode($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return \Illuminate\Http\Response
     */
    public function show(BlogCategory $blogCategory)
    {
        //return view('admin.blog-category.edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(BlogCategory $blogCategory)
    {
        return view('admin.blog-category.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, BlogCategory $blogCategory)
    {
        try {

            //$category = Category::findOrFail($id);
            $this->validate($request, [
                'name' => 'required',
                'slug' => 'required|'.Rule::unique('blog_categories')->ignore($blogCategory),
                'meta_title' => 'required',
                'page_heading' => 'required',
                'meta_keywords' => 'required',
                'meta_description' => 'required',
            ]);
            $validatedData = $request->post();
            //$validatedData['canonical_url'] = config('app.url').'/'.$validatedData['slug'];
            if($blogCategory->update($validatedData)){
                $response['error'] = false;
                $response['msg'] = 'The category "'.$validatedData['name'].'" was updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'The category "'.$validatedData['name'].'" was not updated.';
            }

        }catch(Exception $ex){
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
            $this->log()->error($response['msg']);
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(BlogCategory $blogCategory)
    {
        $response = [];
        try {
            if(auth()->user()->can('category-delete')){
                $id = $blogCategory->id;
                $hasBlogs = $blogCategory->blogs()->count();
                if($hasBlogs){
                    $response['error'] = true;
                    $response['msg'] = 'The category is associated with one or more Blogs.';
                } else{
                    if($blogCategory->delete()){
                        $response['error'] = false;
                        $response['msg'] = 'Category "'.$blogCategory->name.'" deleted successfully!';
                    } else {
                        $response['error'] = true;
                        $response['msg'] = 'There was a problem while deleting. Please try later';
                    }
                }
            } else {
                $response['error'] = true;
                $response['msg'] = 'You do not have permission to perform this action.';
            }
        } catch(Exception $ex){
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
            $this->log()->error($response['msg']);
        }
        return response()->json($response);
    }
}
