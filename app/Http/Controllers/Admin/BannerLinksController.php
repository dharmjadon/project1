<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\BookArtist;
use App\Models\BuySell;
use App\Models\Concierge;
use App\Models\Directory;
use App\Models\BannerLink;
use App\Models\Education;
use App\Models\Events;
use App\Models\GiveAway;
use App\Models\Influencer;
use App\Models\It;
use App\Models\Job;
use App\Models\MajorCategory;
use App\Models\Motors;
use App\Models\Tickets;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class BannerLinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\MajorCategory $majorCategory
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, MajorCategory $majorCategory)
    {
        return view('admin.major-category.banner-links.index', get_defined_vars());
    }

    public function showTable(Request $request, MajorCategory $majorCategory)
    {
        if ($request->ajax()) {
            $rows = BannerLink::where('major_category_id', $majorCategory->id)->latest()->get();
            return DataTables::of($rows)
                ->editColumn('banner_image', function ($row) {
                    return  '<img src="'.(!empty($row->banner_image) ? otherImage($row->banner_image) : '/v2/images/image-placeholder.jpeg').'" class="img-fluid" width=150>';
                })
                ->addColumn('action', function ($row) use($majorCategory) {
                    $btn = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('admin.major-category.banner-links.edit', [$majorCategory->id, $row->id]) . '" data-toggle="modal" data-target="#ajaxModal">
                                            <i data-feather="edit"></i> Edit </a>';
                    if(!Str::startsWith('popular', $row->slug) && !Str::startsWith('trending', $row->slug) && !Str::startsWith('hot', $row->slug)) {
                        $btn .= '<a role="button" class="dropdown-item btn-icon modal-btn" onclick="confirmDelete('.$row->id.')">
                                                                <i data-feather="trash-2"></i> Delete </a>';
                    }

                    $btn .= '</div>
                            </div>';

                    return $btn;
                })
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at, 'Asia/Dubai')->timestamp;
                })
                ->rawColumns(['banner_image', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\MajorCategory $majorCategory
     * @return \Illuminate\Http\Response
     */
    public function create(MajorCategory $majorCategory)
    {
        return view('admin.major-category.banner-links.create',  get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MajorCategory $majorCategory
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MajorCategory $majorCategory)
    {
        $response = [];
        $request->validate(
            [
                'banner_title' => 'required',
                'banner_position' => 'required',
                'banner_text' => 'required',
                'banner_image' => 'required|file|max:1024',
                'url' => 'required',
            ],
            []
        );

        try {
            $validatedData = $request->post();
            //dd($validatedData);
            if($request->hasfile('banner_image')) {
                $file = $request->file('banner_image');
                $name = rand(100,100000).'.'.time().'.'.$file->extension();
                $imagePath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($imagePath, file_get_contents($file));
                $validatedData['banner_image'] = $name;
            }
            //$validatedData['related_items'] = implode(',', $validatedData['related_items']);
            $link = BannerLink::create($validatedData);
            if($link) {
                $response['error'] = false;
                $response['msg'] = 'Banner links created successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while processing your request. Please try later.';
            }
        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = 'There was a problem while processing your request. Please try later.';
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
        }
        return json_encode($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BannerLink  $bannerLink
     * @param  \App\Models\MajorCategory $majorCategory
     * @return \Illuminate\Http\Response
     */
    public function show(MajorCategory $majorCategory, BannerLink $bannerLink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BannerLink  $bannerLink
     * @param  \App\Models\MajorCategory $majorCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(MajorCategory $majorCategory, BannerLink $bannerLink)
    {
        return view('admin.major-category.banner-links.edit',  get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MajorCategory $majorCategory
     * @param  \App\Models\BannerLink  $bannerLink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MajorCategory $majorCategory, BannerLink $bannerLink)
    {
        $response = [];
        $request->validate(
            [
                'banner_title' => 'required',
                'banner_position' => 'required',
                'banner_text' => 'required',
                //'banner_image' => 'required|file|max:1024',
                'url' => 'required',
            ],
            []
        );

        try {
            $validatedData = $request->post();
            //dd($validatedData);
            if($request->hasfile('banner_image')) {
                $file = $request->file('banner_image');
                $name = rand(100,100000).'.'.time().'.'.$file->extension();
                $imagePath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($imagePath, file_get_contents($file));
                $validatedData['banner_image'] = $name;
            }
            //$validatedData['related_items'] = implode(',', $validatedData['related_items']);
            if($bannerLink->update($validatedData)) {
                $response['error'] = false;
                $response['msg'] = 'Banner link update successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while processing your request. Please try later.';
            }
        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = 'There was a problem while processing your request. Please try later.';
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
        }
        return json_encode($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BannerLink  $bannerLink
     * @param  \App\Models\MajorCategory $majorCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MajorCategory $majorCategory, BannerLink $bannerLink)
    {
        $response = [];
        try {
            if($bannerLink->delete()){
                $response['error'] = false;
                $response['msg'] = 'Banner link deleted successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while deleting. Please try later';
            }
        } catch(Exception $ex){
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
            $this->log()->error($response['msg']);
        }
        return json_encode($response);
    }
}
