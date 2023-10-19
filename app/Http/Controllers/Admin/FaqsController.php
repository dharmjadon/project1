<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FaqsCategory;
use App\Models\FaqsQuestionAndAnswer;

// use App\Models\Directory;


class FaqsController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|faqs-add', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Admin|faqs-view', ['only' => ['index']]);
        $this->middleware('role_or_permission:Admin|faqs-update', ['only' => ['edit','update','update_status_faqs','update_status_faqs_qna']]);

    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $datas=FaqsCategory::all();
                // resources\views\admin\faqs\create.blade.php
        return view('admin.faqs.index',compact('datas'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('admin.faqs.create');
    }

    public function faqs_q_and_a(){
        $datas=FaqsQuestionAndAnswer::all();
        $categories=FaqsCategory::where('status','0')->get();
        return view('admin.faqs_q_and_q.index',compact('categories','datas'));

    }


    public function faqs_qna_add(){

        $categories=FaqsCategory::where('status','0')->get();
        return view('admin.faqs_q_and_q.create',compact('categories'));

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
        // dd($request->all());

        if(isset($request->qna_form)){

            $obj = new FaqsQuestionAndAnswer();
            $obj->faqs_category = $request->faqs_category;
            $obj->question = $request->question;
            $obj->answer = $request->answer;
            $obj->status = '0';

            $obj->save();

            $message = [
                'message' => 'Faqs Question and Answer Added Successfully',
                'alert-type' => 'success'
            ];

            return redirect()->back()->with($message);
        }
        else{

        $obj = new FaqsCategory();
        $obj->name = $request->cate_name;
        $obj->save();

        $message = [
            'message' => 'Faqs Category Added Successfully',
            'alert-type' => 'success'
        ];
    }

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

        $categories=FaqsCategory::where('status','0')->get();

        $datas=FaqsQuestionAndAnswer::where('id',$id)->first();

        return view('admin.faqs_q_and_q.edit',compact('datas','categories'));
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
        $datas=FaqsCategory::where('id',$id)->first();

        return view('admin.faqs.edit',compact('datas'));
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
if(isset($request->qna_form)){
    $obj = FaqsQuestionAndAnswer::find($id);
    $obj->faqs_category = $request->faqs_category;
    $obj->question = $request->question;
    $obj->answer = $request->answer;
    $obj->status = '0';

}else{
    $obj = FaqsCategory::find($id);
    $obj->name = $request->cate_name;

}
$obj->save();
        $message = [
            'message' => 'Faqs Category Updated Successfully',
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
    public function destroy(Request $request)
    {
        $obj = FaqsQuestionAndAnswer::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function delete(Request $request)
    {
        $obj = FaqsCategory::find($request->id);
        $obj->delete();

        $message = [
            'message' => 'Deleted successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    public function update_status_faqs(Request $request)
    {

        $id=$request->id;

        $obj = FaqsCategory::find($id);
        $obj->status = $request->status;
        $obj->update();
    }


    public function update_status_faqs_qna(Request $request)
    {
        $job = FaqsQuestionAndAnswer::find($request->id);
        $job->status = $request->status;
        $job->save();
    }
}
