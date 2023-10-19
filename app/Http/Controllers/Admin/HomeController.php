<?php

namespace App\Http\Controllers\Admin;

use App\Models\Job;
use App\Models\Venue;
use App\Models\Events;
use App\Models\Review;
use App\Models\BuySell;
use App\Models\Tickets;
use App\Models\Motors;
use App\Models\Crypto;
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
use App\Models\Education;
use App\Models\Talents; 

class HomeController extends Controller
{
    public function homePage()
    {
        $auth_user_type = Auth::user()->user_type;
        if($auth_user_type != 1){
            $venues = Venue::where('created_by',Auth::id())->get();
            $events = Events::where('created_by',Auth::id())->get();
            $concierges = Concierge::where('created_by',Auth::id())->get();
            $buysell = BuySell::where('created_by',Auth::id())->get();
            $education = Education::where('created_by',Auth::id())->get();
            $directory = Directory::where('created_by',Auth::id())->get();
            $influencer = Influencer::where('created_by',Auth::id())->get();
            $jobs = Job::where('created_by',Auth::id())->get();
            $spaces = Accommodation::where('created_by',Auth::id())->get();
            $tickets = Tickets::where('created_by',Auth::id())->get();
            $attractions = Attraction::where('created_by',Auth::id())->get();
            $motors=Motors::where('created_by',Auth::id())->get();
            $crypto=Crypto::where('created_by',Auth::id())->get();
            $talent=Talents::where('created_by',Auth::id())->get();
        }else{
            $venues = Venue::all();
            $events = Events::all();
            $concierges = Concierge::all();
            $buysell = BuySell::all();
            $education = Education::all();
            $directory = Directory::all();
            $influencer = Influencer::all();
            $jobs = Job::all();
            $spaces = Accommodation::all();
            $tickets = Tickets::all();
            $attractions = Attraction::all();
            $motors=Motors::all();
            $crypto=Crypto::all();
            $talent=Talents::all();
        }

        $total_venues = $venues->where('status', 1)->count();
        $waiting_venues = $venues->where('status', 0)->count();

        $total_events = $events->where('status', 1)->count();
        $waiting_events = $events->where('status', 0)->count();

        $total_concierges = $concierges->where('status', 1)->count();
        $waiting_concierges = $concierges->where('status', 0)->count();

        $total_buysell = $buysell->where('status', 1)->count();
        $waiting_buysell = $buysell->where('status', 0)->count();

        $total_education = $education->where('status', 1)->count();
        $waiting_education = $education->where('status', 0)->count();

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

        $total_motors = $motors->where('status', 1)->count();
        $waiting_motors = $motors->where('status', 0)->count();

        $total_crypto = $crypto->where('status', 1)->count();
        $waiting_crypto = $crypto->where('status', 0)->count();

        $total_talent = $talent->where('status', 1)->count();
        $waiting_talent = $talent->where('status', 0)->count();
        // Enquiry Form Count
        $venue_enquiry = EnquireForm::where('item_type', 'like', '%Venue%')->count();
        $event_enquiry = EnquireForm::where('item_type', 'like', '%Event%')->count();
        $concierge_enquiry = EnquireForm::where('item_type', 'like', '%Concierge%')->count();
        $directory_enquiry = EnquireForm::where('item_type', 'like', '%directory%')->count();
        $buysell_enquiry = EnquireForm::where('item_type', 'like', '%BuySell%')->count();
        $education_enquiry = EnquireForm::where('item_type', 'like', '%Education%')->count();
        $influencer_enquiry = EnquireForm::where('item_type', 'like', '%influencer%')->count();
        $accommodation_enquiry = EnquireForm::where('item_type', 'like', '%accommodation%')->count();
        $attraction_enquiry = EnquireForm::where('item_type', 'like', '%attraction%')->count();
        $motors_enquiry = EnquireForm::where('item_type', 'like', '%motors%')->count();
        $crypto_enquiry = EnquireForm::where('item_type', 'like', '%crypto%')->count();
        $talent_enquiry = EnquireForm::where('item_type', 'like', '%talent%')->count();

        $enquiry = [
            'venue' => $venue_enquiry,
            'event' => $event_enquiry,
            'concierge' => $concierge_enquiry,
            'directory' => $directory_enquiry,
            'buysell' => $buysell_enquiry,
            'education' => $education_enquiry,
            'influencer' => $influencer_enquiry,
            'accommodation' => $accommodation_enquiry,
            'attraction' => $attraction_enquiry,
            'motors'=>$motors_enquiry,
            'crypto'=>$crypto_enquiry,
            'talent'=>$talent_enquiry
        ];

        // Whatsapp Enquiry Count
        $count_whatsapp = CountClick::groupBy('major_category_id')->where('type_of_click', 1)->select('*', \DB::raw('count(*) as total'))->get();
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
            if($count->major_category_id == 16 ) {
                $whatsapp_crypto = $count->total;
            }
            if($count->major_category_id == 17 ) {
                $whatsapp_talent = $count->total;
            }
            if($count->major_category_id ==config('global.motor_major_id')) {
                $whatsapp_motors = $count->total;
            }
        }

