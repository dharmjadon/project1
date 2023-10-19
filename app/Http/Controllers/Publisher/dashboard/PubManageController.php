<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Models\Job;
use App\Models\Venue;
use App\Models\Events;
use App\Models\Review;
use App\Models\BuySell;
use App\Models\Tickets;
use App\Models\Concierge;
use App\Models\Directory;
use App\Models\CountClick;
use App\Models\Influencer;
use App\Models\EnquireForm;
use App\Models\Accommodation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\MajorCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Education;
class PubManageController extends Controller
{
    //

    public function dashboard()
    {

        $auth_user_type = Auth::user()->user_type;
        if ($auth_user_type != 1) {
            $venues = Venue::where('created_by', Auth::id())->get();
            $events = Events::where('created_by', Auth::id())->get();
            $educations = Education::where('created_by', Auth::id())->get();
            $concierges = Concierge::where('created_by', Auth::id())->get();
            $buysell = BuySell::where('created_by', Auth::id())->get();
            $directory = Directory::where('created_by', Auth::id())->get();
            $influencer = Influencer::where('created_by', Auth::id())->get();
            $jobs = Job::where('created_by', Auth::id())->get();
            $spaces = Accommodation::where('created_by', Auth::id())->get();
            $tickets = Tickets::where('created_by', Auth::id())->get();
            $attractions = Attraction::where('created_by', Auth::id())->get();
        } else {
            $venues = Venue::all();
            $events = Events::all();
            $educations = Education::all();
            $concierges = Concierge::all();
            $buysell = BuySell::all();
            $directory = Directory::all();
            $influencer = Influencer::all();
            $jobs = Job::all();
            $spaces = Accommodation::all();
            $tickets = Tickets::all();
            $attractions = Attraction::all();
        }

        $total_venues = $venues->where('status', 1)->count();
        $waiting_venues = $venues->where('status', 0)->count();

        $total_events = $events->where('status', 1)->count();
        $waiting_events = $events->where('status', 0)->count();

        $total_educations = $educations->where('status', 1)->count();
        $waiting_educations = $educations->where('status', 0)->count();

        $total_concierges = $concierges->where('status', 1)->count();
        $waiting_concierges = $concierges->where('status', 0)->count();

        $total_buysell = $buysell->where('status', 1)->count();
        $waiting_buysell = $buysell->where('status', 0)->count();

        $total_directory = $directory->where('status', 1)->count();
        $waiting_directory = $directory->where('status', 0)->count();

        $total_influencer = $influencer->where('status', 1)->count();
        $waiting_influencer = $influencer->where('status', 0)->count();

        $total_jobs = $jobs->where('status', 1)->count();
        $waiting_jobs = $jobs->where('status', 0)->count();

        $total_spaces = $spaces->where('status', 1)->count();
        $waiting_spaces = $spaces->where('status', 0)->count();

        $total_attractions = $attractions->where('status', 1)->count();
        $waiting_attractions = $attractions->where('status', 0)->count();

        $total_tickets = $tickets->where('status', 1)->count();
        $waiting_tickets = $tickets->where('status', 0)->count();

        // Enquiry Form Count
        // return $venue_enquiry = EnquireForm::groupBy('item_type')->select('enquire_forms.*', \DB::raw('count(*) as total'))->get();
        $venue_enquiry = EnquireForm::whereHasMorph('item', [Venue::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            })->count();

        $event_enquiry = EnquireForm::whereHasMorph('item', [Events::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            })->count();

        $education_enquiry = EnquireForm::whereHasMorph('item', [Education::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            })->count();

        $concierge_enquiry = EnquireForm::whereHasMorph('item', [Concierge::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            })->count();
        $directory_enquiry = EnquireForm::whereHasMorph('item', [Directory::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            })->count();
        $buysell_enquiry = EnquireForm::whereHasMorph('item', [BuySell::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            })->count();
        $influencer_enquiry = EnquireForm::whereHasMorph('item', [Influencer::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            })->count();
        $accommodation_enquiry = EnquireForm::whereHasMorph('item', [Accommodation::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            })->count();
        $attraction_enquiry = EnquireForm::whereHasMorph('item', [Attraction::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            })->count();

        $enquiry = [
            'venue' => $venue_enquiry,
            'event' => $event_enquiry,
            'education' => $education_enquiry,
            'concierge' => $concierge_enquiry,
            'directory' => $directory_enquiry,
            'buysell' => $buysell_enquiry,
            'influencer' => $influencer_enquiry,
            'accommodation' => $accommodation_enquiry,
            'attraction' => $attraction_enquiry,
        ];

        // Whatsapp Enquiry Count
        $count_whatsapp = CountClick::whereHasMorph('product', [Venue::class, Events::class, Education::class, Concierge::class, Directory::class,
                                    BuySell::class, Influencer::class, Accommodation::class, Attraction::class], function(Builder $q){
                                    $q->where('created_by', Auth::id());
                                })->groupBy('product_type')->where('type_of_click', 1)->select('*', \DB::raw('count(*) as total'))->get();
        foreach ($count_whatsapp as $count) {
            if ($count->major_category_id == 1) {
                $whatsapp_venue = $count->total;
            }
            if ($count->major_category_id == 2) {
                $whatsapp_event = $count->total;
            }
            if ($count->major_category_id == 3) {
                $whatsapp_buysell = $count->total;
            }
            if ($count->major_category_id == 4) {
                $whatsapp_directory = $count->total;
            }
            if ($count->major_category_id == 5) {
                $whatsapp_concierge = $count->total;
            }
            if ($count->major_category_id == 6) {
                $whatsapp_influencer = $count->total;
            }
            if ($count->major_category_id == 9) {
                $whatsapp_accommodation = $count->total;
            }
            if ($count->major_category_id == 10) {
                $whatsapp_attraction = $count->total;
            }
            if ($count->major_category_id == 14) {
                $whatsapp_education = $count->total;
            }
        }

        $whatsapp = [
            'venue' => isset($whatsapp_venue) ? $whatsapp_venue : '',
            'event' => isset($whatsapp_event) ? $whatsapp_event : '',
            'education' => isset($whatsapp_education) ? $whatsapp_education : '',
            'buysell' => isset($whatsapp_buysell) ? $whatsapp_buysell : '',
            'concierge' => isset($whatsapp_concierge) ? $whatsapp_concierge : '',
            'directory' => isset($whatsapp_directory) ? $whatsapp_directory : '',
            'influencer' => isset($whatsapp_influencer) ? $whatsapp_influencer : '',
            'accommodation' => isset($whatsapp_accommodation) ? $whatsapp_accommodation : '',
            'attraction' => isset($whatsapp_attraction) ? $whatsapp_attraction : '',
        ];

        // Email Enquiry Count
        $count_whatsapp = CountClick::whereHasMorph('product', [Venue::class, Events::class,Education::class,  Concierge::class, Directory::class,
                                    BuySell::class, Influencer::class, Accommodation::class, Attraction::class], function(Builder $q){
                                    $q->where('created_by', Auth::id());
                                })->groupBy('product_type')->where('type_of_click', 2)->select('*', \DB::raw('count(*) as total'))->get();
        foreach ($count_whatsapp as $count) {
            if ($count->major_category_id == 1) {
                $email_venue = $count->total;
            }
            if ($count->major_category_id == 2) {
                $email_event = $count->total;
            }
            if ($count->major_category_id == 3) {
                $email_buysell = $count->total;
            }
            if ($count->major_category_id == 4) {
                $email_directory = $count->total;
            }
            if ($count->major_category_id == 5) {
                $email_concierge = $count->total;
            }
            if ($count->major_category_id == 6) {
                $email_influencer = $count->total;
            }
            if ($count->major_category_id == 9) {
                $email_accommodation = $count->total;
            }
            if ($count->major_category_id == 10) {
                $email_attraction = $count->total;
            }
            if ($count->major_category_id == 14) {
                $email_education = $count->total;
            }
        }

        $email = [
            'venue' => isset($email_venue) ? $email_venue : '',
            'event' => isset($email_event) ? $email_event : '',
            'education' => isset($email_education) ? $email_education : '',
            'buysell' => isset($email_buysell) ? $email_buysell : '',
            'concierge' => isset($email_concierge) ? $email_concierge : '',
            'directory' => isset($email_directory) ? $email_directory : '',
            'influencer' => isset($email_influencer) ? $email_influencer : '',
            'accommodation' => isset($email_accommodation) ? $email_accommodation : '',
            'attraction' => isset($email_attraction) ? $email_attraction : '',
        ];

        // Phone Enquiry Count
        $count_whatsapp = CountClick::whereHasMorph('product', [Venue::class, Events::class, Education::class, Concierge::class, Directory::class,
                                BuySell::class, Influencer::class, Accommodation::class, Attraction::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            })->groupBy('product_type')->where('type_of_click', 3)->select('*', \DB::raw('count(*) as total'))->get();

        foreach ($count_whatsapp as $count) {
            if ($count->major_category_id == 1) {
                $phone_venue = $count->total;

            }
            if ($count->major_category_id == 2) {
                $phone_event = $count->total;
            }
            if ($count->major_category_id == 3) {
                $phone_buysell = $count->total;
            }
            if ($count->major_category_id == 4) {
                $phone_directory = $count->total;
            }
            if ($count->major_category_id == 5) {
                $phone_concierge = $count->total;
            }
            if ($count->major_category_id == 6) {
                $phone_influencer = $count->total;
            }
            if ($count->major_category_id == 9) {
                $phone_accommodation = $count->total;
            }
            if ($count->major_category_id == 10) {
                $phone_attraction = $count->total;
            }
            if ($count->major_category_id == 14) {
                $phone_education = $count->total;
            }
        }

        $phone = [
            'venue' => isset($phone_venue) ? $phone_venue : '',
            'event' => isset($phone_event) ? $phone_event : '',
            'education' => isset($phone_education) ? $phone_education : '',
            'buysell' => isset($phone_buysell) ? $phone_buysell : '',
            'concierge' => isset($phone_concierge) ? $phone_concierge : '',
            'directory' => isset($phone_directory) ? $phone_directory : '',
            'influencer' => isset($phone_influencer) ? $phone_influencer : '',
            'accommodation' => isset($phone_accommodation) ? $phone_accommodation : '',
            'attraction' => isset($phone_attraction) ? $phone_attraction : '',
        ];

        // Reviews
        // $reviews = Review::with('reviewable')->orderBy('created_at', 'DESC')->take(5)->get();

        if ($auth_user_type != 1) {

            $venues = Venue::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by', Auth::id())->get();
            $events = Events::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by', Auth::id())->get();
            $education = Education::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by', Auth::id())->get();
            $buysell = BuySell::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by', Auth::id())->get();
            $directory = Directory::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by', Auth::id())->get();
            $influencer = Influencer::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by', Auth::id())->get();
            $concierge = Concierge::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by', Auth::id())->get();
            $accommodation = accommodation::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by', Auth::id())->get();
            $attraction = Attraction::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by', Auth::id())->get();
        } else {
            $venues = Venue::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
            $events = Events::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
            $education = Education::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
            $buysell = BuySell::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
            $directory = Directory::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
            $influencer = Influencer::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
            $concierge = Concierge::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
            $accommodation = accommodation::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
            $attraction = Attraction::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
        }

        $major_category = MajorCategory::all();

        return view('publisher.dashboard', compact('total_venues', 'total_events', 'total_concierges', 'total_buysell',
            'total_directory', 'total_influencer', 'total_jobs', 'total_tickets', 'total_spaces', 'total_attractions','total_educations', 'waiting_events', 'waiting_venues',
            'waiting_concierges', 'waiting_buysell', 'waiting_directory', 'waiting_influencer', 'waiting_jobs', 'waiting_spaces', 'waiting_attractions', 'waiting_tickets','waiting_educations',
            'enquiry', 'whatsapp', 'email', 'phone', 'venues', 'events', 'buysell', 'directory', 'influencer', 'concierge', 'accommodation', 'attraction', 'educations','major_category'
        ));
    }

