<?php
namespace App\Http\Controllers\Admin;

use App\Models\Manufacturer;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Helper;
use Illuminate\Validation\Rule;
use Image;
use DB;
use Yajra\DataTables\Facades\DataTables;

class MotorManufacturerController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Admin|manufacturer-add', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Admin|manufacturer-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|manufacturer-update', ['only' => ['edit', 'update', 'update_status_manufacturer']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $keyword = $request->get('search');

        if ($request->ajax()) {
           $manufacturer = Manufacturer::orderBy('id', 'DESC');
            if ($keyword) {
                $manufacturer->where(function ($query) use ($keyword) {
                    $query->where('manufacturers.title', 'LIKE', '%' . $keyword . '%');
                });
            }

            if (!empty($date_from) && empty($keyword)) {
                $manufacturer->where('manufacturers.created_at', '>', $date_from . " 00:00:00");
            }

            if (!empty($date_from) && empty($keyword)) {
                $manufacturer->where('manufacturers.created_at', '<=', $date_to . " 23:59:59");
            }

            $manufacturer = $manufacturer->get();
            return DataTables::of($manufacturer)
              ->addColumn('title', function($manufacturer) {
                    return isset($manufacturer->title) ? $manufacturer->title : '';
                })
                ->addColumn('active', function ($manufacturer) {

                    return '<input type="checkbox" class="buysell-status" data-toggle="switch" data-size="small" data-on-color="green" data-on-text="ON" data-off-color="default" data-off-text="OFF" data-id="' . $manufacturer->id . '" value="' . $manufacturer->status . '" ' . ($manufacturer->status ? "checked" : "") . '>';
                })

                ->addColumn('action', function ($manufacturer) {
                    $btn = '<div class="btn-group" role="group"><button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button><div class="dropdown-menu"><a class="dropdown-item" href="' . route('admin.manufacturer.edit',
                        $manufacturer) . '"><i data-feather="edit"></i> Edit </a><a class="dropdown-item btn-icon modal-btn" data-href="' . route('admin.manufacturer.destroy', $manufacturer) . '" data-target="#remove" data-toggle="modal" type="button" href="javascript:void(0)"><i data-feather="trash-2"></i> Delete </a></div></div>';

                    return $btn;
                })
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at, 'Asia/Dubai')->timestamp;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
        }
        return view('admin.manufacturer.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.manufacturer.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|unique:manufacturers,title',
                'slug' => 'required|unique:manufacturers,slug'
            ],
            [

            ]
        );
        $logo = '';
        if ($request->logo) {
            // $icon = rand(100,100000).'.'.time().'.'.$request->icon->extension();
            $image_parts = explode(";base64,", $request->logo);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $logo = uniqid() . time() . '.png';
            $imageFullPath = config('app.upload_education_path') . $logo;
            Storage::disk('s3')->put($imageFullPath, $image_base64);
            $logoPath = $logo;
        }
        try {
            $validatedData = $request->post();
            DB::beginTransaction();
            //dd($validatedData);
            $validatedData['logo'] = $logo;
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            $validatedData['status'] = $validatedData['status'] ?? 0;

            $manufacturer = Manufacturer::create($validatedData);
            if($manufacturer)
            {
                $response['error'] = false;
                $response['msg'] = 'Manufacturer "' . $validatedData['title'] . '" was created successfully!';
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add Manufacturer. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
          
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding manufacturer. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param BuySell $BuySell
     * @return \Illuminate\Http\Response
     */





    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Manufacturer $manufacturer)
    {
        if (Auth::user()->user_type != 1 && Auth::id() != $manufacturer->created_by) {
            abort(403);
        }

        return view('admin.manufacturer.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param BuySell $BuySel
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Manufacturer $manufacturer)
    {
        $request->validate(
            [
                'title' => 'required|min:1|max:100|'.Rule::unique('manufacturers')->ignore($manufacturer),
                'slug' => 'required|'.Rule::unique('manufacturers')->ignore($manufacturer),
            ],
            [

            ]
        );
        $logo = '';
        if ($request->logo) {
            // $icon = rand(100,100000).'.'.time().'.'.$request->icon->extension();
            $image_parts = explode(";base64,", $request->logo);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $logo = uniqid() . time() . '.png';
            $imageFullPath = config('app.upload_education_path') . $logo;
            Storage::disk('s3')->put($imageFullPath, $image_base64);
            $logoPath = $logo;
        }
        try {

            $validatedData = $request->post();

            DB::beginTransaction();
            $validatedData['logo'] = $logo;
            $validatedData['slug'] = Str::slug($validatedData['slug']);
            $validatedData['created_by'] = Auth::user()->id;
            //$validatedData['status'] = $validatedData['status'] ?? 0;
//dd($validatedData);
            if ($manufacturer->update($validatedData)) {
                $id = $manufacturer->id;

                $request->module_id = $id;
                $request->module_name = 'Manufacturer';
                $request->user_type = 'admin';

                $title_msg = "Manufacturer Updated";
                $description = $request->title;


                $response['error'] = false;
                $response['msg'] = 'Manufacturer "' . $validatedData['title'] . '" was updated successfully!';
                $response['primary_id'] = $manufacturer->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add manufacturer. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding manufacturer. Please try later.';
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BuySell $BuySell
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manufacturer $manufacturer)
    {
        $manufacturer->delete();

        $message = [
            'message' => 'Manufacturer Deleted Successfully',
            'alert-type' => 'success'
        ];

        # Redirect
        return redirect()->back()->with($message);
    }

    public function update_status_manufacturer(Request $request)
    {
        $response['error'] = false;
        abort_if(!$request->id, 400);
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $manufacturer = Manufacturer::find($id);
        try {
            abort_if(!$manufacturer, 404);
            if ($manufacturer->update([$field => $value])) {
                $response['msg'] = 'Manufacturer updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating manufacturer. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return response()->json($response);
    }

}