        $whatsapp = [
            'venue' =>  isset($whatsapp_venue) ? $whatsapp_venue : '',
            'event' => isset($whatsapp_event) ? $whatsapp_event: '',
            'buysell' => isset($whatsapp_buysell) ? $whatsapp_buysell : '',
            'education' => isset($whatsapp_education) ? $whatsapp_education : '',
            'concierge' => isset($whatsapp_concierge) ? $whatsapp_concierge : '',
            'directory' => isset($whatsapp_directory) ? $whatsapp_directory : '',
            'influencer' =>  isset($whatsapp_influencer) ?  $whatsapp_influencer : '',
            'accommodation' => isset($whatsapp_accommodation) ? $whatsapp_accommodation : '',
            'attraction' => isset($whatsapp_attraction) ? $whatsapp_attraction : '',
            'motors' => isset($whatsapp_motors) ? $whatsapp_motors : '',
            'crypto' => isset($whatsapp_crypto) ? $whatsapp_crypto : '',
            'talent' => isset($whatsapp_talent) ? $whatsapp_talent : '',
        ];

         // Email Enquiry Count
         $count_whatsapp = CountClick::groupBy('major_category_id')->where('type_of_click', 2)->select('*', \DB::raw('count(*) as total'))->get();
         foreach($count_whatsapp as $count) {
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
             if($count->major_category_id == 16 ) {
                 $email_crypto = $count->total;
             }
             if($count->major_category_id == 17 ) {
                 $email_talent = $count->total;
             }
            if($count->major_category_id ==config('global.motor_major_id')) {
                $email_motors = $count->total;
            }
         }

         $email = [
            'venue' =>  isset($email_venue) ? $email_venue : '',
            'event' => isset($email_event) ? $email_event: '',
            'buysell' => isset($email_buysell) ?  $email_buysell: '',
            'education' => isset($email_education) ?  $email_education: '',
            'concierge' => isset($email_concierge) ? $email_concierge: '',
            'directory' => isset($email_directory) ?  $email_directory : '',
            'influencer' =>  isset($email_influencer) ? $email_influencer : '',
            'accommodation' => isset($email_accommodation) ? $email_accommodation : '',
            'attraction' => isset($email_attraction) ? $email_attraction : '',
            'motors' => isset($email_motors) ? $email_motors : '',
            'crypto' => isset($email_crypto) ? $email_crypto : '',
            'talent' => isset($email_talent) ? $email_talent : '',
        ];

        // Phone Enquiry Count
        $count_whatsapp = CountClick::groupBy('major_category_id')->where('type_of_click', 3)->select('*', \DB::raw('count(*) as total'))->get();
        foreach($count_whatsapp as $count) {
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
             if($count->major_category_id == 16 ) {
                $phone_crypto = $count->total;
            }
            if($count->major_category_id == 17 ) {
                $phone_talent = $count->total;
            }
            if($count->major_category_id ==config('global.motor_major_id')) {
                $phone_motors = $count->total;
            }
        }

        $phone = [
           'venue' =>  isset($phone_venue) ? $phone_venue : '',
           'event' => isset($phone_event) ? $phone_event : '',
           'buysell' => isset($phone_buysell) ? $phone_buysell : '',
            'education' => isset($phone_education) ? $phone_education : '',
           'concierge' => isset($phone_concierge) ? $phone_concierge : '',
           'directory' => isset($phone_directory) ? $phone_directory : '',
           'influencer' =>  isset($phone_influencer) ? $phone_influencer : '',
           'accommodation' => isset($phone_accommodation) ? $phone_accommodation : '',
           'attraction' => isset($phone_attraction) ? $phone_attraction : '',
           'motors' => isset($phone_motors) ? $phone_motors : '',
           'crypto' => isset($phone_crypto) ? $phone_crypto : '',
            'talent' => isset($phone_talent) ? $phone_talent : '',
       ];

        // Reviews
        // $reviews = Review::with('reviewable')->orderBy('created_at', 'DESC')->take(5)->get();