    public function update_profile(Request $request){



        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'company_name' => 'required|unique:users,company_name,'.$request->id,
            'mobile' => 'required',
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

        $profile = null;
        if($request->profile_pic) {
            // $profile = rand(100,100000).'.'.time().'.'.$request->profile_pic->extension();
            // $request->profile_pic->move(public_path('uploads/profile_pic'), $profile);

            $profile = rand(100, 100000) . '.' . time() . '.' . $request->profile_pic->extension();
            $imageFullPath = config('app.upload_profile_pic_path') . $profile;
            Storage::disk('s3')->put($imageFullPath, file_get_contents($request->profile_pic));
        }

        $user = User::find($request->id);
        if(isset($profile)){
            $user->profile_picture = $profile;
        }
        $user->company_name = $request->company_name;
        $user->mobile_no = $request->mobile;
        $user->address = $request->address;
        $user->update();


        $message = [
            'message' => "profile updated",
            'alert-type' => 'success',
        ];
        return back()->with($message);



    }

    public function reset_password(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required:min|4',
            'repeat_password' => 'required|min:4',
            'new_password' => 'required|same:repeat_password',
        ]);

        if ($validator->fails()) {
            $validate = $validator->errors();
            $message_password = [
                'message' => $validate->first(),
                'alert-type' => 'error',
                'error' => $validate->first()
            ];


            return back()->with($message_password);
        }




        $user = User::find($request->id);
        if (Hash::check($request->current_password, $user->password)) {

            $user->password = Hash::make($request->new_password);
            $user->save();

            $message_password = [
                'message_password' => "Password Changed successfully",
                'alert-type-password' => 'success',
            ];

            return back()->with($message_password);
        }else{
            $message_password = [
                'message_password' => "Current Password Is Worng",
                'alert-type-password' => 'error',
            ];
            return back()->with($message_password);
        }

    }





    public function monthFilter(Request $request)
    {
             // Enquiry Form Count
        $education_enquiry = EnquireForm::whereHasMorph('item', [Education::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            });
        if($request->monthId && $request->monthId == 1) {
            $education_enquiry = $education_enquiry->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $education_enquiry = $education_enquiry->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $education_enquiry = $education_enquiry->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId) {
            $education_enquiry = $education_enquiry->whereRaw('WEEKDAY(enquire_forms.created_at) = ' . ($request->weekId - 1));
        }
        $education_enquiry = $education_enquiry->count();
        // Enquiry Form Count
        $venue_enquiry = EnquireForm::whereHasMorph('item', [Venue::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            });
        if($request->monthId && $request->monthId == 1) {
            $venue_enquiry = $venue_enquiry->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $venue_enquiry = $venue_enquiry->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $venue_enquiry = $venue_enquiry->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId) {
            $venue_enquiry = $venue_enquiry->whereRaw('WEEKDAY(enquire_forms.created_at) = ' . ($request->weekId - 1));
        }
        $venue_enquiry = $venue_enquiry->count();

        $event_enquiry = EnquireForm::whereHasMorph('item', [Events::class], function(Builder $q){
                            $q->where('created_by', Auth::id());
                        });
        if($request->monthId && $request->monthId == 1) {
            $event_enquiry = $event_enquiry->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $event_enquiry = $event_enquiry->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $event_enquiry = $event_enquiry->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId) {
            $event_enquiry = $event_enquiry->whereRaw('WEEKDAY(enquire_forms.created_at) = ' . ($request->weekId - 1));
        }
        $event_enquiry = $event_enquiry->count();


        $concierge_enquiry = EnquireForm::whereHasMorph('item', [Concierge::class], function(Builder $q){
                                    $q->where('created_by', Auth::id());
                                });
        if($request->monthId && $request->monthId == 1) {
            $concierge_enquiry =  $concierge_enquiry->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $concierge_enquiry =  $concierge_enquiry->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $concierge_enquiry =  $concierge_enquiry->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId) {
            $concierge_enquiry =  $concierge_enquiry->whereRaw('WEEKDAY(enquire_forms.created_at) = ' . ($request->weekId - 1));
        }
        $concierge_enquiry =  $concierge_enquiry->count();


        $directory_enquiry = EnquireForm::whereHasMorph('item', [Directory::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            });
        if($request->monthId && $request->monthId == 1) {
            $directory_enquiry =  $directory_enquiry->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $directory_enquiry =  $directory_enquiry->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $directory_enquiry =  $directory_enquiry->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId) {
            $directory_enquiry =  $directory_enquiry->whereRaw('WEEKDAY(enquire_forms.created_at) = ' . ($request->weekId - 1));
        }
        $directory_enquiry =  $directory_enquiry->count();

        $buysell_enquiry = EnquireForm::whereHasMorph('item', [BuySell::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            });
        if($request->monthId && $request->monthId == 1) {
            $buysell_enquiry = $buysell_enquiry->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $buysell_enquiry = $buysell_enquiry->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $buysell_enquiry = $buysell_enquiry->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId) {
            $buysell_enquiry = $buysell_enquiry->whereRaw('WEEKDAY(enquire_forms.created_at) = ' . ($request->weekId - 1));
        }
        $buysell_enquiry = $buysell_enquiry->count();

        $influencer_enquiry = EnquireForm::whereHasMorph('item', [Influencer::class], function(Builder $q){
                                    $q->where('created_by', Auth::id());
                                });
        if($request->monthId && $request->monthId == 1) {
            $influencer_enquiry = $influencer_enquiry->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $influencer_enquiry = $influencer_enquiry->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $influencer_enquiry = $influencer_enquiry->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId) {
            $influencer_enquiry = $influencer_enquiry->whereRaw('WEEKDAY(enquire_forms.created_at) = ' . ($request->weekId - 1));
        }
        $influencer_enquiry = $influencer_enquiry->count();

        $accommodation_enquiry = EnquireForm::whereHasMorph('item', [Accommodation::class], function(Builder $q){
                                        $q->where('created_by', Auth::id());
                                    });
        if($request->monthId && $request->monthId == 1) {
            $accommodation_enquiry =$accommodation_enquiry->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $accommodation_enquiry =$accommodation_enquiry->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $accommodation_enquiry =$accommodation_enquiry->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId) {
            $accommodation_enquiry =$accommodation_enquiry->whereRaw('WEEKDAY(enquire_forms.created_at) = ' . ($request->weekId - 1));
        }
        $accommodation_enquiry = $accommodation_enquiry->count();

        $attraction_enquiry = EnquireForm::whereHasMorph('item', [Attraction::class], function(Builder $q){
                                    $q->where('created_by', Auth::id());
                                });
        if($request->monthId && $request->monthId == 1) {
            $attraction_enquiry =$attraction_enquiry->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $attraction_enquiry =$attraction_enquiry->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $attraction_enquiry =$attraction_enquiry->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId && $request->weekId == 1) {
            $attraction_enquiry =$attraction_enquiry->whereRaw('WEEKDAY(enquire_forms.created_at) = ' . ($request->weekId - 1));
        }
        $attraction_enquiry = $attraction_enquiry->count();

        $enquiry = [
            'venue' => $venue_enquiry,
            'event' => $event_enquiry,
            'education' => $education_enquiry,
            'concierge' => $concierge_enquiry,
            'directory' => $directory_enquiry,
            'buysell' => $buysell_enquiry,
            'influencer' => $influencer_enquiry,
            'accommodation' => $accommodation_enquiry,
            'attraction' => $attraction_enquiry,
        ];


        // Whatsapp Enquiry Count
        $count_whatsapp = CountClick::whereHasMorph('product', [Venue::class, Events::class, Concierge::class, Directory::class,
                                BuySell::class, Influencer::class, Accommodation::class, Attraction::class], function(Builder $q){
                                $q->where('created_by', Auth::id());
                            })->groupBy('product_type')->where('type_of_click', 1)->select('*', \DB::raw('count(*) as total'));
        if($request->monthId && $request->monthId == 1) {
            $count_whatsapp = $count_whatsapp->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $count_whatsapp = $count_whatsapp->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $count_whatsapp = $count_whatsapp->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId) {
            $count_whatsapp = $count_whatsapp->whereRaw('WEEKDAY(count_clicks.created_at) = ' . ($request->weekId - 1));
        }
        $count_whatsapp = $count_whatsapp->get();

        foreach($count_whatsapp as $count) {
            if($count->major_category_id == 1 ) {
                $whatsapp_venue = $count->total;
            }
            if($count->major_category_id == 2 ) {
                $whatsapp_event = $count->total;
            }
            if($count->major_category_id == 3 ) {
                $whatsapp_buysell = $count->total;
            }
            if($count->major_category_id == 4 ) {
                $whatsapp_directory = $count->total;
            }
            if($count->major_category_id == 5 ) {
                $whatsapp_concierge = $count->total;
            }
            if($count->major_category_id == 6 ) {
                $whatsapp_influencer = $count->total;
            }
            if($count->major_category_id == 9 ) {
                $whatsapp_accommodation = $count->total;
            }
            if($count->major_category_id == 10 ) {
                $whatsapp_attraction = $count->total;
            }
            if($count->major_category_id == 14 ) {
                $whatsapp_education = $count->total;
            }
        }

        $whatsapp = [
            'venue' =>  isset($whatsapp_venue) ? $whatsapp_venue : '',
            'event' => isset($whatsapp_event) ? $whatsapp_event: '',
            'education' => isset($whatsapp_education) ? $whatsapp_education: '',
            'buysell' => isset($whatsapp_buysell) ? $whatsapp_buysell : '',
            'concierge' => isset($whatsapp_concierge) ? $whatsapp_concierge : '',
            'directory' => isset($whatsapp_directory) ? $whatsapp_directory : '',
            'influencer' =>  isset($whatsapp_influencer) ?  $whatsapp_influencer : '',
            'accommodation' => isset($whatsapp_accommodation) ? $whatsapp_accommodation : '',
            'attraction' => isset($whatsapp_attraction) ? $whatsapp_attraction : '',
        ];


        $count_email = CountClick::whereHasMorph('product', [Venue::class, Events::class, Concierge::class, Directory::class,
                            BuySell::class, Influencer::class, Accommodation::class, Attraction::class], function(Builder $q){
                            $q->where('created_by', Auth::id());
                        })->groupBy('product_type')->where('type_of_click', 2)->select('*', \DB::raw('count(*) as total'));
        if($request->monthId && $request->monthId == 1) {
            $count_email = $count_email->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $count_email = $count_email->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $count_email = $count_email->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId) {
            $count_email = $count_email->whereRaw('WEEKDAY(count_clicks.created_at) = ' . ($request->weekId - 1));
        }
        $count_email = $count_email->get();
         foreach($count_email as $count) {
             if($count->major_category_id == 1 ) {
                 $email_venue = $count->total;
             }
             if($count->major_category_id == 2 ) {
                 $email_event = $count->total;
             }
             if($count->major_category_id == 3 ) {
                 $email_buysell = $count->total;
             }
             if($count->major_category_id == 4 ) {
                 $email_directory = $count->total;
             }
             if($count->major_category_id == 5 ) {
                 $email_concierge = $count->total;
             }
             if($count->major_category_id == 6 ) {
                 $email_influencer = $count->total;
             }
             if($count->major_category_id == 9 ) {
                 $email_accommodation = $count->total;
             }
             if($count->major_category_id == 10 ) {
                 $email_attraction = $count->total;
             }
             if($count->major_category_id == 14 ) {
                 $email_education = $count->total;
             }
         }

         $email = [
            'venue' =>  isset($email_venue) ? $email_venue : '',
            'event' => isset($email_event) ? $email_event: '',
            'education' => isset($email_education) ? $email_education: '',
            'buysell' => isset($email_buysell) ?  $email_buysell: '',
            'concierge' => isset($email_concierge) ? $email_concierge: '',
            'directory' => isset($email_directory) ?  $email_directory : '',
            'influencer' =>  isset($email_influencer) ? $email_influencer : '',
            'accommodation' => isset($email_accommodation) ? $email_accommodation : '',
            'attraction' => isset($email_attraction) ? $email_attraction : '',
        ];


        // Phone Enquiry Count
        $count_phone = CountClick::whereHasMorph('product', [Venue::class, Events::class, Concierge::class, Directory::class,
                            BuySell::class, Influencer::class, Accommodation::class, Attraction::class], function(Builder $q){
                            $q->where('created_by', Auth::id());
                        })->groupBy('product_type')->where('type_of_click', 3)->select('*', \DB::raw('count(*) as total'));
        if($request->monthId && $request->monthId == 1) {
            $count_phone = $count_phone->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $count_phone = $count_phone->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $count_phone = $count_phone->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId) {
            $count_phone = $count_phone->whereRaw('WEEKDAY(count_clicks.created_at) = ' . ($request->weekId - 1));
        }
        $count_phone = $count_phone->get();
        foreach($count_phone as $count) {
            if($count->major_category_id == 1 ) {
                $phone_venue = $count->total;

            }
            if($count->major_category_id == 2 ) {
                $phone_event = $count->total;
            }
            if($count->major_category_id == 3 ) {
                $phone_buysell = $count->total;
            }
            if($count->major_category_id == 4 ) {
                $phone_directory = $count->total;
            }
            if($count->major_category_id == 5 ) {
                $phone_concierge = $count->total;
            }
            if($count->major_category_id == 6 ) {
                $phone_influencer = $count->total;
            }
            if($count->major_category_id == 9 ) {
                $phone_accommodation = $count->total;
            }
            if($count->major_category_id == 10 ) {
                $phone_attraction = $count->total;
            }
             if($count->major_category_id == 14 ) {
                $phone_education = $count->total;
            }
        }

        $phone = [
           'venue' =>  isset($phone_venue) ? $phone_venue : '',
           'event' => isset($phone_event) ? $phone_event : '',
           'education' => isset($phone_education) ? $phone_education : '',
           'buysell' => isset($phone_buysell) ? $phone_buysell : '',
           'concierge' => isset($phone_concierge) ? $phone_concierge : '',
           'directory' => isset($phone_directory) ? $phone_directory : '',
           'influencer' =>  isset($phone_influencer) ? $phone_influencer : '',
           'accommodation' => isset($phone_accommodation) ? $phone_accommodation : '',
           'attraction' => isset($phone_attraction) ? $phone_attraction : '',
       ];


        $data = [
            'enquiry' => $enquiry,
            'whatsapp' => $whatsapp,
            'email' => $email,
            'phone' => $phone,
        ];

        return response()->json($data);
    }

}
