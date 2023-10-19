<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Events\MyEvent;
use App\Http\Controllers\Admin\Exception;
use App\Models\NotificationsInfo;
use App\Models\WishListDetails;
use Auth;
use Helper;
use DB;
use App\Http\Controllers\Controller;
use App\Models\JobCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class JobCompanyController extends Controller
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
        $pagetitle = 'Manage Job Company';
        $activeTab = 'job-company';
        $activeSubTab = 'list-companies';

        $keyword = $request->get('search');
        if ($request->ajax()) {
            $companies = JobCompany::withCount('openJobs');
            $auth_user_type = Auth::user()->user_type;
            if ($auth_user_type != 1) {
                $companies = $companies->where('created_by', Auth::id());
            }
            // Global search function
            if ($keyword) {
                $companies->where(function ($query) use ($keyword) {
                    $query->where('company_name', 'LIKE', '%' . $keyword . '%')
                    ;
                });
                //"select filed_list from tbale where condition1 and condtion2 and (condition3 or condtion4 or condigiton5) and condition6 orderby column limit 20"
            }
            //dd($companies->toSql());
            $companies = $companies->latest()->get();
            return DataTables::of($companies)
                ->addColumn('action', function ($company) {
                    $btn = '<div class="btn-group" role="group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('publisher.job-company.edit', $company->id) . '">
                                        <i data-feather="edit"></i> Edit </a>
                                <a class="dropdown-item btn-icon modal-btn" data-id="' . $company->id . '" data-target="#danger" data-toggle="modal" type="button" href="javascript:void(0)">
                                  <i data-feather="trash-2"></i> Delete </a>
                            </div>
                        </div>';
                    return $btn;

                })
                ->rawColumns(['active', 'action'])
                ->make(true);
        }

        return view('publisher.job-company.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $array_social_name = Helper::get_social_name();
        return view('publisher.job-company.create', get_defined_vars());
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
                'company_name' => 'required|unique:job_companies,company_name',
                //'slug' => 'required|unique:job_companies,slug',
                'location' => 'required',
                'company_founded' => 'required',
                'logo_image' => 'required|file|max:1024',
            ]
        );

        try {

            $validatedData = $request->post();
            //dd($validatedData);
            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['long'] = $request->citylong;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['contact'] = $validatedData['full_phone_contact'] ?? $validatedData['contact'];
            $validatedData['slug'] = Str::slug($validatedData['company_name']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_featured'] = $validatedData['is_featured'] ?? 0;

            $social_array_name = $request->social_name_array;
            $social_array = explode(',', $social_array_name);
            $social_name_save_array = array();

            foreach ($social_array as $ids) {
                $social_name = 'social_name_' . $ids;
                $social_link = 'social_link_' . $ids;

                if (isset($request->$social_name)) {
                    $array_to_social_name = array(
                        'social_name' => $request->$social_name,
                        'social_link' => $request->$social_link,
                    );
                    $social_name_save_array[] = $array_to_social_name;
                }
            }
            $validatedData['social_links'] = $social_name_save_array;
            //dd($validatedData);
            if ($request->hasFile('logo_image')) {
                $main_image = $request->file('logo_image');
                $destinationPath = config('app.upload_job_path').'logo/';
                $filename = time().'-'.$main_image->getClientOriginalName();
                $imageName = $destinationPath . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($main_image), 'public');
                $validatedData['logo'] = $filename;
            }

            $company = JobCompany::create($validatedData);
            if ($company) {
                $id = $company->id;
                if ($request->logo_ids) {
                    $this->updateJobImages($request, $id, $request->logo_ids);
                }
                if ($request->main_image_ids) {
                    $this->updateJobImages($request, $id, $request->main_image_ids);
                }

                $response['error'] = false;
                $response['msg'] = 'Job "' . $validatedData['company_name'] . '" was created successfully!';
                $response['primary_id'] = $company->id;
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
     * @param  \App\Models\JobCompany  $jobCompany
     * @return \Illuminate\Http\Response
     */
    public function show(JobCompany $jobCompany)
    {
        return view('publisher.job-company.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobCompany  $jobCompany
     * @return \Illuminate\Http\Response
     */
    public function edit(JobCompany $jobCompany)
    {
        $array_social_name = Helper::get_social_name();
        $social_links = $jobCompany->social_links ?? [];
        return view('publisher.job-company.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobCompany  $jobCompany
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobCompany $jobCompany)
    {
        $request->validate(
            [
                'company_name' => 'required|'.Rule::unique('job_companies')->ignore($jobCompany),
                //'slug' => 'required|'.Rule::unique('job_companies')->ignore($jobCompany),
                'location' => 'required',
                'company_founded' => 'required',
                'logo_image' => 'file|max:1024',
            ]
        );

        try {

            $validatedData = $request->post();

            DB::beginTransaction();
            $validatedData['lat'] = $request->citylat;
            $validatedData['long'] = $request->citylong;
            $validatedData['whatsapp'] = $validatedData['full_phone_whatsapp'] ?? $validatedData['whatsapp'];
            $validatedData['contact'] = $validatedData['full_phone_contact'] ?? $validatedData['contact'];
            //$validatedData['slug'] = Str::slug($validatedData['company_name']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['is_popular'] = $validatedData['is_popular'] ?? 0;
            $validatedData['is_featured'] = $validatedData['is_featured'] ?? 0;

            $social_array_name = $request->social_name_array;
            $social_array = explode(',', $social_array_name);

            $social_name_save_array = array();

            foreach ($social_array as $ids) {
                $social_name = 'social_name_' . $ids;
                $social_link = 'social_link_' . $ids;

                if (isset($request->$social_name)) {
                    $array_to_social_name = array(
                        'social_name' => $request->$social_name,
                        'social_link' => $request->$social_link,
                    );
                    $social_name_save_array[] = $array_to_social_name;
                }
            }
            $validatedData['social_links'] = $social_name_save_array;
            //dd($validatedData);
            if ($request->hasFile('logo_image')) {
                $main_image = $request->file('logo_image');
                $destinationPath = config('app.upload_job_path').'logo/';
                $filename = time().'-'.$main_image->getClientOriginalName();
                $imageName = $destinationPath . $filename;
                Storage::disk('s3')->put($imageName, file_get_contents($main_image), 'public');
                $validatedData['logo'] = $filename;
            }

            if ($jobCompany->update($validatedData)) {
                $id = $jobCompany->id;
                $response['error'] = false;
                $response['msg'] = 'Company was updated successfully!';
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to update company. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            $response['error'] = true;
            $response['msg'] = 'There was a problem while updating company. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    public function update_status(Request $request)
    {
        $response['error'] = false;
        abort_if(!$request->id, 400);
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $jobCompany = JobCompany::find($id);
        try {
            abort_if(!$jobCompany, 404);
            if ($jobCompany->update([$field => $value])) {
                if ($jobCompany->is_publisher == "1" && $field === 'status') {

                    if ($value == "1") {
                        $satuts_label = "Approved";
                    } else {
                        $satuts_label = "Rejected";
                    }

                    $description_event = $satuts_label . ' By Admin';
                    $message_event = "Admin {$satuts_label} job company list";
                    $url_now = route('publisher.job-company.edit', $jobCompany->slug);

                    event(new MyEvent($message_event, $description_event, $url_now, "1", $jobCompany->created_by));

                    $notification = new NotificationsInfo();
                    $notification->title = $message_event;
                    $notification->description = $description_event;
                    $notification->notification_for = 1;
                    $notification->url = $url_now;
                    $notification->notify_to = $jobCompany->created_by;
                    $notification->save();
                }
                $response['msg'] = 'Company updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating company. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobCompany  $jobCompany
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobCompany $jobCompany)
    {
        //
    }
}
