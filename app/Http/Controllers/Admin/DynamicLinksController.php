<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\BookArtist;
use App\Models\BuySell;
use App\Models\Concierge;
use App\Models\Directory;
use App\Models\DynamicLink;
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

class DynamicLinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\MajorCategory $majorCategory
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, MajorCategory $majorCategory)
    {
        return view('admin.major-category.dynamic-links.index', get_defined_vars());
    }

    public function showTable(Request $request, MajorCategory $majorCategory)
    {
        if ($request->ajax()) {
            $rows = DynamicLink::where('major_category_id', $majorCategory->id)->latest()->get();
            return DataTables::of($rows)
                ->editColumn('link_image', function ($row) {
                    return  '<img src="'.(!empty($row->link_image) ? otherImage($row->link_image) : '/v2/images/image-placeholder.jpeg').'" class="img-fluid" width=150>';
                })
                ->addColumn('action', function ($row) use($majorCategory) {
                    $btn = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('admin.major-category.dynamic-links.edit', [$majorCategory->id, $row->id]) . '" data-toggle="modal" data-target="#ajaxModal">
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
                ->rawColumns(['link_image', 'action'])
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
        switch($majorCategory->id) {
            case 1:
                $module_products = Venue::active()->pluck('title', 'id')->toArray();
                $popular_list = Venue::active()->popular()->pluck('id')->toArray();
                $trending_list = Venue::active()->trending()->pluck('id')->toArray();
                $hot_list = Venue::active()->hot()->pluck('id')->toArray();
                break;
            case 2:
                $module_products = Events::active()->pluck('title', 'id')->toArray();
                $popular_list = Events::active()->popular()->pluck('id')->toArray();
                $trending_list = Events::active()->trending()->pluck('id')->toArray();
                $hot_list = Events::active()->hot()->pluck('id')->toArray();
                break;
            case 3:
                $module_products = BuySell::active()->pluck('title', 'id')->toArray();
                $popular_list = BuySell::active()->popular()->pluck('id')->toArray();
                $trending_list = BuySell::active()->trending()->pluck('id')->toArray();
                $hot_list = BuySell::active()->hot()->pluck('id')->toArray();
                break;
            case 4:
                $module_products = Directory::active()->pluck('title', 'id')->toArray();
                $popular_list = Directory::active()->popular()->pluck('id')->toArray();
                $trending_list = Directory::active()->trending()->pluck('id')->toArray();
                $hot_list = Directory::active()->hot()->pluck('id')->toArray();
                break;
            case 5:
                $module_products = Concierge::pluck('title', 'id')->toArray();
                break;
            case 6:
                $module_products = Influencer::active()->pluck('title', 'id')->toArray();
                $popular_list = Influencer::active()->popular()->pluck('id')->toArray();
                $trending_list = Influencer::active()->trending()->pluck('id')->toArray();
                $hot_list = Influencer::active()->hot()->pluck('id')->toArray();
                break;
            case 7:
                $module_products = Job::active()->pluck('title', 'id')->toArray();
                $popular_list = Job::active()->popular()->pluck('id')->toArray();
                $trending_list = Job::active()->trending()->pluck('id')->toArray();
                $hot_list = Job::active()->hot()->pluck('id')->toArray();
                break;
            case 8:
                $module_products = Tickets::pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            case 9:
                $module_products = $popular_list = $trending_list = $hot_list = [];
                break;
            case 10:
                $module_products = Attraction::pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            case 11:
                $module_products = BookArtist::pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            case 12:
                $module_products = GiveAway::pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            case 13:
                $module_products = Motors::pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            case 14:
                $module_products = Education::active()->pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            case 15:
                $module_products = It::pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            default:
                $module_products = $popular_list = $trending_list = $hot_list = [];
                break;
        }
        return view('admin.major-category.dynamic-links.create',  get_defined_vars());
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
                'link_title' => 'required',
                'slug' => 'required|unique:dynamic_links,slug',
                'link_image' => 'file|max:1024',
                'related_items' => 'required',
                'meta_title' => 'required',
                'meta_description' => 'required',
                'meta_tags' => 'required',
            ],
            []
        );

        try {
            $validatedData = $request->post();
            //dd($validatedData);
            if($request->hasfile('link_image')) {
                $file = $request->file('link_image');
                $name = rand(100,100000).'.'.time().'.'.$file->extension();
                $imagePath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($imagePath, file_get_contents($file));
                $validatedData['link_image'] = $name;
            }
            //$validatedData['related_items'] = implode(',', $validatedData['related_items']);
            $link = DynamicLink::create($validatedData);
            if($link) {
                $response['error'] = false;
                $response['msg'] = 'Dynamic links created successfully!';
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
     * @param  \App\Models\DynamicLink  $dynamicLink
     * @param  \App\Models\MajorCategory $majorCategory
     * @return \Illuminate\Http\Response
     */
    public function show(MajorCategory $majorCategory, DynamicLink $dynamicLink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DynamicLink  $dynamicLink
     * @param  \App\Models\MajorCategory $majorCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(MajorCategory $majorCategory, DynamicLink $dynamicLink)
    {
        switch($majorCategory->id) {
            case 1:
                $module_products = Venue::active()->pluck('title', 'id')->toArray();
                $popular_list = Venue::active()->popular()->pluck('id')->toArray();
                $trending_list = Venue::active()->trending()->pluck('id')->toArray();
                $hot_list = Venue::active()->hot()->pluck('id')->toArray();
                break;
            case 2:
                $module_products = Events::active()->pluck('title', 'id')->toArray();
                $popular_list = Events::active()->popular()->pluck('id')->toArray();
                $trending_list = Events::active()->trending()->pluck('id')->toArray();
                $hot_list = Events::active()->hot()->pluck('id')->toArray();
                break;
            case 3:
                $module_products = BuySell::active()->pluck('title', 'id')->toArray();
                $popular_list = BuySell::active()->popular()->pluck('id')->toArray();
                $trending_list = BuySell::active()->trending()->pluck('id')->toArray();
                $hot_list = BuySell::active()->hot()->pluck('id')->toArray();
                break;
            case 4:
                $module_products = Directory::active()->pluck('title', 'id')->toArray();
                $popular_list = Directory::active()->popular()->pluck('id')->toArray();
                $trending_list = Directory::active()->trending()->pluck('id')->toArray();
                $hot_list = Directory::active()->hot()->pluck('id')->toArray();
                break;
            case 5:
                $module_products = Concierge::pluck('title', 'id')->toArray();
                break;
            case 6:
                $module_products = Influencer::active()->pluck('title', 'id')->toArray();
                $popular_list = Influencer::active()->popular()->pluck('id')->toArray();
                $trending_list = Influencer::active()->trending()->pluck('id')->toArray();
                $hot_list = Influencer::active()->hot()->pluck('id')->toArray();
                break;
            case 7:
                $module_products = Job::active()->pluck('title', 'id')->toArray();
                $popular_list = Job::active()->popular()->pluck('id')->toArray();
                $trending_list = Job::active()->trending()->pluck('id')->toArray();
                $hot_list = Job::active()->hot()->pluck('id')->toArray();
                break;
            case 8:
                $module_products = Tickets::pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            case 9:
                $module_products = $popular_list = $trending_list = $hot_list = [];
                break;
            case 10:
                $module_products = Attraction::pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            case 11:
                $module_products = BookArtist::pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            case 12:
                $module_products = GiveAway::pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            case 13:
                $module_products = Motors::pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            case 14:
                $module_products = Education::active()->pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            case 15:
                $module_products = It::pluck('title', 'id')->toArray();
                $popular_list = $trending_list = $hot_list = [];
                break;
            default:
                $module_products = $popular_list = $trending_list = $hot_list = [];
                break;
        }
        return view('admin.major-category.dynamic-links.edit',  get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MajorCategory $majorCategory
     * @param  \App\Models\DynamicLink  $dynamicLink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MajorCategory $majorCategory, DynamicLink $dynamicLink)
    {
        $response = [];
        $request->validate(
            [
                'link_title' => 'required',
                'slug' => 'required|'.Rule::unique('dynamic_links')->ignore($dynamicLink),
                'link_image' => 'file|max:1024',
                'related_items' => 'required',
                'meta_title' => 'required',
                'meta_description' => 'required',
                'meta_tags' => 'required',
            ],
            []
        );

        try {
            $validatedData = $request->post();
            //dd($validatedData);
            if($request->hasfile('link_image')) {
                $file = $request->file('link_image');
                $name = rand(100,100000).'.'.time().'.'.$file->extension();
                $imagePath = config('app.upload_other_path') . $name;
                Storage::disk('s3')->put($imagePath, file_get_contents($file));
                $validatedData['link_image'] = $name;
            }
            //$validatedData['related_items'] = implode(',', $validatedData['related_items']);
            if($dynamicLink->update($validatedData)) {
                $response['error'] = false;
                $response['msg'] = 'Dynamic links update successfully!';
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
     * @param  \App\Models\DynamicLink  $dynamicLink
     * @param  \App\Models\MajorCategory $majorCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MajorCategory $majorCategory, DynamicLink $dynamicLink)
    {
        $response = [];
        try {
            if($dynamicLink->delete()){
                $response['error'] = false;
                $response['msg'] = 'Dynamic link deleted successfully!';
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
