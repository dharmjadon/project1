<?php
/**
 * @file
 * Methods related to FAQ in Publisher Login.
 */
namespace App\Http\Controllers\Admin;

use App\Models\PublisherFaq;
use App\Models\MajorCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PublisherFaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = MajorCategory::all();
        $faqs = PublisherFaq::all();
        return view('admin.publisher-faq.index', compact('faqs', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = MajorCategory::all();
        return view('admin.publisher-faq.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
            'question' => 'required',
            ]
        );

        if ($validator->fails()) {
            $validate = $validator->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first(),
            ];
            return back()->with($message);
        }

        $obj = new PublisherFaq();
        $obj->major_category_id = $request->major_category_id;
        $obj->question = $request->question;
        $obj->answer = $request->answer;
        $obj->status = '0';
        $obj->save();

        $message = [
            'message' => 'Faqs Question and Answer Added Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = MajorCategory::all();
        $faq = PublisherFaq::find($id);
        return view('admin.publisher-faq.edit', compact('faq', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(), [
            'question' => 'required',
            ]
        );

        if ($validator->fails()) {
            $validate = $validator->errors();
            $message = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first(),
            ];
            return back()->with($message);
        }

        $obj = PublisherFaq::find($id);
        $obj->major_category_id = $request->major_category_id;
        $obj->question = $request->question;
        $obj->answer = $request->answer;
        $obj->save();

        $message = [
            'message' => 'Faqs Question and Answer Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = PublisherFaq::find($id);
        $obj->delete();

        $message = [
            'message' => 'FAQ Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }

    /**
     * Ajax Request to update Status.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatusFaqsPublisher(Request $request)
    {
        $obj = PublisherFaq::find($request->id);
        $obj->status = $request->status;
        $obj->save();
    }
}
