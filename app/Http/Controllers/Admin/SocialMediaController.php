<?php

namespace App\Http\Controllers\Admin;

use App\Models\SocialMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SocialMediaController extends Controller
{
    public function index()
    {
        $datas = SocialMedia::all();
        return view('admin.social-media.index', compact('datas'));

    }

    public function edit($id)
    {
        $datas = SocialMedia::find($id);
        return view('admin.social-media.edit', compact('datas'));

    }

    public function update(Request $request, $id)
    {
        $obj = SocialMedia::find($id);
        $obj->url = $request->url;
        $obj->save();

        $message = [
            'message' => 'Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);

    }
}
