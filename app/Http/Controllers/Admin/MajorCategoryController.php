<?php

namespace App\Http\Controllers\Admin;

use App\Models\Amenties;
use App\Models\Attraction;
use App\Models\Blog;
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
use App\Models\JobPageContent;
use App\Models\Landmark;
use App\Models\MainCategory;
use App\Models\ModuleStatistic;
use App\Models\Motors;
use App\Models\SearchLink;
use App\Models\SliderImage;
use App\Models\MajorCategory;
use App\Models\Tickets;
use App\Models\Venue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MajorCategoryController extends Controller
{


    function __construct()
    {
        $this->middleware('role_or_permission:Admin|major-category-edit', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Admin|major-category-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|major-category-edit', ['only' => ['edit','update']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = MajorCategory::all();
        return view('admin.major-category.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $amenties = Amenties::orderBy('description')->pluck('description', 'id')->toArray();
        $landmarks = Landmark::orderBy('name')->pluck('name', 'id')->toArray();
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
        $active = 'basic-details';
        $module = MajorCategory::with('banner_images', 'statistics', 'searchLinksTop', 'searchLinksBottom')->find($id);
        $amenities = Amenties::orderBy('description')->pluck('description', 'id')->toArray();
        $landmarks = Landmark::orderBy('name')->pluck('name', 'id')->toArray();
        return view('admin.major-category.edit',  compact('module', 'amenities', 'landmarks', 'active'));
    }

    public function getBasicDetails($id)
    {
        $active = 'basic-details';
        $module = MajorCategory::with('banner_images', 'statistics', 'searchLinksTop', 'searchLinksBottom')->find($id);
        return view('admin.major-category.basic-details',  compact('module',  'active'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  MajorCategory $majorCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MajorCategory $majorCategory)
    {
        $response = [];
        /*$request->validate(
            [
                'name' => 'required|min:1|max:100|'.Rule::unique('major_categories')->ignore($majorCategory),
                'slug' => 'required|'.Rule::unique('venues')->ignore($majorCategory),
            ]
        );*/

        $id = $majorCategory->id;
        try {

            $data = [];
            if($request->hasfile('images')) {
                foreach($request->file('images') as $file) {
                    $name = rand(100,100000).'.'.time().'.'.$file->extension();
                    $imagePath = config('app.upload_other_path') . $name;
                    Storage::disk('s3')->put($imagePath, file_get_contents($file));
                    $data[] = $name;
                }
            }
            if($request->old_images) {
                foreach($request->old_images as $image) {
                    $data[] = $image;
                }
            }

            $obj = MajorCategory::find($id);
            if(isset($data)){
                $obj->images = json_encode($data);
            }
            if(isset($request->video)){
                $obj->video = $request->video;
            }

            $obj->save();

            foreach($request->banner as $key => $value) {

                if(!empty($value['banner_image'])) {
                    $image = '';
                    $image = rand(100,100000).'.'.time().'.'.$value['banner_image']->extension();
                    $imagePath = config('app.upload_other_path') . $image;
                    Storage::disk('s3')->put($imagePath, file_get_contents($value['banner_image']));

                }

                if(isset($value['id'])){
                    $slider = SliderImage::find($value['id']);
                    $slider->url = $value['url'];
                    $slider->description = $value['description'];
                    $slider->heading = $value['heading'];
                    if(isset($value['banner_image'])){
                        $slider->image = $image;
                    }
                    $slider->save();
                }else{
                    $slider = new SliderImage();
                    $slider->major_category_id = $id;
                    if(isset($value['banner_image'])){
                        $slider->image = $image;
                    }

                    $slider->url = $value['url'];
                    $slider->heading = $value['heading'];
                    $slider->description = $value['description'];
                    $slider->save();
                }

            }
            $response['msg'] = 'Category updated successfully!';
            $response['error'] = false;

        } catch (Exception $ex) {
            $response['error'] = true;
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
        }
        return json_encode($response);

    }

    public function getSeoDetails($id)
    {
        $active = 'seo-details';
        $module = MajorCategory::find($id);
        return view('admin.major-category.seo-details',  compact('module', 'active'));
    }
    public function updateSeoDetails(Request $request, $id)
    {
        $response = [];
        $request->validate(
            [
                'meta_title' => 'required',
                'meta_tags' => 'required',
                'meta_description' => 'required',
            ]
        );
        try {
            $majorCategory = MajorCategory::find($id);
            $validatedData = $request->post();
            if ($majorCategory->update($validatedData)) {
                $response['error'] = false;
                $response['msg'] = 'Seo details updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] .= $ex->getMessage();
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
        }
        return json_encode($response);
    }
    public function getBlogsList($id)
    {
        $active = 'seo-details';
        $module = MajorCategory::find($id);
        $blogs_list = Blog::latest()->active()->pluck('title','id')->toArray();
        return view('admin.major-category.blogs-list',  compact('module', 'active', 'blogs_list'));
    }
    public function updateBlogsList(Request $request, $id)
    {
        $response = [];
        $request->validate(
            [
                'blogs_list_heading' => 'required',
                'blogs_list' => 'required|array',
            ]
        );
        try {
            $majorCategory = MajorCategory::find($id);
            $validatedData = $request->post();
            if ($majorCategory->update($validatedData)) {
                $response['error'] = false;
                $response['msg'] = 'Blogs list updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] .= $ex->getMessage();
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
        }
        return json_encode($response);
    }

    public function getAmenitiesLandmarks($id)
    {
        $active = 'amenities-landmarks';
        $module = MajorCategory::find($id);
        $amenities = Amenties::orderBy('description')->pluck('description', 'id')->toArray();
        $landmarks = Landmark::orderBy('name')->pluck('name', 'id')->toArray();
        return view('admin.major-category.amenities-landmarks',  compact('module','amenities', 'landmarks', 'active'));
    }
    public function updateAmenitiesLandmarks(Request $request, $id)
    {
        $response = [];
        try {
            $majorCategory = MajorCategory::find($id);
            $validatedData = $request->post();
            $validatedData['amenities'] = isset($validatedData['amenities']) ? implode(',', $validatedData['amenities']) : '';
            $validatedData['landmarks'] = isset($validatedData['landmarks']) ? implode(',', $validatedData['landmarks']) : '';
            //dd($validatedData);
            if ($majorCategory->update($validatedData)) {
                $response['error'] = false;
                $response['msg'] = 'Amenities and Landmarks updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
        }
        return json_encode($response);
    }


    public function getStatistics($id)
    {
        $active = 'statistics';
        $module = MajorCategory::with('statistics')->find($id);
        return view('admin.major-category.statistics',  compact('module', 'active'));
    }

    public function updateStatistics(Request $request, $id)
    {
        $response = [];
        try {
            $majorCategory = MajorCategory::find($id);
            $validatedData = $request->post();
            $majorCategory->statistics()->delete();
            foreach($validatedData['stat_name'] as $sk => $stats) {
                ModuleStatistic::create([
                    'stat_name' => $validatedData['stat_name'][$sk],
                    'stat_value' => $validatedData['stat_value'][$sk],
                    'major_category_id' => $id
                ]);
            }
            $response['error'] = false;
            $response['msg'] = 'Statistics updated successfully!';

        } catch (Exception $ex) {
            $response['error'] = true;
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
        }
        return json_encode($response);
    }


    public function getSearchLinks($id)
    {
        $active = 'search-links';
        $module = MajorCategory::with('searchLinksTop', 'searchLinksBottom')->find($id);
        return view('admin.major-category.search-links',  compact('module', 'active'));
    }
    public function updateSearchLinks(Request $request, $id)
    {
        $response = [];
        try {
            $majorCategory = MajorCategory::find($id);
            $validatedData = $request->post();
            //dd($validatedData);
            $majorCategory->searchLinks()->delete();
            foreach($validatedData['search_link'] as $stats) {
                if(isset($stats['link_position']) && isset($stats['link_name']) && isset($stats['url'])) {
                    SearchLink::create([
                        'link_position' => $stats['link_position'] ?? 'bottom',
                        'link_name' => $stats['link_name'] ?? '',
                        'url' => $stats['url'] ?? '#',
                        'major_category_id' => $id
                    ]);
                }
            }
            $response['error'] = false;
            $response['msg'] = 'Search links updated successfully!';

        } catch (Exception $ex) {
            $response['error'] = true;
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
        }
        return json_encode($response);
    }

    public function getRegistrationContent($id)
    {
        $active = 'registration-content';
        $module = MajorCategory::find($id);
        return view('admin.major-category.registration-content',  compact('module', 'active'));
    }
    public function updateRegistrationContent(Request $request, $id)
    {
        $response = [];
        $request->validate(
            [
                'register_heading' => 'required',
                'register_summary' => 'required',
                'register_image' => 'file|max:1024',
            ]
        );
        try {
            $majorCategory = MajorCategory::find($id);
            $validatedData = $request->post();
            if ($request->hasFile('register_image')) {
                $main_image = $request->file('register_image');
                $destinationPath = config('app.upload_other_path');
                $filename = time().'-'.$main_image->getClientOriginalName();
                $imageName = $destinationPath . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($main_image), 'public');
                $validatedData['register_image'] = $filename;
            }
            if ($majorCategory->update($validatedData)) {
                $response['error'] = false;
                $response['msg'] = 'Registration content updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = 'There was a problem while updating. Please try later.';
            $response['msg'] .= $ex->getMessage();
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
        }
        return json_encode($response);
    }

    public function getJobPageContent($id)
    {
        $active = 'job-page-content';
        $page_content = JobPageContent::find(1);
        return view('admin.major-category.job-page-content',  compact('page_content', 'active'));
    }
    public function updateJobPageContent(Request $request, $id)
    {
        $response = [];
        $request->validate(
            [
                'jobseeker_heading' => 'required',
                'jobseeker_content' => 'required',
                'jobseeker_image' => 'file|max:1024',
                'recruiter_heading' => 'required',
                'recruiter_content' => 'required',
                'recruiter_image' => 'file|max:1024',
                'resume_heading' => 'required',
                'resume_content' => 'required',
                'resume_image' => 'file|max:1024',
                'interview_heading' => 'required',
                'interview_content' => 'required',
                'interview_image' => 'file|max:1024',
            ]
        );
        try {
            $page_content = JobPageContent::find(1);
            $validatedData = $request->post();
            if ($request->hasFile('jobseeker_image')) {
                $main_image = $request->file('jobseeker_image');
                $destinationPath = config('app.upload_job_path');
                $filename = time().'-'.$main_image->getClientOriginalName();
                $imageName = $destinationPath . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($main_image), 'public');
                $validatedData['jobseeker_image'] = $filename;
            }
            if ($request->hasFile('recruiter_image')) {
                $main_image = $request->file('recruiter_image');
                $destinationPath = config('app.upload_job_path');
                $filename = time().'-'.$main_image->getClientOriginalName();
                $imageName = $destinationPath . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($main_image), 'public');
                $validatedData['recruiter_image'] = $filename;
            }
            if ($request->hasFile('resume_image')) {
                $main_image = $request->file('resume_image');
                $destinationPath = config('app.upload_job_path');
                $filename = time().'-'.$main_image->getClientOriginalName();
                $imageName = $destinationPath . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($main_image), 'public');
                $validatedData['resume_image'] = $filename;
            }
            if ($request->hasFile('interview_image')) {
                $main_image = $request->file('interview_image');
                $destinationPath = config('app.upload_job_path');
                $filename = time().'-'.$main_image->getClientOriginalName();
                $imageName = $destinationPath . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($main_image), 'public');
                $validatedData['interview_image'] = $filename;
            }
            $content_image = [];
            //dd($_FILES);
            $interview_content = $validatedData['interview_content'];
            if ($request->hasFile("interview_content_image_0")) {
                $main_image = $request->file("interview_content_image_0");
                $destinationPath = config('app.upload_job_path');
                $filename = time().'-'.$main_image->getClientOriginalName();
                $imageName = $destinationPath . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($main_image), 'public');
                $interview_content[0]['image'] = $filename;
            }
            if ($request->hasFile("interview_content_image_1")) {
                $main_image = $request->file("interview_content_image_1");
                $destinationPath = config('app.upload_job_path');
                $filename = time().'-'.$main_image->getClientOriginalName();
                $imageName = $destinationPath . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($main_image), 'public');
                $interview_content[1]['image'] = $filename;
            }
            $validatedData['interview_content'] = $interview_content;
            //dd($validatedData['interview_content']);
            //$validatedData['interview_content'] = $this->setSummernoteImages($validatedData, 'interview_content');
            if ($page_content->update($validatedData)) {
                $response['error'] = false;
                $response['msg'] = 'Job page content updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = 'There was a problem while updating. Please try later.';
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
        }
        return json_encode($response);
    }

    public function setSummernoteImages($postData, $ctype) {
        $dom = new \DomDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);

        $dom->loadHtml($postData[$ctype], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $specImages = $dom->getElementsByTagName('img');
        if(!$specImages) {
            return $postData[$ctype];
        }
        foreach($specImages as $k => $img) {
            $data = $img->getAttribute('src');
            $alt_text = time().'-interview-image-'.$k;
            if(!str_starts_with($data, config('app.cloudfront_url'))) {
                list($type, $data) = explode(';', $data);
                list($type, $data) = explode(',', $data);

                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));

                $destinationPath = config('app.upload_job_path');
                $filename = time().'-interview-image-'.$k.'.png';

                $filePath = $destinationPath . $filename;
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
            $img->setAttribute('alt', $alt_text);
        }
        return $dom->saveHTML();
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

    /**
     * Remove the specified Banner from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteMajorCategoryBanner(Request $request)
    {
        $obj = SliderImage::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Banner Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
