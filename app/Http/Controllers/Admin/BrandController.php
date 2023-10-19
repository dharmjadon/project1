<?php
namespace App\Http\Controllers\Admin;

use App\Models\Brand;
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

class BrandController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Admin|brand-add', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Admin|brand-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|brand-update', ['only' => ['edit', 'update', 'update_status_brand']]);
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
           $brand = Brand::orderBy('id', 'DESC');
            if ($keyword) {
                $brand->where(function ($query) use ($keyword) {
                    $query->where('brands.title', 'LIKE', '%' . $keyword . '%');
                });
            }

            if (!empty($date_from) && empty($keyword)) {
                $brand->where('brands.created_at', '>', $date_from . " 00:00:00");
            }

            if (!empty($date_from) && empty($keyword)) {
                $brand->where('brands.created_at', '<=', $date_to . " 23:59:59");
            }

            $brand = $brand->get();
            return DataTables::of($brand)
              ->addColumn('title', function($brand) {
                    return isset($brand->title) ? $brand->title : '';
                })
                ->addColumn('active', function ($brand) {

                    return '<input type="checkbox" class="buysell-status" data-toggle="switch" data-size="small" data-on-color="green" data-on-text="ON" data-off-color="default" data-off-text="OFF" data-id="' . $brand->id . '" value="' . $brand->status . '" ' . ($brand->status ? "checked" : "") . '>';
                })

                ->addColumn('action', function ($brand) {
                    $btn = '<div class="btn-group" role="group"><button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions</button><div class="dropdown-menu"><a class="dropdown-item" href="' . route('admin.brand.edit',
                        $brand) . '"><i data-feather="edit"></i> Edit </a><a class="dropdown-item btn-icon modal-btn" data-href="' . route('admin.brand.destroy', $brand) . '" data-target="#remove" data-toggle="modal" type="button" href="javascript:void(0)"><i data-feather="trash-2"></i> Delete </a></div></div>';

                    return $btn;
                })
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at, 'Asia/Dubai')->timestamp;
                })
                ->rawColumns(['active', 'action'])
                ->make(true);
        }
        return view('admin.brand.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.brand.create', get_defined_vars());
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
                'title' => 'required|unique:brands,title',
                'slug' => 'required|unique:brands,slug'
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

            $brand = Brand::create($validatedData);
            if($brand)
            {
                $response['error'] = false;
                $response['msg'] = 'Brand "' . $validatedData['title'] . '" was created successfully!';
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add Brand. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {
          
            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding brand. Please try later.';
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
    public function edit(Brand $brand)
    {
        if (Auth::user()->user_type != 1 && Auth::id() != $brand->created_by) {
            abort(403);
        }

        return view('admin.brand.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param BuySell $BuySel
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate(
            [
                'title' => 'required|min:1|max:100|'.Rule::unique('brands')->ignore($brand),
                'slug' => 'required|'.Rule::unique('brands')->ignore($brand),
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
            if ($brand->update($validatedData)) {
                $id = $brand->id;

                $request->module_id = $id;
                $request->module_name = 'Brand';
                $request->user_type = 'admin';

                $title_msg = "Brand Updated";
                $description = $request->title;


                $response['error'] = false;
                $response['msg'] = 'Brand "' . $validatedData['title'] . '" was updated successfully!';
                $response['primary_id'] = $brand->id;
            } else {
                $response['error'] = false;
                $response['msg'] = 'Unable to add Brand. Please try later.';
                return response()->json($response, 500);
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollback();
            $response['error'] = true;
            $response['msg'] = 'There was a problem while adding brand. Please try later.';
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
    public function destroy(Brand $brand)
    {
        $brand->delete();

        $message = [
            'message' => 'Brand Deleted Successfully',
            'alert-type' => 'success'
        ];

        # Redirect
        return redirect()->back()->with($message);
    }

    public function update_status_brand(Request $request)
    {
        $response['error'] = false;
        abort_if(!$request->id, 400);
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $brand = Brand::find($id);
        try {
            abort_if(!$brand, 404);
            if ($brand->update([$field => $value])) {
                $response['msg'] = 'Brand updated successfully!';
            } else {
                $response['error'] = true;
                $response['msg'] = 'There was a problem while updating Brand. Please try later.';
            }

        } catch (Exception $ex) {
            $response['error'] = true;
            $response['msg'] = $ex->getMessage();
        }
        return response()->json($response);
    }

}
