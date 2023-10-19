<?php

namespace App\Http\Controllers\Admin;

use App\Models\JobCompany;
use DB;
use Illuminate\Validation\Rule;
use Image;
use Helper;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Job;
use App\Models\City;
use App\Models\Skill;
use App\Models\State;
use App\Events\MyEvent;
use App\Models\JobImage;
use App\Models\JobUsers;
use App\Models\JobApplied;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\WishListDetails;
use App\Models\NotificationsInfo;
use App\Models\DynamicSubCategory;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|job-add', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Admin|job-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|job-update', ['only' => ['edit', 'update', 'update_status_jobs']]);
        $this->middleware('role_or_permission:Admin|applied-candidate-view', ['only' => ['applied_candidate']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $pagetitle = 'Manage Contact Requests';
        $activeTab = 'contact-requests';
        $activeSubTab = 'list-contact-requests';
        $main_category = MainCategory::where('major_category_id', 7)->orderBy('name')->pluck('name', 'id');

        $main_categories = $request->get('main_category', []);
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');
        if ($request->ajax()) {
            $jobs = Job::with('sub_category.mainCategory')->orderby('id', 'desc');
            $auth_user_type = Auth::user()->user_type;
            if ($auth_user_type != 1) {
                $jobs = $jobs->where('created_by', Auth::id());
            }
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
                ->addColumn('active', function ($job) use ($auth_user_type) {
                    if ($auth_user_type === 1) {
                        return '<input type="checkbox" class="job-status" data-toggle="switch" data-size="small"
                    data-on-color="green" data-on-text="ON" data-off-color="default" data-off-text="OFF"
                    data-id="' . $job->id . '" value="' . $job->status . '" ' . ($job->status ? "checked" : "") . '>';
                    }
                    return '';
                })
                ->addColumn('company', function ($job) {
                    return isset($job->jobCompany) ? $job->jobCompany->company_name : '';
                })
                ->addColumn('main_category', function ($job) {
                    return isset($job->sub_category->mainCategory) ? $job->sub_category->mainCategory->name : '';
                })
                ->addColumn('publisher_name', function ($job) {
                    if ($job->is_publisher == "1") {
                        return $job->publish_by->name;
                    }
                    return 'N/A';
                })
                ->addColumn('applied_candidate', function ($job) {
                    return isset($job->applied_candidate) ? $job->applied_candidate->count() : '0';
                })
                ->addColumn('action', function ($job) {
                    $btn = '<div class="btn-group" role="group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('admin.job.edit', $job->id) . '">
                                        <i data-feather="edit"></i> Edit </a>
                                <a class="dropdown-item" href="' . route('admin.job.show', $job->id) . '">
                                    <i data-feather="eye"></i> Preview </a>
                                <a class="dropdown-item btn-icon modal-btn" data-id="' . $job->id . '" data-target="#danger" data-toggle="modal" type="button" href="javascript:void(0)">
                                  <i data-feather="trash-2"></i> Delete </a>
                            </div>
                        </div>';
                    return $btn;

                })
                ->rawColumns(['active', 'action'])
                ->make(true);
        }

        return view('admin.job.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $companies = JobCompany::orderBy('company_name', 'asc')->pluck('company_name', 'id')->toArray();
        $array_social_name = Helper::get_social_name();
        $main_category = MainCategory::where('major_category_id', 7)->orderBy('name')->pluck('name', 'id')->toArray();
        $dynamic_main_category = DynamicMainCategory::where('major_category_id', 7)->get();
        return view('admin.job.create', get_defined_vars());
    }

    public function applied_candidate($id)
    {

        $job = Job::find($id);

        $skills = Skill::all();

        $array_status = array('Pending', 'Selected', 'Interviewed', 'Shortlisted', 'Rejected');

        return view('admin.job.applied-candidate', compact('job', 'skills', 'array_status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
            $validatedData['canonical_url'] = route('job-details', $validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['featured'] = $validatedData['assign_featured'] ?? 0;
            $validatedData['is_trending'] = $validatedData['is_trending'] ?? 0;
            $validatedData['is_hot'] = $validatedData['is_hot'] ?? 0;
            $validatedData['discount_offer'] = $validatedData['discount_offer'] ?? 0;

            $validatedData['responsibility'] = json_encode($request->responsibility);
            $validatedData['benefit'] = json_encode($request->benefit);
            //dd($validatedData);
            $job = Job::create($validatedData);
            if ($job) {
                $id = $job->id;
                if ($request->logo_ids) {
                    $this->updateJobImages($request, $id, $request->logo_ids);
                }
                if ($request->main_image_ids) {
                    $this->updateJobImages($request, $id, $request->main_image_ids);
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function show(Job $job)
    {
        //
        $job->load(['subCategory', 'city', 'storyImages', 'mainImages', 'logoImage', 'mainImage']);
        //$data = Job::with('subCategory', 'city')->where('slug', $id)->first();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $job->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }

        return view('admin.job.show', get_defined_vars());
    }

    public function preview($id)
    {
        $data = Job::with('subCategory', 'city_name')->where('id', $id)->first();
        return view('admin.job.preview', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Job $job)
    {
        //$job = Job::findOrFail($id);
        $job->load('logoImage', 'mainImage', 'storyImages');
        if (Auth::user()->user_type != 1 && Auth::id() != $job->created_by) {
            abort(403);
        }
        $companies = JobCompany::orderBy('company_name', 'asc')->pluck('company_name', 'id')->toArray();
        $responsibilty = isset($job->responsibility) ? json_decode($job->responsibility) : [];
        $benefits = isset($job->benefit) ? json_decode($job->benefit) : [];
        $main_category = MainCategory::where('major_category_id', 7)->orderBy('name')->pluck('name', 'id')->toArray();;
        $subcatgories = SubCategory::where('main_category_id', '=', isset($job->sub_category->mainCategory) ? $job->sub_category->mainCategory->id : '')->orderBy('name')->pluck('name', 'id')->toArray();
        $mainImage = $job->mainImage;

        return view('admin.job.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Job $job
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Job $job)
    {
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
            //amenties work
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

                //sending notfication to particular for wish lists
                $wishlist_notifications = WishListDetails::where('item_type', 'App\Models\Job')
                    ->where('item_id', '=', $id)
                    ->where('status', '=', "1")
                    ->where('created_by', '!=', Auth::user()->id)
                    ->where('is_notification_need', '=', "1")
                    ->get();

                $title_msg = "Job Updated";
                $description = $request->title;
                $route = route('job-details', $job->slug);
                Helper::send_notification_wishlist_guys($wishlist_notifications, $route, $title_msg, $description);
                //sending notfication to particular for wish lists

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
            $response['msg'] = 'There was a problem while updating job. Please try later.';
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

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {
        $obj = Job::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'job deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function change_applicant_status(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'applicant_status_id' => 'required',
            'applicant_status' => 'required',
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

        $obj = JobApplied::find($request->applicant_status_id);
        $obj->applicant_status = $request->applicant_status;
        $obj->update();

        $user = $obj->user_detail;
        $route = route('client.applied_detail', $obj->job_id);
        $title_msg = 'Job Application Alert';
        $description = 'Status has been changed';

        Helper::send_notification_jobs_applied($user, $route, $title_msg, $description);

        // Helper::send_notification_jobs_applied()

        $message = [
            'message' => 'candidate status has changed successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);

    }

    public function applied_cv_status(Request $request)
    {

        if ($request->ajax()) {

            $main_id = $request->main_id;
            $type = $request->type;

            if ($type == 1) {
                $job_applied = JobApplied::find($main_id);

                $already_seen = $job_applied->is_cv_seen;

                $job_applied->is_cv_seen = '1';

                $job_applied->update();

                if ($already_seen == '0') {

                    $user = $job_applied->user_detail;
                    $route = route('client.applied_detail', $job_applied->job_id);
                    $title_msg = 'Job Application Alert';
                    $description = 'Resume has been seen';

                    Helper::send_notification_jobs_applied($user, $route, $title_msg, $description);

                }

            } elseif ($type == 2) {
                $job_applied = JobApplied::find($main_id);

                $already_download = $job_applied->is_cv_download;

                $job_applied->is_cv_download = '1';
                $job_applied->update();

                if ($already_download == '0') {

                    $user = $job_applied->user_detail;
                    $route = route('client.applied_detail', $job_applied->job_id);
                    $title_msg = 'Job Application Alert';
                    $description = 'Resume has been download';

                    Helper::send_notification_jobs_applied($user, $route, $title_msg, $description);

                }

            }

            return 'success';

        }

    }

    public function update_status_jobs(Request $request)
    {
        $response['error'] = false;
        abort_if(!$request->id, 400);
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $job = Job::find($id);
        try {
            abort_if(!$job, 404);
            if ($job->update([$field => $value])) {
                if ($job->is_publisher == "1" && $field === 'status') {

                    if ($value == "1") {
                        $satuts_label = "Approved";
                    } else {
                        $satuts_label = "Rejected";
                    }

                    $description_event = $satuts_label . ' By Admin';
                    $message_event = "Admin {$satuts_label} job list";
                    $url_now = route('publisher.job.edit', $job->slug);

                    event(new MyEvent($message_event, $description_event, $url_now, "1", $job->created_by));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_event;
                    $notification->description = $description_event;
                    $notification->notification_for = 1;
                    $notification->url = $url_now;
                    $notification->notify_to = $job->created_by;
                    $notification->save();
                }
                $response['msg'] = 'Job updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating job. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return response()->json($response);
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
        return view('admin.job.find-all-cv', compact('users', 'cities'));
    }

    public function filterAllCv(Request $request)
    {
        $to = Carbon::now()->subYears($request->min_age);
        $from = Carbon::now()->subYears($request->max_age);

        $users = JobUsers::with('emirates', 'city_detail');
        if ($request->city_id) {
            $users = $users->where('city_id', $request->city_id);
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
