<?php

namespace App\Http\Controllers\Admin;

use App\Models\MiscData;
use Illuminate\Http\Request;
use App\Constants\MiscDataConst;
use App\Models\PublisherLoginBanner;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PublisherLoginBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = PublisherLoginBanner::all();
        $videoGuide = MiscData::where('id', MiscDataConst::PUBLISHER_VIDEO_GUIDANCE)->first();
        return view('admin.publisher-login-banner.index', compact('datas', 'videoGuide'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.publisher-login-banner.create');
    }

    public function createVideoGuide()
    {
        return view('admin.publisher-login-banner.create-video-guide');
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
            'img' => 'required',
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

        $img= '';
        if($request->img) {
            $img = rand(100,100000).'.'.time().'.'.$request->img->extension();
            $menuPath = config('app.upload_other_path') . $img;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->img));
        }

        $obj = new PublisherLoginBanner();
        $obj->img = $img;
        $obj->save();

        $message = [
            'message' => 'Image Added Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($message);
    }


    public function storeVideoGuide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required',
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


        $obj = MiscData::find(MiscDataConst::PUBLISHER_VIDEO_GUIDANCE);
        $obj->data = $request->url;
        $obj->save();

        $message = [
            'message' => 'Data Added Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($message);
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
        $data = PublisherLoginBanner::find($id);
        return view('admin.publisher-login-banner.edit', compact('data'));
    }

    public function editVideoGuide($id)
    {
        $data = MiscData::find(MiscDataConst::PUBLISHER_VIDEO_GUIDANCE);
        return view('admin.publisher-login-banner.edit-video-guide', compact('data'));
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
        $validator = Validator::make($request->all(), [
            'img' => 'required',
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

        $img= '';
        if($request->img) {
            $img = rand(100,100000).'.'.time().'.'.$request->img->extension();
            $menuPath = config('app.upload_other_path') . $img;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->img));
        }

        $obj = MiscData::find(MiscDataConst::PUBLISHER_VIDEO_GUIDANCE);
        $obj->data = $request->url;
        $obj->save();

        $message = [
            'message' => 'Data Added Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($message);
    }

    public function updateVideoGuide(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required',
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

        $obj = MiscData::find(MiscDataConst::PUBLISHER_VIDEO_GUIDANCE);
        $obj->data = $request->url;
        $obj->save();

        $message = [
            'message' => 'Data Added Successfully',
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
    public function destroy($id)
    {
        $obj = PublisherLoginBanner::find($id);
        $obj->delete();

        $message = [
            'message' => 'Image Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