        if($auth_user_type != 1){

        $venues = Venue::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by',Auth::id())->get();
        $events = Events::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by',Auth::id())->get();
        $buysell = BuySell::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by',Auth::id())->get();
        $education = Education::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by',Auth::id())->get();
        $directory = Directory::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by',Auth::id())->get();
        $influencer = Influencer::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by',Auth::id())->get();
        $concierge = Concierge::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by',Auth::id())->get();
        $accommodation = accommodation::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by',Auth::id())->get();
        $attraction = Attraction::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by',Auth::id())->get();
        $motors = Motors::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by',Auth::id())->get();
        $crypto = Crypto::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by',Auth::id())->get();
        $talent = Talents::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->where('created_by',Auth::id())->get();

    }else{
        $venues = Venue::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
        $events = Events::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
        $buysell = BuySell::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
        $education = Education::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
        $directory = Directory::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
        $influencer = Influencer::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
        $concierge = Concierge::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
        $accommodation = accommodation::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
        $attraction = Attraction::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
        $motors = Motors::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
        $crypto = Crypto::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
        $talent = Talents::withCount('enquiries', 'clickCountWhatsapp', 'clickCountEmail', 'clickCountPhone')->get();
    }



        $major_category = MajorCategory::all();

        return view('admin.home.index', compact('total_venues', 'total_events', 'total_concierges', 'total_buysell','total_directory', 'total_influencer', 'total_jobs', 'total_tickets', 'total_spaces', 'total_attractions', 'total_education','total_crypto','total_talent','waiting_events', 'waiting_venues','total_motors','waiting_motors','waiting_concierges', 'waiting_buysell', 'waiting_directory', 'waiting_influencer', 'waiting_jobs', 'waiting_spaces', 'waiting_attractions', 'waiting_tickets','waiting_education','waiting_crypto','waiting_talent','enquiry', 'whatsapp', 'email', 'phone', 'venues', 'events', 'buysell', 'directory', 'education','influencer', 'concierge', 'accommodation', 'attraction','major_category','motors','crypto','talent'
        ));
    }

    public function loginPage()
    {
          if(auth::check()){
                return redirect('/admin/dashboard');
          }else{
            return view('admin.auth.login');
          }

    }
    public function logout()
    {
        $redirect = '/';

            Auth::logout();
            return redirect($redirect);
    }

    public function monthFilter(Request $request)
    {
        // Enquiry Form Count
        $venue_enquiry = EnquireForm::where('item_type', 'like', '%Venue%');
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

        $event_enquiry = EnquireForm::where('item_type', 'like', '%Event%');
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


        $education_enquiry = EnquireForm::where('item_type', 'like', '%Education%');
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

        $crypto_enquiry = EnquireForm::where('item_type', 'like', '%Crypto%');
        if($request->monthId && $request->monthId == 1) {
            $crypto_enquiry = $crypto_enquiry->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $crypto_enquiry = $crypto_enquiry->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $crypto_enquiry = $crypto_enquiry->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId) {
            $crypto_enquiry = $crypto_enquiry->whereRaw('WEEKDAY(enquire_forms.created_at) = ' . ($request->weekId - 1));
        }
        $crypto_enquiry = $crypto_enquiry->count();



        $concierge_enquiry = EnquireForm::where('item_type', 'like', '%Concierge%');
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


        $directory_enquiry = EnquireForm::where('item_type', 'like', '%directory%');
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

        $buysell_enquiry = EnquireForm::where('item_type', 'like', '%BuySell%');
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

        $influencer_enquiry = EnquireForm::where('item_type', 'like', '%influencer%');
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

        $accommodation_enquiry = EnquireForm::where('item_type', 'like', '%accommodation%');
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

        $motor_enquiry = EnquireForm::where('item_type', 'like', '%motors%');
        if($request->monthId && $request->monthId == 1) {
            $motor_enquiry =$motor_enquiry->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $motor_enquiry =$motor_enquiry->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $motor_enquiry =$motor_enquiry->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId) {
            $motor_enquiry =$motor_enquiry->whereRaw('WEEKDAY(enquire_forms.created_at) = ' . ($request->weekId - 1));
        }
        $motor_enquiry = $motor_enquiry->count();

        $attraction_enquiry = EnquireForm::where('item_type', 'like', '%attraction%');
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

        $talent_enquiry = EnquireForm::where('item_type', 'like', '%talent%');
        if($request->monthId && $request->monthId == 1) {
            $talent_enquiry =$talent_enquiry->where('created_at', '>', now()->subDays(30)->endOfDay());
        }
        if($request->monthId && $request->monthId == 2) {
            $talent_enquiry =$talent_enquiry->where('created_at', '>', now()->subDays(90)->endOfDay());
        }
        if($request->monthId && $request->monthId == 3) {
            $talent_enquiry =$talent_enquiry->where('created_at', '>', now()->subDays(180)->endOfDay());
        }
        if($request->weekId && $request->weekId == 1) {
            $talent_enquiry =$talent_enquiry->whereRaw('WEEKDAY(enquire_forms.created_at) = ' . ($request->weekId - 1));
        }
        $talent_enquiry = $talent_enquiry->count();

        $enquiry = [
            'venue' => $venue_enquiry,
            'event' => $event_enquiry,
            'concierge' => $concierge_enquiry,
            'directory' => $directory_enquiry,
            'buysell' => $buysell_enquiry,
            'education' => $education_enquiry,
            'influencer' => $influencer_enquiry,
            'accommodation' => $accommodation_enquiry,
            'attraction' => $attraction_enquiry,
            'motors' => $motor_enquiry,
            'crypto' => $crypto_enquiry,
            'talent' => $talent_enquiry,
        ];


        // Whatsapp Enquiry Count
        $count_whatsapp = CountClick::groupBy('major_category_id')->where('type_of_click', 1)->select('*', \DB::raw('count(*) as total'));
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
            if($count->major_category_id == 16 ) {
                $whatsapp_crypto = $count->total;
            }
            if($count->major_category_id == 17 ) {
                $whatsapp_talent = $count->total;
            }
            if($count->major_category_id ==config('global.motor_major_id')) {
                $whatsapp_motors = $count->total;
            }
        }

        $whatsapp = [
            'venue' =>  isset($whatsapp_venue) ? $whatsapp_venue : '',
            'event' => isset($whatsapp_event) ? $whatsapp_event: '',
            'buysell' => isset($whatsapp_buysell) ? $whatsapp_buysell : '',
            'education' => isset($whatsapp_education) ? $whatsapp_education : '',
            'concierge' => isset($whatsapp_concierge) ? $whatsapp_concierge : '',
            'directory' => isset($whatsapp_directory) ? $whatsapp_directory : '',
            'influencer' =>  isset($whatsapp_influencer) ?  $whatsapp_influencer : '',
            'accommodation' => isset($whatsapp_accommodation) ? $whatsapp_accommodation : '',
            'attraction' => isset($whatsapp_attraction) ? $whatsapp_attraction : '',
            'motors' => isset($whatsapp_motors) ? $whatsapp_motors : '',
            'crypto' => isset($whatsapp_crypto) ? $whatsapp_crypto : '',
            'talent' => isset($whatsapp_talent) ? $whatsapp_talent : '',
        ];


        $count_email = CountClick::groupBy('major_category_id')->where('type_of_click', 2)->select('*', \DB::raw('count(*) as total'));
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
             if($count->major_category_id == 16 ) {
                 $email_crypto = $count->total;
             }
             if($count->major_category_id == 17 ) {
                 $email_talent = $count->total;
             }
             if($count->major_category_id ==config('global.motor_major_id')) {
                $email_motors = $count->total;
             }
         }

         $email = [
            'venue' =>  isset($email_venue) ? $email_venue : '',
            'event' => isset($email_event) ? $email_event: '',
            'buysell' => isset($email_buysell) ?  $email_buysell: '',
            'education' => isset($email_education) ?  $email_education: '',
            'concierge' => isset($email_concierge) ? $email_concierge: '',
            'directory' => isset($email_directory) ?  $email_directory : '',
            'influencer' =>  isset($email_influencer) ? $email_influencer : '',
            'accommodation' => isset($email_accommodation) ? $email_accommodation : '',
            'attraction' => isset($email_attraction) ? $email_attraction : '',
            'motors' => isset($email_motors) ? $email_motors : '',
            'crypto' => isset($email_crypto) ? $email_crypto : '',
            'talent' => isset($email_talent) ? $email_talent : '',
        ];


        // Phone Enquiry Count
        $count_phone = CountClick::groupBy('major_category_id')->where('type_of_click', 3)->select('*', \DB::raw('count(*) as total'));
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
            if($count->major_category_id == 14 ) {
                $phone_education = $count->total;
            }
            if($count->major_category_id == 16 ) {
                $phone_crypto = $count->total;
            }
            if($count->major_category_id == 17 ) {
                $phone_talent = $count->total;
            }
            if($count->major_category_id ==config('global.motor_major_id')) {
                $phone_motors = $count->total;
             }
        }

        $phone = [
           'venue' =>  isset($phone_venue) ? $phone_venue : '',
           'event' => isset($phone_event) ? $phone_event : '',
           'buysell' => isset($phone_buysell) ? $phone_buysell : '',
            'education' => isset($phone_education) ? $phone_education : '',
           'concierge' => isset($phone_concierge) ? $phone_concierge : '',
           'directory' => isset($phone_directory) ? $phone_directory : '',
           'influencer' =>  isset($phone_influencer) ? $phone_influencer : '',
           'accommodation' => isset($phone_accommodation) ? $phone_accommodation : '',
           'attraction' => isset($phone_attraction) ? $phone_attraction : '',
           'motors' => isset($phone_motors) ? $phone_motors : '',
           'crypto' => isset($phone_crypto) ? $phone_crypto : '',
           'talent' => isset($phone_talent) ? $phone_talent : '',
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
