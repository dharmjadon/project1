<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Models\JobCompany;
use App\Models\WishListDetails;
use Helper;
use Illuminate\Validation\Rule;
use Image;
use DB;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Job;
use App\Models\City;
use App\Models\Skill;
use App\Models\State;
use App\Models\Venue;
use App\Models\Career;
use App\Models\Events;
use App\Models\Motors;
use App\Events\MyEvent;
use App\Models\BuySell;
use App\Models\JobImage;
use App\Models\JobUsers;
use App\Models\Concierge;
use App\Models\Directory;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\Accommodation;
use App\Models\NotificationsInfo;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PubJobsController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|publisher-user-jobs', [
            'only' =>
            ['index', 'applied_candidate', 'edit', 'update', 'destroy']
        ]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $main_category = MainCategory::where('major_category_id', 7)->orderBy('name')->pluck('name', 'id');

        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');
        if ($request->ajax()) {
            $jobs = Job::with('sub_category.mainCategory')->orderby('id', 'desc');
            $jobs = $jobs->where('created_by', Auth::id());
            if ($main_categories) { // && empty($keyword)
                $jobs->orWhereHas('sub_category.mainCategory', function ($q) use ($main_categories) {
                    $q->whereIn('id', $main_categories);
                });
            }
            // Global search function
            if ($keyword) {
                $jobs->where(function ($query) use ($keyword) {
                    $query->where('jobs.job_title', 'LIKE', '%' . $keyword . '%') /*->orWhere('jobs.email', 'LIKE', '%' . $keyword . '%')
                           ->orWhere('jobs.description', 'LIKE', '%' . $keyword . '%')*/
                    ;
                });
                //"select filed_list from tbale where condition1 and condtion2 and (condition3 or condtion4 or condigiton5) and condition6 orderby column limit 20"
            }
            if (!empty($date_from) && empty($keyword)) {
                $jobs->where('jobs.created_at', '>', $date_from . " 00:00:00");
            }

            if (!empty($date_from) && empty($keyword)) {
                $jobs->where('jobs.created_at', '<=', $date_to . " 23:59:59");
            }
            //dd($jobs->toSql());
            $jobs = $jobs->latest()->get();
            return DataTables::of($jobs)
                ->addColumn('company', function ($job) {
                    return isset($job->jobCompany) ? $job->jobCompany->company_name : '';
                })
                ->addColumn('main_category', function ($job) {
                    return isset($job->sub_category->mainCategory) ? $job->sub_category->mainCategory->name : '';
                })
                ->addColumn('applied_candidate', function ($job) {
                    return isset($job->applied_candidate) ? $job->applied_candidate->count() : '0';
                })
                ->addColumn('action', function ($job) {
                    $btn = '<div class="btn-group" role="group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('publisher.job.edit', $job->id) . '">
                                        <i data-feather="edit"></i> Edit </a>
                                <a class="dropdown-item btn-icon modal-btn" data-id="' . $job->id . '" data-target="#danger" data-toggle="modal" type="button" href="javascript:void(0)">
                                  <i data-feather="trash-2"></i> Delete </a>
                            </div>
                        </div>';
                    return $btn;

                })
                ->rawColumns(['active', 'action'])
                ->make(true);
        }

        return view('publisher.job.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $companies = JobCompany::where('created_by', Auth::id())->orderBy('company_name', 'asc')->pluck('company_name', 'id')->toArray();
        $array_social_name = Helper::get_social_name();
        $main_category = MainCategory::where('major_category_id', 7)->orderBy('name')->pluck('name', 'id')->toArray();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id', 7)->get();
        return view('publisher.job.create', compact('companies', 'array_social_name', 'main_category', 'dynamic_main_category'));
    }

    public function career_list()
    {

        $id = Auth::user()->id;
        $space = Accommodation::where('created_by', $id)->pluck('id')->toArray();
        $motor = Motors::where('created_by', $id)->pluck('id')->toArray();

        $venue = Venue::where('created_by', $id)->pluck('id')->toArray();
        $conciger = Concierge::where('created_by', $id)->pluck('id')->toArray();
        $directory = Directory::where('created_by', $id)->pluck('id')->toArray();
        $event = Events::where('created_by', $id)->pluck('id')->toArray();
        $buysell = BuySell::where('created_by', $id)->pluck('id')->toArray();

        $space_data = Career::with('spaceModule')->whereIn('module_id', $space)->where('module_type', 'space')
            ->orderBy('id', 'DESC')->get();

        $motor_data = Career::with('motorsModule')->whereIn('module_id', $motor)->where('module_type', 'motor')
            ->orderBy('id', 'DESC')->get();

        $venue_data = Career::with('venueModule')->whereIn('module_id', $venue)->where('module_type', 'venue')
            ->orderBy('id', 'DESC')->get();
        $conciger_data = Career::with('conciergeModule')->whereIn('module_id', $conciger)->where('module_type', 'concierge')
            ->orderBy('id', 'DESC')->get();
        $directory_data = Career::with('directoryModule')->whereIn('module_id', $directory)->where('module_type', 'directory')
            ->orderBy('id', 'DESC')->get();
        $event_data = Career::with('eventModule')->whereIn('module_id', $event)->where('module_type', 'event')
            ->orderBy('id', 'DESC')->get();
        $buysell_data = Career::with('buysellModule')->whereIn('module_id', $buysell)->where('module_type', 'buysell')
            ->orderBy('id', 'DESC')->get();

        return view('publisher.career.index', get_defined_vars());

    }

    public function career_list_ajax_tabs_table(Request $request)
    {

        $id = Auth::user()->id;
        $type = $request->module_name;
        if ($request->module_name == 'venue') {

            $venue = Venue::where('created_by', $id)->pluck('id')->toArray();

            $data = Career::with('venueModule')->whereIn('module_id', $venue)->where('module_type', 'venue')
                ->orderBy('id', 'DESC')->get();

            $view = view('publisher.career.career-list-ajax-tab-table', compact('data', 'type'))->render();
            return response()->json(['html' => $view]);

        } elseif ($request->module_name == 'event') {

            $event = Events::where('created_by', $id)->pluck('id')->toArray();
            $data = Career::with('eventModule')->whereIn('module_id', $event)->where('module_type', 'event')
                ->orderBy('id', 'DESC')->get();

            $view = view('publisher.career.career-list-ajax-tab-table', compact('data', 'type'))->render();
            return response()->json(['html' => $view]);

        } elseif ($request->module_name == 'buysell') {

            $buysell = BuySell::where('created_by', $id)->pluck('id')->toArray();

            $data = Career::with('buysellModule')->whereIn('module_id', $buysell)->where('module_type', 'buysell')
                ->orderBy('id', 'DESC')->get();

            $view = view('publisher.career.career-list-ajax-tab-table', compact('data', 'type'))->render();
            return response()->json(['html' => $view]);
        } elseif ($request->module_name == 'directory') {

            $directory = Directory::where('created_by', $id)->pluck('id')->toArray();

            $data = Career::with('directoryModule')->whereIn('module_id', $directory)->where('module_type', 'directory')
                ->orderBy('id', 'DESC')->get();

            $view = view('publisher.career.career-list-ajax-tab-table', compact('data', 'type'))->render();
            return response()->json(['html' => $view]);
        } elseif ($request->module_name == 'concierge') {

            $conciger = Concierge::where('created_by', $id)->pluck('id')->toArray();
            $data = Career::with('conciergeModule')->whereIn('module_id', $conciger)->where('module_type', 'concierge')
                ->orderBy('id', 'DESC')->get();

            $view = view('publisher.career.career-list-ajax-tab-table', compact('data', 'type'))->render();
            return response()->json(['html' => $view]);
        } elseif ($request->module_name == 'accomdation') {

            $space = Accommodation::where('created_by', $id)->pluck('id')->toArray();
            $data = Career::with('spaceModule')->whereIn('module_id', $space)->where('module_type', 'space')
                ->orderBy('id', 'DESC')->get();

            $view = view('publisher.career.career-list-ajax-tab-table', compact('data', 'type'))->render();
            return response()->json(['html' => $view]);

        } elseif ($request->module_name == 'motor') {

            $motor = Motors::where('created_by', $id)->where('created_by', $id)->pluck('id')->toArray();
            $data = Career::with('motorsModule')->whereIn('module_id', $motor)->where('module_type', 'motor')
                ->orderBy('id', 'DESC')->get();

            $view = view('publisher.career.career-list-ajax-tab-table', compact('data', 'type'))->render();
            return response()->json(['html' => $view]);

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $request->validate(
            [
                'job_company_id' => 'required',
                'sub_category_id' => 'required',
                'slug' => 'required|unique:jobs,slug',
                'location' => 'required',
                'job_title' => 'required|unique:jobs,job_title',
                'job_type' => 'required',
                'last_date_to_apply' => 'required',
            ],
            [
                'sub_category_id.required' => 'Sub category is required.',
            ]
        );

        try {

            $validatedData = $request->post();

            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['long'] = $request->citylong;
            //$validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            //$validatedData['contact'] = $validatedData['full_phone_contact'] ?? $validatedData['contact'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            //$validatedData['canonical_url'] = route('job-details', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['featured'] = $validatedData['featured'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;

            $validatedData['responsibility'] = json_encode($request->responsibility);
            $validatedData['benefit'] = json_encode($request->benefit);
            //dd($validatedData);
            $validatedData['is_publisher'] = '1';

            $job = Job::create($validatedData);
            if ($job) {
                $id = $job->id;
                if ($request->logo_ids) {
                    $this->updateJobImages($request, $id, $request->logo_ids);
                }
                if ($request->main_image_ids) {
                    $this->updateJobImages($request, $id, $request->main_image_ids);
                }
                $user = Auth::user();

                if ($user->hasRole('publisher-user-jobs')) {

                } else {
                    $user->assignRole('publisher-user-jobs');
                }

                //sending notification to admin
                if ($request->is_draft == '0') {

                    $description_event = 'Submitted by' . ' ' . Auth::user()->name;
                    $message_event = 'Publisher submitted a new job';
                    $url_now = route('admin.job.edit', $job->slug);

                    event(new MyEvent($message_event, $description_event, $url_now, '0'));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_event;
                    $notification->description = $description_event;
                    $notification->notification_for = 0;
                    $notification->url = $url_now;
                    $notification->save();

                }
                $response['error'] = false;
                $response['msg'] = 'Job "' . $validatedData['job_title'] . '" was created successfully!';
                $response['primary_id'] = $job->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add job. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding event. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
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
        $job = Job::where('id', $id)->first();
        $job->load('logoImage', 'mainImage', 'storyImages');
        if (Auth::user()->user_type != 1 && Auth::id() != $job->created_by) {
            abort(403);
        }
        $companies = JobCompany::where('created_by', Auth::id())->orderBy('company_name', 'asc')->pluck('company_name', 'id')->toArray();

        $responsibilty = isset($job->responsibility) ? json_decode($job->responsibility) : [];
        $benefits = isset($job->benefit) ? json_decode($job->benefit) : [];

        $main_category = MainCategory::where('major_category_id', 7)->orderBy('name')->pluck('name', 'id')->toArray();;
        $subcatgories = SubCategory::where('main_category_id', '=', isset($job->sub_category->mainCategory) ? $job->sub_category->mainCategory->id : '')->orderBy('name')->pluck('name', 'id')->toArray();

        $mainImage = $job->mainImage;
        return view('publisher.job.edit', get_defined_vars());

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
        $job = Job::find($id);
        $request->validate(
            [
                'job_company_id' => 'required',
                'main_category' => 'required',
                'sub_category_id' => 'required',
                'job_title' => 'required|min:1|max:100|'.Rule::unique('jobs')->ignore($job),
                'slug' => 'required|'.Rule::unique('jobs')->ignore($job),
                'location' => 'required',
                'job_type' => 'required',
                'last_date_to_apply' => 'required',
            ],
            [
                'sub_category_id.required' => 'Sub category is required.',
            ]
        );

        try {

            $validatedData = $request->post();
            $validatedData['lat'] = $request->citylat;
            $validatedData['long'] = $request->citylong;
            //$validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            //$validatedData['contact'] = $validatedData['full_phone_contact'] ?? $validatedData['contact'];
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['canonical_url'] = route('job-details', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['featured'] = $validatedData['assign_featured'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;


            $validatedData['responsibility'] = json_encode($request->responsibility);
            $validatedData['benefit'] = json_encode($request->benefit);
            $validatedData['is_publisher'] = '1';
            if ($job->update($validatedData)) {
                $id = $job->id;
                if ($request->logo_ids) {
                    $this->updateJobImages($request, $id, $request->logo_ids);
                }
                if ($request->main_image_ids) {
                    $this->updateJobImages($request, $id, $request->main_image_ids);
                }

                $request->module_id = $id;
                $request->module_name = 'job';
                $request->user_type = 'admin';

                //sending notification to admin
                if ($job->is_draft == '1' && $request->is_draft == '0') {

                    $description_event = 'Submitted by' . ' ' . Auth::user()->name;
                    $message_event = 'Publisher submitted a new job';
                    $url_now = route('admin.job.edit', $job->slug);

                    event(new MyEvent($message_event, $description_event, $url_now, '0'));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_event;
                    $notification->description = $description_event;
                    $notification->notification_for = 0;
                    $notification->url = $url_now;
                    $notification->save();

                }

                $response['error'] = false;
                $response['msg'] = 'Job "' . $validatedData['job_title'] . '" was updated successfully!';
                $response['primary_id'] = $job->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to update job. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            $response['error'] = true;
            $response['msg'] = 'There was a problem while updating event. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);

    }


    public function uploadPhotos(Request $request)
    {
        $photos = [];
        $image_type = $request->image_type;
        $images = $request->{$image_type};
        $feature_image_ids = isset($request->feature_image_ids) ? explode(', ', $request->feature_image_ids) : [];
        $logo_ids = isset($request->logo_ids) ? explode(', ', $request->logo_ids) : [];
        $images_ids = isset($request->images_ids) ? explode(', ', $request->images_ids) : [];

        $main_image_ids = isset($request->main_image_ids) ? explode(', ', $request->main_image_ids) : [];
        foreach ($images as $key => $photo) {
            $filename = time() . '-' . $photo->getClientOriginalName();
            $photo_object = new \stdClass();
            $photo_object->name = $photo->getClientOriginalName();

            if ($image_type === 'main_image' && count($main_image_ids) >= 1) {
                $photo_object->errorMessage = 'Only one main image is allowed';
                $photos[] = $photo_object;
                return response()->json(array('files' => $photos));
            }
            if ($image_type === 'feature_image' && count($feature_image_ids) >= 1) {
                $photo_object->errorMessage = 'Only one feature image is allowed';
                $photos[] = $photo_object;
                return response()->json(array('files' => $photos));
            }
            if ($image_type === 'logo' && count($logo_ids) >= 1) {
                $photo_object->errorMessage = 'Only one logo image is allowed';
                $photos[] = $photo_object;
                return response()->json(array('files' => $photos));
            }
            if (in_array($image_type, ['feature_image', 'logo', 'stories', 'images', 'main_image'])) {
                if ($image_type === 'images' && count($images_ids) >= 4) {
                    $photo_object->errorMessage = 'Cannot upload more than 4 images';
                    $photos[] = $photo_object;
                    return response()->json(array('files' => $photos));
                }
                $height = Image::make($photo)->height();
                $width = Image::make($photo)->width();
                /*if ($image_type === 'logo' && ($width < 240 || $height < 200)) {
                $photo_object->errorMessage = 'Required minimum image dimensions 240x200';
                $photos[] = $photo_object;
                return response()->json(array('files' => $photos));
                }
                if ($image_type !== 'logo' && ($width < 800 || $height < 475)) {
                $photo_object->errorMessage = 'Required minimum image dimensions 800x475';
                $photos[] = $photo_object;
                return response()->json(array('files' => $photos));
                }*/

            }
            if ($photo->getSize() < (1024 * 1024)) {
                $destinationPath = config('app.upload_job_path') . $image_type;
                $imageName = $destinationPath . '/' . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($photo), 'public');
                $blog_photo = JobImage::create([
                    'image_type' => $image_type,
                    'image' => $filename
                ]);
                $photo_object->path = config('app.cloudfront_url') . $imageName;
                //$photo_object->size = $size;
                $photo_object->fileID = $blog_photo->id;
            } else {
                $photo_object->errorMessage = 'Required minimum file size < 1MB';
            }
            $photos[] = $photo_object;
        }
        return response()->json(array('files' => $photos));
    }

    public function deletePhoto(Request $request)
    {
        $response = [];
        try {
            abort_if(!$request->id, 404);
            $image = JobImage::findOrFail($request->id);
            $tmp_obj = $image;
            if ($image->delete()) {
                if (file_exists(public_path($tmp_obj->image))) {
                    @unlink(public_path($tmp_obj->image));
                }
                $destinationPath = config('app.upload_job_path') . '/' . $image->image_type;
                $imageName = $destinationPath . '/' . $tmp_obj->image;
                if (config('app.env') === 'production') {
                    Storage::disk('s3')->delete($imageName);
                }
                $response['error'] = false;
                $response['msg'] = 'Image deleted successfully!';
            } else {
                $response['error'] = false;
                $response['msg'] = 'There was a problem while deleting image. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return json_encode($response);
    }

    public function updateJobImages(Request $request, $id, $imageIds)
    {
        foreach (explode(",", $imageIds) as $k => $img_id) {
            $jobImage = JobImage::find($img_id);
            if ($jobImage) {
                $alt_texts['en'] =  $request->alt_text_en[$img_id] ?? '';
                $jobImage->update([
                    'job_id' => $id,
                    'alt_texts' => $alt_texts,
                ]);
            }
        }
    }

    public function applied_candidate($id)
    {

        $job = Job::find($id);

        $skills = Skill::all();

        $array_status = array('Pending', 'Selected', 'Interviewed', 'Shortlisted', 'Rejected');

        return view('publisher.job.applied-candidate', compact('job', 'skills', 'array_status'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {
        //
        $obj = Job::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'job deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    /**
     * Report to show all the CV for Hiring/Admin.
     *
     * @return \Illuminate\Http\Response
     */

    public function findAllCv()
    {
        $cities = City::all();
        $users = JobUsers::with('emirates', 'city_detail')->get();
        return view('publisher.job.find-all-cv', compact('users', 'cities'));
    }

    /**
     * Ajax request to filter all CV.
     *
     * @return \Illuminate\Http\Response
     */

    public function filterAllCv(Request $request)
    {

        $to = Carbon::now()->subYears($request->min_age);
        $from = Carbon::now()->subYears($request->max_age);

        $users = JobUsers::with('emirates', 'city_detail');
        if ($request->state_id) {
            $users = $users->where('region_id', $request->state_id);
        }
        if ($request->keyword) {
            $users = $users->where('headline', 'LIKE', "%$request->keyword%")->orWhere('current_position', 'LIKE', "%$request->keyword%");
        }
        if ($from && $to) {
            $users = $users->whereBetween('date_of_birth', [$from, $to]);
        }
        $users = $users->get();

        $view = view('admin.job.filter-all-cv', compact('users'))->render();
        return response()->json(['html' => $view]);
    }
}
