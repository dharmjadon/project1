<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\BlogImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\TagBlog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;


class BlogsController extends Controller
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
    public function index(Request $request)
    {
        $categories = BlogCategory::orderBy('name')->pluck('name', 'id');
        $category_id = $request->get('category_id', 'All');
        $is_featured = $request->get('is_featured', 'All');
        $is_popular = $request->get('is_popular', 'All');
        $keyword = $request->get('search');

        if ($request->ajax()) {
            $blogs = Blog::select(['id', 'blog_category_id', 'title', 'is_featured', 'is_popular', 'status', 'created_at', 'updated_at'])
                ->latest();

            if ($category_id !== "All") {
                $blogs->where('blog_category_id', $category_id);
            }

            if ($is_featured !== 'All') {
                $blogs->where('is_featured', $is_featured);
            }

            if ($is_popular !== 'All') {
                $blogs->where('is_popular', $is_popular);
            }
            if ($keyword) {
                $blogs->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('page_heading', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('meta_title', 'LIKE', '%' . $keyword . '%');
                });
            }
            $blogs = $blogs->get();
            return Datatables::of($blogs)
                ->editColumn('created_at', function($data){
                    return Carbon::parse($data->created_at, 'Asia/Dubai')->timestamp;
                })
                ->editColumn('is_featured', function($blog){
                    return '<input type="checkbox" class="featured-status" data-toggle="switch" data-size="small"
                    data-on-color="pink" data-on-text="Yes" data-off-color="default" data-off-text="No"
                    data-id="'.$blog->id.'" value="'.$blog->is_featured.'" '.($blog->is_featured ? "checked" : "").'>';
                })
                ->editColumn('is_popular', function($blog){
                    return '<input type="checkbox" class="popular-status" data-toggle="switch" data-size="small"
                    data-on-color="pink" data-on-text="Yes" data-off-color="default" data-off-text="No"
                    data-id="'.$blog->id.'" value="'.$blog->is_popular.'" '.($blog->is_popular ? "checked" : "").'>';
                })
                ->editColumn('status', function($blog){
                    return '<input type="checkbox" class="blog-status" data-toggle="switch" data-size="small"
                    data-on-color="pink" data-on-text="ON" data-off-color="default" data-off-text="OFF"
                    data-id="'.$blog->id.'" value="'.$blog->status.'" '.($blog->status ? "checked" : "").'>';
                })
                ->addColumn('thumbnail', function($blog) {
                    if($blog->thumbnailImage) {
                        return '<img src="'.$blog->storedImage($blog->thumbnailImage->image, 'thumbnail').'" loading="lazy" class="img-fluid img-responsive" width="150">';
                    }
                    return '<img src="/v2/images/image-placeholder.jpeg" loading="lazy" class="img-fluid img-responsive" width="150">';
                })
                ->addColumn('category', function($blog){
                    return $blog->blogCategory ? $blog->blogCategory->name : 'N/A';
                })
                ->addColumn('action', function($blog){
                    $btn = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('admin.blogs.edit', $blog->id) . '">
                                            <i data-feather="edit"></i> Edit </a>
                                    <a class="dropdown-item btn-icon modal-btn" onclick="confirmDelete('.$blog->id.')" type="button" href="javascript:void(0)">
                                                                <i data-feather="trash-2"></i> Delete </a>
                                </div>
                            </div>';

                    return $btn;

                })
                ->rawColumns(['thumbnail', 'is_popular', 'is_featured', 'status', 'action'])
                ->make(true);
        }
        return view('admin.blogs.index', get_defined_vars());
    }

    public function updateFieldStatus(Request $request)
    {
        $response['error'] = false;
        abort_if(!$request->id, 400);
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $blog = Blog::find($id);
        try {
            abort_if(!$blog, 404);
            if($blog->update([$field => $value])) {
                $response['msg'] = 'Blog updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating blog. Please try later.';
            }

        } catch(Exception $ex){
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = BlogCategory::orderBy('name')->pluck('name', 'id');
        $tags = TagBlog::orderBy('name')->pluck('name', 'id');

        return view('admin.blogs.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'blog_category_id' => 'required',
                'title' => 'required|min:3|max:100|unique:blogs,title',
                'slug' => 'required|unique:blogs,slug',
                'meta_title' => 'required|min:3',
                'meta_keywords' => 'required',
                'meta_description' => 'required',
                'content' => 'required',
                'thumbnail_image' => 'required',
                //'thumbnail' => 'file|mimes:jpg,jpeg,png|dimensions:min_width=600,min_height=450|max:1024',
                //'feature_images' => 'file|mimes:jpg,jpeg,png|dimensions:min_width=800,min_height=475|max:1024',
            ],[
                'thumbnail_image.required' => 'Profile image is required.',
            ]);
            $validatedData = $request->post();
            $validatedData['canonical_url'] = config('app.url') . '/blog-details/'.$validatedData['slug'];
            $validatedData['content'] = $this->setSummernoteImages($validatedData, 'content');
            if(isset($validatedData['tags'])){
                $validatedData['tags'] = implode(',', $validatedData['tags']);
            }
            $blog = Blog::create($validatedData);

            if($blog) {
                if($request->thumbnail_ids) {
                    foreach(explode(",", $request->thumbnail_ids) as $k => $img_id) {
                        $blogImage = BlogImage::find($img_id);
                        if($blogImage) {
                            $blogImage->update([
                                'blog_id' => $blog->id,
                                'image_type' => 'thumbnail',
                                'alt_text_en' => $request->alt_text_en[$img_id] ?? '',
                                'alt_text_ar' => $request->alt_text_ar[$img_id] ?? '',
                                'alt_text_zh' => $request->alt_text_zh[$img_id] ?? '',
                            ]);
                        }
                    }
                }

                if($request->feature_images_ids) {
                    foreach(explode(",", $request->feature_images_ids) as $k => $img_id) {
                        $blogImage = BlogImage::find($img_id);
                        if($blogImage) {
                            $blogImage->update([
                                'blog_id' => $blog->id,
                                'image_type' => 'feature_image',
                                'alt_text_en' => $request->alt_text_en[$img_id] ?? '',
                                'alt_text_ar' => $request->alt_text_ar[$img_id] ?? '',
                                'alt_text_zh' => $request->alt_text_zh[$img_id] ?? '',
                            ]);
                        }
                    }
                }
                $response['error'] = false;
                $response['msg'] = 'Blog "'.$validatedData['title'].'" was created successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'Blog "'.$validatedData['title'].'" was not created';
            }
        }catch(Exception $ex){
            //return redirect('/admin/courses')->with('error', 'The course "'.$validatedData['course_name'].'" was not created');
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return response()->json($response);
    }

    public function setSummernoteImages($postData, $ctype) {
        if($postData[$ctype]) {
            $category_name = BlogCategory::where('id', $postData['blog_category_id'])->value('name');
            $category_name = Str::plural($category_name);

            $dom = new \DomDocument('1.0', 'UTF-8');
            libxml_use_internal_errors(true);

            $dom->loadHtml($postData[$ctype], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $specImages = $dom->getElementsByTagName('img');
            if(!$specImages) {
                return $postData[$ctype];
            }
            foreach($specImages as $k => $img) {
                $data = $img->getAttribute('src');
                $alt_texts[] = $category_name;
                if($ctype === 'specification_details') {
                    $alt_texts[] = $postData['slug'].'-specification-'.$k;
                } else {
                    $alt_texts[] = $postData['slug'].'-description-'.$k;
                }

                if(!str_starts_with($data, config('app.cloudfront_url'))) {
                    list($type, $data) = explode(';', $data);
                    list($type, $data) = explode(',', $data);

                    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));

                    $destinationPath = config('app.UPLOAD_BLOG_PATH');
                    if($ctype === 'specification_details') {
                        $filename = time().'-'.$postData['slug'].'-spec-'.$k.'.png';
                    } else {
                        $filename = time().'-'.$postData['slug'].'-description-'.$k.'.png';
                    }

                    $filePath = $destinationPath . '/' . $filename;
                    Storage::disk('s3')->put($filePath, $data, 'public');

                    $url = config('app.cloudfront_url').$filePath;
                    $img->removeAttribute('src');
                    $img->setAttribute('src',  $url);

                    $img->setAttribute('class',  'img-fluid lazyload');
                    $img->setAttribute('loading',  'lazy');
                } else {
                    if(!$img->hasAttribute('class')) {
                        $img->setAttribute('class',  'img-fluid lazyload');
                    }
                    if(!$img->hasAttribute('loading')) {
                        $img->setAttribute('loading',  'lazy');
                    }
                }
                $img->setAttribute('alt',  implode(', ', $alt_texts));
            }
            return $dom->saveHTML();
        }
        return '';
    }

    public function uploadPhotos(Request $request)
    {
        $photos = [];
        $image_type = $request->image_type ?? 'thumbnail';
        $images = $image_type === 'feature_image' ? $request->feature_images : $request->thumbnail;
        foreach ($images as $key => $photo) {
            $filename = time().'-'.$photo->getClientOriginalName();
            if($photo->getSize() < (1024*1024)){
                $destinationPath = config('app.upload_blog_path').$image_type;
                $imageName = $destinationPath . '/' . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($photo), 'public');

                $blog_photo = BlogImage::create([
                    'image_type' => $image_type,
                    'image' => $filename
                ]);

                $photo_object = new \stdClass();
                $photo_object->name = $filename;
                $photo_object->path = config('app.cloudfront_url').$imageName;
                //$photo_object->size = $size;
                $photo_object->fileID = $blog_photo->id;
                $photos[] = $photo_object;
            } else {
                $photo_object = new \stdClass();
                $photo_object->name = $photo->getClientOriginalName();
                $photo_object->path = '';
                $photo_object->fileID = '';
                $photos[] = $photo_object;
            }
        }


        return response()->json(array('files' => $photos), 200);

    }

    public function deletePhoto(Request $request)
    {
        $response = [];
        try {
            abort_if(!$request->id, 404);
            $image = BlogImage::findOrFail($request->id);
            $tmp_obj = $image;
            if($image->delete()) {
                if(file_exists(public_path($tmp_obj->image))){
                    @unlink(public_path($tmp_obj->image));
                }
                $destinationPath = config('app.upload_blog_path').'/'.$tmp_obj->image_type;
                $imageName = $destinationPath . '/' . $tmp_obj->image;
                if(config('app.env') === 'production') {
                    Storage::disk('s3')->delete($imageName);
                }
                $response['error'] = false;
                $response['type'] = $tmp_obj->image_type;
                $response['msg'] = 'Image deleted successfully!';
            } else {
                $response['error'] = false;
                $response['msg'] = 'There was a problem while deleting image. Please try later.';
            }

        } catch(Exception $ex){
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return json_encode($response);
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
    public function edit(Blog $blog)
    {
        $categories = BlogCategory::orderBy('name')->pluck('name', 'id');
        $tags = TagBlog::orderBy('name')->pluck('name', 'id');
        return view('admin.blogs.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog $blog
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Blog $blog)
    {
        try {
            $id = $blog->id;
            $request->validate([
                'blog_category_id' => 'required',
                'title' => 'required|min:1|max:100|'.Rule::unique('blogs')->ignore($blog),
                'slug' => 'required|unique:blogs,slug,'.$id,
                'meta_title' => 'required|min:3',
                'meta_keywords' => 'required',
                'meta_description' => 'required',
                'content' => 'required',
                'thumbnail_image' => 'required',
            ],
            [
                'thumbnail_image.required' => 'Profile image is required.',
            ]);
            $validatedData = $request->post();

            $validatedData['canonical_url'] = config('app.url') . '/blog-details/'.$validatedData['slug'];

            $validatedData['content'] = $this->setSummernoteImages($validatedData, 'content');

            if($blog->update($validatedData)) {
                if($request->thumbnail_ids) {
                    foreach(explode(",", $request->thumbnail_ids) as $k => $img_id) {
                        $blogImage = BlogImage::find($img_id);
                        if($blogImage) {
                            $blogImage->update([
                                'blog_id' => $blog->id,
                                'image_type' => 'thumbnail',
                                'alt_text_en' => $request->alt_text_en[$img_id] ?? '',
                                'alt_text_ar' => $request->alt_text_ar[$img_id] ?? '',
                                'alt_text_zh' => $request->alt_text_zh[$img_id] ?? '',
                            ]);
                        }
                    }
                }

                if($request->feature_images_ids) {
                    foreach(explode(",", $request->feature_images_ids) as $k => $img_id) {
                        $blogImage = BlogImage::find($img_id);
                        if($blogImage) {
                            $blogImage->update([
                                    'blog_id' => $blog->id,
                                    'image_type' => 'feature_image',
                                    'alt_text_en' => $request->alt_text_en[$img_id] ?? '',
                                    'alt_text_ar' => $request->alt_text_ar[$img_id] ?? '',
                                    'alt_text_zh' => $request->alt_text_zh[$img_id] ?? '',
                                ]);
                        }
                    }
                }
                $response['error'] = false;
                $response['msg'] = 'Blog "'.$validatedData['title'].'" was updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'Blog "'.$validatedData['title'].'" was not updated';
            }
        }catch(Exception $ex){
            //return redirect('/admin/courses')->with('error', 'The course "'.$validatedData['course_name'].'" was not created');
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return response()->json($response);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog $blog
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Blog $blog)
    {
        $response = [];
        try {
            abort_if(!$blog, 404);
            if($blog->status == '0'){
                if($blog->delete()) {
                    $blogImages = BlogImage::where('blog_id', $blog->id)->get();
                    foreach($blogImages as $blogImage) {
                        if(file_exists(public_path($blogImage->image))){
                            @unlink(public_path($blogImage->image));
                        }
                        if(config('app.env') === 'production') {
                            $destinationPath = config('app.upload_blog_path').'/'.$blogImage->image_type;
                            $imageName = $destinationPath . '/' . $blogImage->image;
                            Storage::disk('s3')->delete($imageName);
                        }
                        $blogImage->delete();
                    }
                    $response['error'] = false;
                    $response['msg'] = 'Blog and related images deleted successfully!';
                } else {
                    $response['error'] = false;
                    $response['msg'] = 'There was a problem while deleting blog. Please try later.';
                }
            } else {
                $response['error'] = true;
                $response['msg'] = 'Cannot delete active blogs.';
            }

        } catch(Exception $ex){
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return json_encode($response);
    }
}
