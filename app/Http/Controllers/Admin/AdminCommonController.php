<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrivacyPolicy;

class AdminCommonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.privacy_policy.index');
    }
    public function privacy_policy(){

        $datas=PrivacyPolicy::all();
        return view('admin.privacy_policy.index',compact('datas'));

    }

    public function privacy_policy_create()
    {
        return view('admin.privacy_policy.create');
    }

    public function privacy_policy_edit($id)
    {
        $datas=PrivacyPolicy::find($id);

        return view('admin.privacy_policy.edit',compact('datas','id'));
    }





    public function privacy_policy_store(Request $request)
    {
        $obj = new PrivacyPolicy();
        $obj->privacy_policy = $request->privacy_policy;
        $obj->save();

        $message = [
            'message' => 'Privacy Policy Added Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($message);

    }

    public function privacy_policy_update(Request $request)
    {

        $id=$request->id;
        $obj = PrivacyPolicy::find($id);


        $obj->privacy_policy = $request->privacy_policy;
        $obj->save();

        $message = [
            'message' => 'Privacy Policy Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($message);

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //
        return view('admin.privacy_policy.create');
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
        //
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
        //
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






}
