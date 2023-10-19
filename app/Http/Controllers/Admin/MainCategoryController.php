<?php

namespace App\Http\Controllers\Admin;

use App\Models\MainCategory;
use App\Models\MajorCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MainCategoryController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware( 'role_or_permission:Admin|main-category-view', [ 'only' => [ 'index', 'create', 'store', 'edit', 'update' ] ] );
    //     $this->middleware( 'role_or_permission:Admin|main-category-view', [ 'only' => [ 'index', 'create', 'store', 'edit', 'update' ] ] );
    // }

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|main-category-add', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Admin|main-category-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|main-category-update', ['only' => ['edit', 'update']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $venues = MainCategory::with('majorCategory')->where('major_category_id', 1)->get();
        $events = MainCategory::with('majorCategory')->where('major_category_id', 2)->get();
        $buys_sells = MainCategory::with('majorCategory')->where('major_category_id', 3)->get();
        $directories = MainCategory::with('majorCategory')->where('major_category_id', 4)->get();
        $conciergs = MainCategory::with('majorCategory')->where('major_category_id', 5)->get();
        $influencers = MainCategory::with('majorCategory')->where('major_category_id', 6)->get();
        $jobs = MainCategory::with('majorCategory')->where('major_category_id', 7)->get();
        $tickets = MainCategory::with('majorCategory')->where('major_category_id', 8)->get();
        $spaces = MainCategory::with('majorCategory')->where('major_category_id', 9)->get();
        $attractions = MainCategory::with('majorCategory')->where('major_category_id', 10)->get();
        $book_artists = MainCategory::with('majorCategory')->where('major_category_id', 11)->get();
        $give_away = MainCategory::with('majorCategory')->where('major_category_id', 12)->get();
        $education = MainCategory::with('majorCategory')->where('major_category_id', 14)->get();
        $motors = MainCategory::with('majorCategory')->where('major_category_id', 13)->get();
        $it = MainCategory::with('majorCategory')->where('major_category_id', 15)->get();
        $crypto = MainCategory::with('majorCategory')->where('major_category_id', 16)->get();
        $talent = MainCategory::with('majorCategory')->where('major_category_id', 17)->get();
        return view('admin.main-category.index', get_defined_vars());
        /*return view( 'admin.main-category.index', compact( 'venues', 'events', 'buys_sells', 'directories', 'conciergs', 'influencers', 'jobs', 'tickets', 'spaces', 'attractions', 'book_artists', 'give_away' ) );
         */
    }

    public function main_ajax_tab(Request $request)
    {

        if ($request->ajax()) {

            $datas = MainCategory::with('majorCategory')->where('major_category_id', $request->module_name)->get();

            $view = view('admin.main-category.ajax-tab', compact('datas'))->render();
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
        $categories = MajorCategory::all();
        return view('admin.main-category.create', compact('categories'));
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

        $data = [];
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $file) {
                $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $menuPath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($menuPath, file_get_contents($file));
                $data[] = $name;
            }
        }
        $icon = '';
        $icon = rand(100, 100000) . '.' . time() . '.' . $request->icon->extension();
        $menuPath = config('app.upload_other_path') . $icon;
        Storage::disk('s3')->put($menuPath, file_get_contents($request->icon));

        $obj = new MainCategory();
        $obj->major_category_id = $request->major_category;
        $obj->name = $request->name;
        $obj->slug = Str::slug($request->name);
        $obj->icon = $icon;
        $obj->images = json_encode($data);
        $obj->page_heading = $request->page_heading ?? $request->name;
        $obj->page_title = $request->page_title ?? $request->name. ' - '.config('app.name');
        $obj->meta_description = $request->meta_description ?? null ;
        $obj->meta_keywords = $request->meta_keywords ?? null;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $main = MainCategory::find($id);
        $categories = MajorCategory::all();
        return view('admin.main-category.edit', compact('main', 'categories'));
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

        if (!empty($_FILES['icon']['name'])) {
            $icon = '';
            $icon = rand(100, 100000) . '.' . time() . '.' . $request->icon->extension();
            $menuPath = config('app.upload_other_path') . $icon;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->icon));
        } else {
            $icon = $request->icon2;
        }

        $data = [];
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $file) {
                $name = rand(100, 100000) . '.' . time() . '.' . $file->extension();
                $menuPath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($menuPath, file_get_contents($file));
                $data[] = $name;
            }
        }

        $obj = MainCategory::find($id);
        $obj->major_category_id = $request->major_category;
        $obj->name = $request->name;
        $obj->slug = Str::slug($request->name);
        $obj->icon = $icon;
        $obj->images = json_encode($data);
        $obj->page_heading = $request->page_heading ?? $request->name;
        $obj->page_title = $request->page_title ?? $request->name. ' - '.config('app.name');
        $obj->meta_description = $request->meta_description ?? null ;
        $obj->meta_keywords = $request->meta_keywords ?? null;
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
        $obj = MainCategory::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

}
