<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\AttractionSearchPage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SearchPageAttractionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = AttractionSearchPage::all();
        return view('admin.search-page-attraction.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.search-page-attraction.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        foreach($request->banner as $key => $value) {

            $image = '';
            if($value['banner_image']) {
                $image = rand(100,100000).'.'.time().'.'.$value['banner_image']->extension();
                $menuPath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($menuPath, file_get_contents($value['banner_image']));
            }

            $obj = new AttractionSearchPage();
            $obj->image = $image;
            $obj->heading = $value['heading'];
            $obj->description = $value['description'];
            $obj->url = $value['url'];
            $obj->type = $request->type;
            $obj->save();
        }

        $message = [
            'message' => 'Attraction Page Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data =AttractionSearchPage::find($id);
        return view('admin.search-page-attraction.edit',  compact('data'));
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
        $image = '';
        if($request->banner_image) {
            $image = rand(100,100000).'.'.time().'.'.$request->banner_image->extension();
            $menuPath = config('app.upload_other_path') . $image;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->banner_image));
        }

        $obj = AttractionSearchPage::find($id);
        if($request->banner_image) {
            $obj->image =  $image;
        }
        $obj->heading = $request->heading;
        $obj->description = $request->description;
        $obj->url = $request->url;
        $obj->type = $request->type;
        $obj->save();

        $message = [
            'message' => 'Attraction Page Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
