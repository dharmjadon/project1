<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\User;
use App\Models\Job;
use App\Models\Venue;
use App\Models\Events;
use App\Models\BuySell;
use App\Models\Tickets;
use App\Models\Concierge;
use App\Models\Directory;
use App\Models\Influencer;
use App\Models\Accommodation;
use Illuminate\Http\Request;
use App\Mail\PublisherInvoice;
use App\Models\InvoicePublisher;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class InvoiceGeneratorController extends Controller
{
    public function index()
    {
        $publishers = User::where('user_type', 4)->get();
        $report = InvoicePublisher::with('user')->get();
        return view('admin.invoice-generator.index', compact('publishers', 'report'));
    }

    public function report(Request $request)
    {
        $publishers = User::where('user_type', 4)->get();
        $venues = Venue::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $events = Events::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $buysells = BuySell::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $directories = Directory::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $concierges = Concierge::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $influencers = Influencer::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $jobs = Job::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $tickets = Tickets::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $accommodations = Accommodation::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();

        $report = InvoicePublisher::with('user')->get();

        return view('admin.invoice-generator.index', compact('venues', 'events', 'buysells', 'directories', 'concierges', 'influencers', 'jobs', 'tickets', 'accommodations', 'publishers', 'report'));
    }

    public function generate(Request $request){

        $publisher = User::where('id', $request->publisher_id)->first();
        $venues = Venue::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $events = Events::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $buysells = BuySell::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $directories = Directory::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $concierges = Concierge::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $influencers = Influencer::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $jobs = Job::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $tickets = Tickets::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $accommodations = Accommodation::where('created_by', $request->publisher_id)->whereBetween('created_at', [$request->from, $request->to])->get();
        $amount = $request->amount;
        $data = compact('venues', 'events', 'buysells', 'directories', 'concierges', 'influencers', 'jobs', 'tickets', 'accommodations', 'amount', 'publisher');
        $pdf = PDF::loadView('admin.invoice-generator.pdf-report', $data);

        return $pdf->download('publisher-report.pdf');
    }

    public function sendReport(Request $request){
        $validator = Validator::make($request->all(), [
            'publisher_id' => 'required',
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

        $email = User::where('id', $request->publisher_id)->first();
        $data["email"] = $email->email;
        $data["client_name"] = $email->name;
        $data["subject"] = 'Invoice';

        $reportFile = $request->report;

        if ($request->report) {
            $report = rand(100, 100000) . '.' . time() . '.' . $request->report->extension();
            $menuPath = config('app.upload_other_path') . $report;
            Storage::disk('s3')->put($menuPath, file_get_contents($request->report));
        }

        Mail::send('emails.publisher-invoice', $data, function($message) use($data, $report) {
            $message->to($data["email"], $data["client_name"])
            ->subject($data["subject"])
            ->attach(config('app.upload_other_path') . $report);
            });

        // \Mail::to($email->email)->send(new PublisherInvoice($details));


        $obj = new InvoicePublisher();
        $obj->publisher_id = $request->publisher_id;
        $obj->report = $report;
        $obj->save();

        $message = [
            'message' => 'Invoice Sent Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($message);
    }
}
