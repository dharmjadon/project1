<?php

namespace App\Http\Controllers\User;

use App\Models\Education;
use App\Models\It;
use Exception;
use Log;
use File;
use Config;
use App\Models\User;
use App\Models\Job;
use Carbon\Carbon;
use App\InboundSeo;
use App\Models\Blog;
use App\Models\City;
use App\Models\State;
use App\Models\Crypto;
use App\Models\News;
use App\Models\Venue;
use App\Models\Career;
use App\Models\Events;
use App\Models\Motors;
use App\Models\Review;
use App\Models\AboutUs;
use App\Models\BuySell;
use App\Models\Gallery;
use App\Models\Tickets;
use App\Events\MyEvent;
use App\Models\GiveAway;
use App\Models\BookTable;
use App\Models\Concierge;
use App\Models\ContactUs;
use App\Models\Directory;
use App\Models\Talents;
use App\Mail\EnquiryMail;
use App\Models\Attraction;
use App\Models\BookArtist;
use App\Models\Influencer;
use App\Mail\GiveAwayMail;
use App\Models\EnquireForm;
use App\Models\Newslettter;
use App\Models\OtherBanner;
use App\Models\SliderImage;
use App\Models\SubCategory;
use App\Mail\ContactUsMail;
use App\Models\FaqsCategory;
use App\Models\MainCategory;
use App\Models\SliderBanner;
use Mail;
use Spatie\Sitemap\Sitemap;
use App\Models\Accommodation;
use App\Models\GiveAwayClaim;
use App\Models\MajorCategory;
use Illuminate\Http\Request;
use Spatie\Sitemap\Tags\Url;
use App\Models\Recommendation;
use App\Models\WishListDetails;
use App\Models\InfluencerReview;
use App\Models\NotificationsInfo;
use App\Models\PopularPlaceVenue;
use App\Models\EnquireTypeBuySell;
use App\Models\ItemRecommendation;
use App\Models\PopularPlaceRating;
use App\Models\PopularPlacesTypes;
use App\Mail\ConciergeEnquiryMail;
use App\Models\ServiceTypeTopTrend;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\FaqsQuestionAndAnswer;
use Illuminate\Support\Facades\Auth;
use App\Models\PopularPlaceSuggestion;
use App\Models\TagBlog;
use Illuminate\Support\Facades\Validator;
use Request as RequestIp;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Location\Facades\Location;


class CommonController extends Controller
{
    protected $redirectTo1 = "/admin/dashboard";

    public function thankYou()
    {
        return view('thankyou');
    }

    public function getUserLocation()
    {
        $data = Location::get(getUserIpAddr());
        return json_encode($data);
    }

    public function enquireForm(Request $request)
    {
        $response = [];
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'mobile' => 'required',
                'message' => 'required',
                'major_category' => 'required',
                'item_id' => 'required',
                'g-recaptcha-response' => 'required'//|recaptchav3:fld_recaptcha,0.5'
            ]);
            $user_location = userIPLocation();
            if (isset($request->full_phone)) {
                $request->mobile = $request->full_phone;
                if (strpos($request->mobile, '+') === false) {
                    $response['error'] = true;
                    $response['msg'] = 'Please select country code for phone number.';
                    return json_encode($response);
                }
            }
            $created_by = 0;

            if (Auth::check()) {
                $created_by = Auth::user()->id;
            }
            $enquire = new EnquireForm();
            $enquire->name = $request->name;
            $enquire->email = $request->email;
            $enquire->mobile = $request->full_phone ?? $request->mobile;
            $enquire->message = $request->message;
            if (isset($request->subject) && $request->subject != '') {
                $enquire->subject = $request->subject;
            }

            $enquire->created_by = $created_by;
            if (isset($request->date_and_time)) {
                $enquire->date_and_time = $request->date_and_time;
            }
            $enquire->save();

            $last_inserted_id = $enquire->id;

            if ($request->major_category == 3) {
                $enquiry_type_buysell = new EnquireTypeBuySell();
                $enquiry_type_buysell->enquire_form_id = $last_inserted_id;
                $enquiry_type_buysell->enquiry_type = $request->enquiry_type;
                $enquiry_type_buysell->save();
            }

            $created_by_id = 0;
            $list_name = "";
            if ($request->major_category == 1) {
                $type = Venue::find($request->item_id);
                $email = $type->email;
                $list_name = "venue";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            } elseif ($request->major_category == 2) {
                $type = Events::find($request->item_id);
                $email = $type->email;

                $list_name = "event";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            } elseif ($request->major_category == 3) {
                $type = BuySell::find($request->item_id);
                $email = $type->enquiry_email;

                $list_name = "buy & sell";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            } elseif ($request->major_category == 4) {
                $type = Directory::find($request->item_id);
                $email = $type->enquiry_email;

                $list_name = "directory";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            } elseif ($request->major_category == 5) {
                $type = Concierge::find($request->item_id);
                $email = $type->email;

                $list_name = "concierge";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            } elseif ($request->major_category == 6) {
                $type = Influencer::find($request->item_id);
                $email = $type->email;

                $list_name = "influencer";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            } elseif ($request->major_category == 8) {
                $type = Tickets::find($request->item_id);
                $email = $type->email;

                $list_name = "tickets";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            } elseif ($request->major_category == 9) {
                $type = Accommodation::find($request->item_id);
                $email = $type->email;


                $list_name = "accommodation";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            } elseif ($request->major_category == 10) {
                $type = Attraction::find($request->item_id);
                $email = $type->email;
                $list_name = "attraction";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            } elseif ($request->major_category == 11) {
                $type = BookArtist::find($request->item_id);
                $email = $type->email;
                $list_name = "book artist";
                $created_by_id = $type->created_by;
                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            } elseif ($request->major_category == 14) {
                $type = Education::find($request->item_id);
                $email = $type->email;
                $list_name = "education";
                $created_by_id = $type->created_by;
                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            } elseif ($request->major_category == 15) {
                $type = Concierge::find($request->item_id);
                $email = $type->email;
                $list_name = "concierge";
                $created_by_id = $type->created_by;
                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            } elseif ($request->major_category == 16) {
                $type = Crypto::find($request->item_id);
                $email = $type->email;
                $list_name = "crypto";
                $created_by_id = $type->created_by;
                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            
            } elseif ($request->major_category == 17) {
                $type = GiveAway::find($request->item_id);
                $email = 'giveaway@partyfinder.com';
                $list_name = "giveaway";
                $created_by_id = $type->created_by ?? 1;

                $count = $type->claimed + 1;
                $type->claimed = $count;
                $type->timestamps = false;
                $type->save();

                $give_away_claim = new GiveAwayClaim(['name' => $request->name, 'email' => $request->email, 'mobile' => $request->mobile, 'message' => $request->message, 'created_by' => $created_by]);
                $type->claim()->save($give_away_claim);

                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            } elseif ($request->major_category == config('global.motor_major_id')) {

                $type = Motors::find($request->item_id);
                $email = $type->email;


                $list_name = "motors";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.enquiry.index');
                $url_now_publisher = route('publisher.enquiry.index');
            }

            $this->send_notfication_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);
            if ($type->is_publisher == "1") {
                $this->send_notfication_to_admin_and_publisher($list_name, $url_now_publisher, "1", $created_by_id);
            }

            $type->enquiries()->save($enquire);

            $details = [
                'name' => $request->name,
                'mobile' => $request->full_number,
                'email' => $request->email,
                'message' => $request->message
            ];

            if (!$email) {
                $email = 'farhaz@mailinator.com';
            }
            $contactUs = $url = config('extra.contact_us');
            $emails = [$email, $contactUs];
            if ($request->major_category == 16) {
                $details = [
                    'name' => $request->name,
                    'mobile' => $request->mobile,
                    'email' => $request->email,
                    'message' => $request->message,
                    'title' => $type->title,
                ];
                Mail::to($emails)->send(new GiveAwayMail($details));
            } else {
                Mail::to($emails)->send(new EnquiryMail($details));
            }

            // for Concierge mail
            // if ($request->major_category == 5) {
            //     $subject="Enquiry Mail";
            //     $date_time_concierge = strtotime($request->date_time);
            //     $date_concierge = date('d/M/Y', $date_time_concierge);
            //     $time_concierge = date('g:i A', $date_time_concierge);
            //     $total_person = $request->men + $request->women + $request->child;
            //     $details = [
            //         'name' => $request->name,
            //         'mobile' => $request->mobile,
            //         'email' => $request->email,
            //         'message' => $request->message,
            //         'date_concierge' => $date_concierge,
            //         'time_concierge' => $time_concierge,
            //         'men' => $request->men,
            //         'women' => $request->women,
            //         'child' => $request->child,
            //         'total_person' => $total_person,
            //         'title' => $type->title,
            //         'subject' => $subject

            //     ];

            //     if (!$email) {
            //         $email = 'farhaz@mailinator.com';
            //     }
            //     $contactUs = $url = \config('extra.contact_us');
            //     $emails = [$email, $contactUs];

            //     \Mail::to($emails)->send(new ConciergeEnquiryMail($details));
            // }

            // \Mail::to('contact@thepartyfinder.com')->send(new EnquiryMail($details));
            // dd($d);

            $response['error'] = false;
            $response['msg'] = 'Thank you for submitting your details. Our team will contact you soon!!';
        } catch (Exception $ex) {
            // dd($ex->getMessage());
            $response['error'] = true;
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
            $response['msg'] = 'There was some problem while processing your request. Please try later.';
        }
        return json_encode($response);
        /*$message = [
        'message' => 'Enquiry Sent Successfully',
        'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);*/
    }

    public function reviewForm(Request $request)
    {
        // return $request->all();
        $response = [];
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'comment' => 'required',
                'major_category' => 'required',
                'item_id' => 'required',
                'g-recaptcha-response' => 'required'//|recaptchav3:fld_recaptcha,0.5'
            ]);
            $created_by = 0;

            if (Auth::check()) {
                $created_by = Auth::user()->id;
            }

            $review = new Review(['name' => $request->name, 'email' => $request->email, 'comment' => $request->comment, 'rating' => $request->review, 'created_by' => $created_by]);
            if ($request->major_category == 1) {
                $type = Venue::find($request->item_id);

                $list_name = "venue";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.review.index');
                $url_now_publisher = route('publisher.review.index');
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_publisher, "1", $created_by_id);
            } elseif ($request->major_category == 2) {
                $type = Events::find($request->item_id);

                $list_name = "event";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.review.index');
                $url_now_publisher = route('publisher.review.index');
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_publisher, "1", $created_by_id);
            } elseif ($request->major_category == 3) {
                $type = BuySell::find($request->item_id);

                $list_name = "buysell";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.review.index');
                $url_now_publisher = route('publisher.review.index');
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_publisher, "1", $created_by_id);
            } elseif ($request->major_category == 4) {
                $type = Directory::find($request->item_id);

                $list_name = "directory";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.review.index');
                $url_now_publisher = route('publisher.review.index');
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_publisher, "1", $created_by_id);
            } elseif ($request->major_category == 5) {
                $type = Concierge::find($request->item_id);

                $list_name = "concierge";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.review.index');
                $url_now_publisher = route('publisher.review.index');
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_publisher, "1", $created_by_id);
            } elseif ($request->major_category == 6) {
                $type = Influencer::find($request->item_id);

                $list_name = "influencer";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.review.index');
                $url_now_publisher = route('publisher.review.index');
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_publisher, "1", $created_by_id);
            } elseif ($request->major_category == 9) {
                $type = Accommodation::find($request->item_id);

                $list_name = "accommodation";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.review.index');
                $url_now_publisher = route('publisher.review.index');
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_publisher, "1", $created_by_id);
            } elseif ($request->major_category == 10) {
                $type = Attraction::find($request->item_id);


                $list_name = "attraction";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.review.index');
                $url_now_publisher = route('publisher.review.index');
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_publisher, "1", $created_by_id);
            } elseif ($request->major_category == 14) {
                $type = Education::find($request->item_id);

                $list_name = "education";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.review.index');
                $url_now_publisher = route('publisher.review.index');
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_publisher, "1", $created_by_id);
            } elseif ($request->major_category == 16) {
                $type = Crypto::find($request->item_id);

                $list_name = "crypto";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.review.index');
                $url_now_publisher = route('publisher.review.index');
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_publisher, "1", $created_by_id);
            } elseif ($request->major_category == 16) {
                $type = Blog::find($request->item_id);


                $list_name = "blog";
                $created_by_id = $request->item_id;

                $url_now_admin = route('admin.review.index');
                $url_now_publisher = route('publisher.review.index');
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_publisher, "1", $created_by_id);
            } elseif ($request->major_category == config('global.motor_major_id')) {
                $type = Motors::find($request->item_id);

                $list_name = "motors";
                $created_by_id = $type->created_by;

                $url_now_admin = route('admin.review.index');
                $url_now_publisher = route('publisher.review.index');
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);
                $this->send_notfication_review_to_admin_and_publisher($list_name, $url_now_publisher, "1", $created_by_id);
            }
            $type->reviews()->save($review);
            $response['error'] = false;
            $response['msg'] = 'Thank you for submitting your details. Our team will contact you soon!!';
        } catch (Exception $ex) {
            $response['error'] = true;
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
            $response['msg'] = 'There was some problem while processing your request. Please try later.';
        }
        return json_encode($response);
        /*$message = [
        'review' => 'Review Sent Successfully',
        'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);*/
    }

    public function save_newsletter(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newslettters,email',
        ]);

        if ($validator->fails()) {
            $validate = $validator->errors();
            // $message = [
            //     'message' => $validate->first(),
            //     'alert-type' => 'error'
            // ];

            return $validate->first();
        }

        $newletter = new Newslettter();
        $newletter->email = $request->email;
        $newletter->save();

        return "success";
    }

    public function privacy_policy()
    {
        $datas = SliderBanner::find(9);
        $bannerImages = OtherBanner::where('slider_id', 9)->get();
        $privacy = AboutUs::where('type', 18)->first();
        return view('user.others.privacy-policy', compact('privacy', 'datas', 'bannerImages'));
    }

    public function contact_us()
    {
        $datas = SliderBanner::find(1);
        $bannerImages = OtherBanner::where('slider_id', 1)->get();
        return view('user.others.contact-us', compact('datas', 'bannerImages'));
    }

    public function GiveAway(Request $request)
    {
        $datas = SliderBanner::find(8);
        $bannerImages = OtherBanner::where('slider_id', 8)->get();


        $result_array = $this->search_giveaway($request);

        $give_aways = $result_array['give_aways'];
        $give_aways_expied = $result_array['total_count'];
        // $give_aways_expied = GiveAway::get();
        $influencer_reviews = InfluencerReview::where('status', 1)->get();
        // return $influencer_reviews;
        return view('user.others.give-away', compact('give_aways', 'give_aways_expied', 'influencer_reviews', 'datas', 'bannerImages'));
    }


    public function search_giveaway($request)
    {

        $result_array = array();

        $give_aways = GiveAway::where('publish_date', '>=', now())->where('status', '=', '1')->get();
        $give_aways_expied = GiveAway::where('publish_date', '<', now())->where('status', '=', '1')->get();

        if (isset($request)) {

            if (isset($request->sub_cate_id)) { //sub ended

                $attribute = $request->sub_cate_id;
                $give_aways = $give_aways->filter(function ($item) use ($attribute) {
                    return strpos($item->sub_category_id, $attribute) !== false;
                });


                $attribute = $request->sub_cate_id;
                $give_aways_expied = $give_aways_expied->filter(function ($item) use ($attribute) {
                    return strpos($item->sub_category_id, $attribute) !== false;
                });
            } //sucat end here
        }


        $result_array = array(
            'give_aways' => $give_aways,
            'total_count' => $give_aways_expied,
        );

        return $result_array;
    }


    public function giveway_more($slug)
    {


        $data = GiveAway::with('get_subcat', 'city', 'landmarks')->where('slug', $slug)->where('status', '=', '1')->first();
        $count = $data->views + 1;
        $data->views = $count;
        $data->update();

        $giveaway_category = GiveAway::select('sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }

        $landmark_event = json_decode($data->landmarks);

        $give_aways_expied = GiveAway::where('publish_date', '<', now())->where('status', '=', '1')->get();
        $give_aways_upcomings = GiveAway::where('publish_date', '>', now())->where('status', '=', '1')->get();

        return view('user.others.give-away-more', compact('data', 'giveaway_category', 'give_aways_upcomings', 'youtube', 'give_aways_expied', 'landmark_event'));
    }

    public function claim_now_form(Request $request)
    {


        $created_by = 0;

        if (Auth::check()) {
            $created_by = Auth::user()->id;
        } else {

            $message = [
                'review' => 'User not Login',
                'alert-type' => 'error'
            ];

            return redirect()->back()->with($message);
        }


        $enquire = new GiveAwayClaim();
        $enquire->name = $request->name;
        $enquire->email = $request->email;
        $enquire->mobile = $request->full_number;
        $enquire->message = $request->message;
        $enquire->item_type = "App\Models\GiveAway";
        $enquire->item_id = $request->item_id;
        $enquire->created_by = $created_by;
        $enquire->save();


        $message = [
            'message' => 'Claim Sent Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($message);
    }

    public function InfluencerReview(Request $request)
    {
        $influencer_reviews = InfluencerReview::where('status', 1);
        if ($request->main_category_id) {
            $influencer_reviews = $influencer_reviews->where('main_category_id', $request->main_category_id);
        }
        if ($request->quick_search) {
            $search = $request->quick_search;
            $influencer_reviews->where(function ($query) use ($search) {
                $query->orWhere('name', 'LIKE', '%' . $search . '%')->orWhere('company_name', 'LIKE', '%' . $search . '%');
            });
        }
        $influencer_reviews = $influencer_reviews->get();

        $banner = SliderImage::where('major_category_id', 6)->get();
        $major_category = MajorCategory::find(6);
        $main_category = MainCategory::where('major_category_id', 6)->get();
        $datas = SliderBanner::find(14);
        $bannerImages = OtherBanner::where('slider_id', 14)->get();

        $give_aways = GiveAway::where('publish_date', '>=', now())->where('status', '=', '1')->get();
        $give_aways_expied = GiveAway::where('publish_date', '<', now())->where('status', '=', '1')->get();
        // return $give_aways_expied;

        return view('user.others.influencer-review', get_defined_vars());
        // return view('user.others.influencer-review', compact('influencer_reviews', 'banner', 'major_category', 'main_category', 'datas', 'bannerImages','give_aways_expied'));
    }

    public function InfluencerReviewView(Request $request)
    {

        $influencer_reviews = InfluencerReview::where('id', $request->id)->first();
        $count = $influencer_reviews->views + 1;
        $influencer_reviews->views = $count;
        $influencer_reviews->timestamps = false;
        $influencer_reviews->save();
    }

    public function popularPlacesAround(Request $request)
    {

        $events = Events::with('get_subcat', 'city')->join('cities', 'cities.id', 'events.city_id')->where('events.status', '=', '1')->where('cities.is_popular', '=', '1')->get();
        $venues = Venue::with('subCategory', 'city')->join('cities', 'cities.id', 'venues.city_id')->where('venues.status', '=', '1')->where('cities.is_popular', '=', '1')->get();
        $concierges = Concierge::with('subCategory', 'city')->join('cities', 'cities.id', 'concierges.city_id')->where('concierges.status', '=', '1')->where('cities.is_popular', '=', '1')->get();
        if ($request->city_id) {
            $events = $events->where('city_id', $request->city_id);
            $venues = $venues->where('city_id', $request->city_id);
            $concierges = $concierges->where('city_id', $request->city_id);
        }
        $major_category = MajorCategory::find(1);
        $banner = SliderImage::where('major_category_id', 1)->get();

        $popular_types = PopularPlacesTypes::all();
        $datas = SliderBanner::find(14);
        $bannerImages = OtherBanner::where('slider_id', 14)->get();


        $service_types = ServiceTypeTopTrend::all();

        if (isset($request->lat)) {
            $city_name = City::where('id', '=', $request->key)->first();


            $all_events = DB::table("events")
                ->select("events.*", \DB::raw("6371 * acos(cos(radians(" . $request->lat . "))
             * cos(radians(events.lat))
             * cos(radians(events.lng) - radians(" . $request->lng . "))
             + sin(radians(" . $request->lat . "))
             * sin(radians(events.lat))) AS distance"))
                ->where('events.status', '=', '1')
                ->where('events.is_popular', '=', '1')
                ->having('distance', '<', 0.5)
                ->orderby('id', 'desc')
                ->get();


            $top_trends = DB::table("top_trends")
                ->select("top_trends.*", \DB::raw("6371 * acos(cos(radians(" . $request->lat . "))
             * cos(radians(top_trends.lat))
             * cos(radians(top_trends.long) - radians(" . $request->lng . "))
             + sin(radians(" . $request->lat . "))
             * sin(radians(top_trends.lat))) AS distance"))
                ->having('distance', '<', 0.5)
                ->orderby('id', 'desc')
                ->limit(3)
                ->get();


            $events = DB::table("events")
                ->select("events.*", \DB::raw("6371 * acos(cos(radians(" . $request->lat . "))
             * cos(radians(events.lat))
             * cos(radians(events.lng) - radians(" . $request->lng . "))
             + sin(radians(" . $request->lat . "))
             * sin(radians(events.lat))) AS distance"))
                ->where('events.status', '=', '1')
                ->where('events.is_popular', '=', '1')
                ->having('distance', '<', 0.5)
                ->orderby('id', 'desc')
                ->get();

            $events_marker = json_encode($events);

            //  $view = view("user.popular-places-around.ajax-render-all-places", compact('all_events'))->render();
            //  return response()->json(['html' => $view]);

        } else {
            $city_name = null;
            $all_events = null;
            $top_trends = null;
            $events_marker = null;
        }


        // Popular Place Venue

        $venuePopularPlace = PopularPlaceVenue::take(10)->get();
        if ($request->id) {
            $venuePlace = PopularPlaceVenue::with('faq')->where('id', $request->id)->first();
        } else {
            $venuePlace = PopularPlaceVenue::with('faq')->first();
        }


        if ($venuePlace && $venuePlace->lat && $venuePlace->long) {
            $nearby = DB::table("popular_place_venues")
                ->select(
                    "popular_place_venues.id",
                    DB::raw("6371 * acos(cos(radians(" . $venuePlace->lat . "))
                * cos(radians(popular_place_venues.lat))
                * cos(radians(popular_place_venues.long) - radians(" . $venuePlace->long . "))
                + sin(radians(" . $venuePlace->lat . "))
                * sin(radians(popular_place_venues.lat))) AS distance"),
                    "popular_place_venues.*"
                )
                ->orderBy('distance', 'ASC')
                ->take(6)
                ->get();
        } else {
            $nearby = [];
        }

        $ratings = round(PopularPlaceRating::where('pp_id', $venuePlace->id)->avg('rating'), 0);

        return view('user.popular-places-around.index', compact('city_name', 'events_marker', 'top_trends', 'service_types', 'all_events', 'popular_types', 'events', 'venues', 'concierges', 'major_category', 'banner', 'datas', 'bannerImages', 'venuePopularPlace', 'venuePlace', 'nearby', 'ratings'));
    }

    public function popularPlaceRating(Request $request)
    {

        $rating = PopularPlaceRating::where('pp_id', $request->id)->where('user_id', Auth::user()->id)->first();
        if ($rating) {
            $message = [
                "message" => "Already rated",
                "code" => 1
            ];
        } else {
            $obj = new PopularPlaceRating();
            $obj->user_id = Auth::user()->id;
            $obj->rating = $request->rating;
            $obj->pp_id = $request->id;
            $obj->save();

            $message = [
                "message" => "Succesfully Done",
                "code" => 2
            ];
        }

        return response()->json($message, 200);
    }

    public function popularPlaceListType(Request $request)
    {

        $suggestion = PopularPlaceSuggestion::where('pp_id', $request->id)->where('user_id', RequestIp::ip())->first();
        if ($suggestion) {
            $message = [
                "message" => "Already Suggestion Added",
                "code" => 2
            ];
        } else {
            $obj = new PopularPlaceSuggestion();
            $obj->user_id = RequestIp::ip();
            $obj->suggestion_id = $request->value;
            $obj->pp_id = $request->id;
            $obj->save();

            $message = [
                "message" => "Succesfully Done",
                "code" => 1
            ];
        }


        return response()->json($message, 200);
    }

    public function get_around_places_ajax(Request $request)
    {

        if (isset($request->popular_type)) { //if popular type not selected

            if ($request->popular_type == "0") {

                $events = DB::table("events")
                    ->select("events.*", \DB::raw("6371 * acos(cos(radians(" . $request->curent_lat . "))
                * cos(radians(events.lat))
                * cos(radians(events.lng) - radians(" . $request->curent_long . "))
                + sin(radians(" . $request->curent_lat . "))
                * sin(radians(events.lat))) AS distance"))
                    ->where('events.status', '=', '1')
                    ->where('events.is_popular', '=', '1')
                    ->having('distance', '<', $request->distance_radar)
                    ->orderby('id', 'desc')
                    ->get();
                return response()->json($events, 200);
            } else {


                $events = DB::table("events")
                    ->select("events.*", \DB::raw("6371 * acos(cos(radians(" . $request->curent_lat . "))
                * cos(radians(events.lat))
                * cos(radians(events.lng) - radians(" . $request->curent_long . "))
                + sin(radians(" . $request->curent_lat . "))
                * sin(radians(events.lat))) AS distance"))
                    ->where('events.status', '=', '1')
                    ->where('events.is_popular', '=', '1')
                    ->where('events.popular_types', 'LIKE', '%' . $request->popular_type . '%')
                    ->having('distance', '<', $request->distance_radar)
                    ->orderby('id', 'desc')
                    ->get();
                return response()->json($events, 200);
            }
        } else { //if popular type  selected

            $events = DB::table("events")
                ->select("events.*", \DB::raw("6371 * acos(cos(radians(" . $request->curent_lat . "))
            * cos(radians(events.lat))
            * cos(radians(events.lng) - radians(" . $request->curent_long . "))
            + sin(radians(" . $request->curent_lat . "))
            * sin(radians(events.lat))) AS distance"))
                ->where('events.status', '=', '1')
                ->where('events.is_popular', '=', '1')
                ->having('distance', '<', $request->distance_radar)
                ->orderby('id', 'desc')
                ->get();

            // $events  = Events::all();

            return response()->json($events, 200);
        }
    }


    public function render_ajax_places(Request $request)
    {

        if ($request->popular_type == "0") { //if popular type not selected

            $all_events = DB::table("events")
                ->select("events.*", \DB::raw("6371 * acos(cos(radians(" . $request->curent_lat . "))
            * cos(radians(events.lat))
            * cos(radians(events.lng) - radians(" . $request->curent_long . "))
            + sin(radians(" . $request->curent_lat . "))
            * sin(radians(events.lat))) AS distance"))
                ->where('events.status', '=', '1')
                ->where('events.is_popular', '=', '1')
                ->having('distance', '<', $request->distance_radar)
                ->orderby('id', 'desc')
                ->get();

            $view = view("user.popular-places-around.ajax-render-all-places", compact('all_events'))->render();
            return response()->json(['html' => $view]);
        } else { //if popular  selected

            $all_events = DB::table("events")
                ->select("events.*", \DB::raw("6371 * acos(cos(radians(" . $request->curent_lat . "))
            * cos(radians(events.lat))
            * cos(radians(events.lng) - radians(" . $request->curent_long . "))
            + sin(radians(" . $request->curent_lat . "))
            * sin(radians(events.lat))) AS distance"))
                ->where('events.status', '=', '1')
                ->where('events.is_popular', '=', '1')
                ->where('events.popular_types', 'LIKE', '%' . $request->popular_type . '%')
                ->having('distance', '<', $request->distance_radar)
                ->orderby('id', 'desc')
                ->get();

            $view = view("user.popular-places-around.ajax-render-all-places", compact('all_events'))->render();
            return response()->json(['html' => $view]);
        }
    }

    public function render_toptrends_ajax(Request $request)
    {

        if ($request->popular_type == "0") { //if popular type not selected

            $top_trends = DB::table("top_trends")
                ->select("top_trends.*", \DB::raw("6371 * acos(cos(radians(" . $request->curent_lat . "))
        * cos(radians(top_trends.lat))
        * cos(radians(top_trends.long) - radians(" . $request->curent_long . "))
        + sin(radians(" . $request->curent_lat . "))
        * sin(radians(top_trends.lat))) AS distance"))
                ->having('distance', '<', $request->distance_radar)
                ->orderby('id', 'desc')
                ->limit(3)
                ->get();

            $service_types = ServiceTypeTopTrend::all();

            $view = view("user.popular-places-around.top-trends-ajax", compact('top_trends', 'service_types'))->render();
            return response()->json(['html' => $view]);
        } else { //if popular  selected

            $top_trends = DB::table("top_trends")
                ->select("top_trends.*", \DB::raw("6371 * acos(cos(radians(" . $request->curent_lat . "))
            * cos(radians(top_trends.lat))
            * cos(radians(top_trends.long) - radians(" . $request->curent_long . "))
            + sin(radians(" . $request->curent_lat . "))
            * sin(radians(top_trends.lat))) AS distance"))
                ->where('top_trends.popular_type', 'LIKE', '%' . $request->popular_type . '%')
                ->having('distance', '<', $request->distance_radar)
                ->orderby('id', 'desc')
                ->limit(3)
                ->get();

            $service_types = ServiceTypeTopTrend::all();

            $view = view("user.popular-places-around.top-trends-ajax", compact('top_trends', 'service_types'))->render();
            return response()->json(['html' => $view]);
        }
    }


    public function WeeklySuggestion(Request $request)
    {
        $banner = SliderImage::where('major_category_id', 2)->get();
        $monday_events = [];
        $tuesday_events = [];
        $wednesday_events = [];
        $thursday_events = [];
        $friday_events = [];
        $saturday_events = [];
        $sunday_events = [];


        $event_category = Events::where('status', '=', '1')->where('assign_weekly_suggestion', '=', '1')
            ->select('title', 'sub_category_id', DB::raw('count(*) as total'))
            ->groupBy("sub_category_id")
            ->get();

        $events = Events::with('get_subcat', 'city')->where('status', '=', '1')
            ->where(function ($q) {
                $q->whereDate('end_date_time', '>=', Carbon::now())
                    ->orWhere('routine', 1);
            })
            ->where('assign_weekly_suggestion', '=', '1')->get();


        if (isset($request->sub_cate_id)) { //event name

            $attribute = $request->sub_cate_id;
            $events = $events->filter(function ($item) use ($attribute) {
                return strpos($item->sub_category_id, $attribute) !== false;
            });
        }

        $sub_cat_search = array();
        if (isset($request->main_cat)) {
            $sub_cat_search = SubCategory::where('main_category_id', '=', $request->main_cat)->pluck('id');
            $events = $events->whereIn('sub_category_id', $sub_cat_search);
        }


        foreach ($events as $event) {
            $day = date('w', strtotime($event->date_time));

            switch ($day) {
                case "6":
                    array_push($sunday_events, $event);
                    break;
                case "1":
                    array_push($monday_events, $event);
                    break;
                case "2":
                    array_push($tuesday_events, $event);
                    break;
                case "3":
                    array_push($wednesday_events, $event);
                    break;
                case "4":
                    array_push($thursday_events, $event);
                    break;
                case "5":
                    array_push($friday_events, $event);
                    break;
                case "6":
                    array_push($saturday_events, $event);
                    break;
            }
        }
        // return $monday_events;
        $major_category = MajorCategory::find(2);
        $categories = MainCategory::where('major_category_id', 2)->get();
        $hot_trends = News::orderBy('created_at', 'desc')->limit(10)->get();
        $influencer_reviews = InfluencerReview::where('status', 1)->get();
        $datas = SliderBanner::find(12);
        $bannerImages = OtherBanner::where('slider_id', 12)->get();

        return view('user.weekly-suggestion.index', get_defined_vars());
        // return view('user.weekly-suggestion.index', compact('events', 'event_category', 'banner', 'monday_events', 'tuesday_events', 'wednesday_events', 'thursday_events', 'friday_events', 'saturday_events', 'sunday_events', 'categories', 'major_category', 'hot_trends', 'influencer_reviews', 'datas', 'bannerImages'));
    }

    public function client_registor()
    {
        return view('user.others.client_registor');
    }

    public function client_registor_save(Request $request)
    {

        $auth = Auth::user();
        $user = User::where('id', $auth->id)->first();
        $user->company_name = $request->company_name;
        $user->address = $request->address;
        $user->mobile_no = $request->mobile_no;
        $user->save();
        return redirect($this->redirectTo1);
    }

    public function contact_us_store(Request $request)
    {
        $response = [];
        try {
            $this->validate($request, [
                'full_name' => 'required',
                'email' => 'required',
                'mobile' => 'required',
                'discription' => 'required',
                'g-recaptcha-response' => 'required'//|recaptchav3:fld_recaptcha,0.5'
            ]);
            $obj = new ContactUs();
            $obj->full_name = $request->full_name;
            $obj->email = $request->email;
            $obj->mobile_number = $request->mobile_number;
            $obj->discription = $request->discription;
            $obj->status = 0;
            $obj->save();
            $details = [
                'full_name' => $request->full_name,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'discription' => $request->discription
            ];
            /*$email = 'creativeali2022@gmail.com';
            \Mail::to($email)->send(new ContactUsMail($details));*/
            $response['error'] = false;
            $response['msg'] = 'Thank you for submitting your details. Our team will contact you soon!!';
        } catch (Exception $ex) {
            $response['error'] = true;
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
            $response['msg'] = 'There was some problem while processing your request. Please try later.';
        }
        return json_encode($response);

        //return redirect()->back()->with('message', 'Contact Requested Successfully!');
    }

    public function terms_and_conditions()
    {
        $datas = SliderBanner::find(10);
        $bannerImages = OtherBanner::where('slider_id', 10)->get();
        $privacy = AboutUs::where('type', 18)->first();
        $terms = AboutUs::where('type', 19)->first();
        return view('user.others.terms-and-conditions', compact('terms', 'datas', 'bannerImages'));
    }

    public function about_us()
    {
        $who_are = AboutUs::where('type', 1)->first();
        $mission = AboutUs::where('type', 2)->first();
        $vision = AboutUs::where('type', 3)->first();
        $what_do = AboutUs::where('type', 4)->first();
        $we_are = AboutUs::where('type', 5)->first();
        $why_us = AboutUs::where('type', 6)->first();
        $venue = AboutUs::where('type', 7)->first();
        $event = AboutUs::where('type', 8)->first();
        $buy_sell = AboutUs::where('type', 9)->first();
        $directory = AboutUs::where('type', 10)->first();
        $concierge = AboutUs::where('type', 11)->first();
        $influencers = AboutUs::where('type', 12)->first();
        $jobs = AboutUs::where('type', 13)->first();
        $spaces = AboutUs::where('type', 14)->first();
        $meet_up = AboutUs::where('type', 15)->first();
        $tickets = AboutUs::where('type', 16)->first();
        $attachment = AboutUs::where('type', 20)->first();
        $datas = SliderBanner::find(2);
        $bannerImages = OtherBanner::where('slider_id', 2)->get();
        return view(
            'user.others.about-us',
            compact(
                'who_are',
                'mission',
                'vision',
                'what_do',
                'we_are',
                'why_us',
                'venue',
                'event',
                'buy_sell',
                'directory',
                'concierge',
                'influencers',
                'jobs',
                'spaces',
                'meet_up',
                'tickets',
                'attachment',
                'datas',
                'bannerImages'
            )
        );
    }

    public function careers()
    {
        $datas = SliderBanner::find(3);
        $bannerImages = OtherBanner::where('slider_id', 3)->get();
        $career = AboutUs::where('type', 17)->first();
        return view('user.others.careers', compact('career', 'datas', 'bannerImages'));
    }

    public function faqs()
    {
        $datas = SliderBanner::find(5);
        $bannerImages = OtherBanner::where('slider_id', 5)->get();
        $faqs = FaqsQuestionAndAnswer::all();
        $faqs_cat = FaqsCategory::all();
        return view('user.others.faqs', compact('faqs', 'faqs_cat', 'datas', 'bannerImages'));
    }

    //----------front end


    public function city_guide()
    {
        $city_guide = AboutUs::where('type', 21)->first();
        return view('user.others.city_guide', compact('city_guide'));
    }

    public function report_fraude()
    {
        $datas = SliderBanner::find(6);
        $bannerImages = OtherBanner::where('slider_id', 6)->get();
        $report_fraude = AboutUs::where('type', 22)->first();
        return view('user.others.report_fraude', compact('report_fraude', 'datas', 'bannerImages'));
    }

    public function cookie_policy()
    {
        $datas = SliderBanner::find(7);
        $bannerImages = OtherBanner::where('slider_id', 7)->get();
        $cookie_policy = AboutUs::where('type', 23)->first();
        return view('user.others.cookie_policy', compact('cookie_policy', 'datas', 'bannerImages'));
    }

    public function investor_relation()
    {
        $datas = SliderBanner::find(3);
        $bannerImages = OtherBanner::where('slider_id', 4)->get();
        $investor_relation = AboutUs::where('type', 24)->first();
        return view('user.others.investor_relation', compact('investor_relation', 'datas', 'bannerImages'));
    }

    public function gallery(Request $request)
    {
        $galleries = Gallery::with('subCategory', 'city')->where('active', '=', '1')->orderby('id', 'DESC')->get();
        if ($request->sub_category) {
            $galleries = $galleries->where('sub_category_id', $request->sub_category);
        }
        // return $galleries;

        return view('user.others.gallery', compact('galleries'));
    }

    public function blogs(Request $request)
    {
        // $blogs = Blog::orderby('id', 'desc')->get();  //return $blogs;
        $blogs = Blog::active();
        $datas = SliderBanner::find(3);
        $bannerImages = OtherBanner::where('slider_id', 4)->get();
        $justJoin = $blogs->take($request->get('limit', 10))->orderby('created_at', 'desc');
        // dd($blogs);

        $category = $request->main_cat;
        if ($category != '') {
            $blogs = $blogs->where('blog_category_id', $category);
        }
        $tag = $request->tag;
        if ($tag != '') {
            $blogs = $blogs->where('tags', "LIKE", '%' . $tag . '%');
        }
        $keyword = $request->quick_search;
        if ($keyword != '') {
            // $blogs = $blogs->where('title',$keyword);
            $blogs = $blogs->where('title', 'like', '%' . $keyword . '%');
        }
        $value = $request->value;
        if ($value == 'feature') {
            $blogs = $blogs->where('is_featured', '1');
        } elseif ($value == 'popular') {
            $blogs = $blogs->where('is_popular', '1');
        }
        $shortby = $request->shortby;
        if ($shortby == "newtoold") {
            $blogs = $blogs->orderby('id', 'desc');
        } elseif ($shortby == "oldtonew") {
            $blogs = $blogs->orderby('id', 'asc');
        } else {
            $blogs = $blogs->latest();
        }
        $blogs = $blogs->paginate(5);
        $main_category = BlogCategory::all();
        $main_tag = TagBlog::all();
        // $dynamic_sub_categories = DynamicSubCategory::all();
        // $dynamic_categories = DynamicMainCategory::where('major_category_id', '=', '1')->get();

        $cities = City::all();
        $states = State::all();
        return view('user.others.blogs', get_defined_vars());
    }

    public function more_data(Request $request)
    {
        if ($request->ajax()) {
            $skip = $request->skip;
            $take = 5;
            $blogs = Blog::skip($skip)->take($take)->get();
            return view('user.others.blog-list', compact('blogs'));
        } else {
            return response()->json('Direct Access Not Allowed!!');
        }
    }

    public function blogDetails($slug)
    {
        // $blogs = Blog::where('seo_url', $slug)->first();
        $blog = Blog::where('slug', $slug)->first();

        $count = $blog->views + 1;

        $blog->views = $count;
        $blog->timestamps = false;
        $blog->save();
        $blogs_all = Blog::all();

        $array_blogs = json_decode($blog->blog_category_id);
        $array_tags = explode(",", $blog->tags);
        // $array_tags = json_decode($blog->tags);

        // dd($array_tags);
        $category_names = BlogCategory::where('id', $array_blogs)->get();
        $tag_names = TagBlog::whereIn('id', $array_tags)->get();
        $main_category = BlogCategory::all();
        $tagblog = TagBlog::all();

        $justJoin = null;
        return view('user.others.blog-details', compact('blog', 'blogs_all', 'category_names', 'tag_names', 'main_category', 'tagblog'));
    }


    public function blogByCategory(Request $request, $category)
    {

        $name_cat = BlogCategory::where('slug', $category)->first();

        $blogs = Blog::where('blog_category_id', $name_cat->id);
        $datas = SliderBanner::find(3);
        $bannerImages = OtherBanner::where('slider_id', 4)->get();
        $justJoin = $blogs->take($request->get('limit', 10))->orderby('created_at', 'desc');
        $category = $request->main_cat;
        if ($category != '') {
            $blogs = $blogs->where('blog_category_id', $category);
        }
        $keyword = $request->quick_search;
        if ($keyword != '') {
            // $blogs = $blogs->where('title',$keyword);
            $blogs = $blogs->where('title', 'like', '%' . $keyword . '%');
        }
        $value = $request->value;
        if ($value == 'feature') {
            $blogs = $blogs->where('is_featured', '1');
        } elseif ($value == 'popular') {
            $blogs = $blogs->where('is_popular', '1');
        }
        $shortby = $request->shortby;
        if ($shortby == "newtoold") {
            $blogs = $blogs->orderby('id', 'desc');
        } elseif ($shortby == "oldtonew") {
            $blogs = $blogs->orderby('id', 'asc');
        } else {
            $blogs = $blogs->latest();
        }
        $blogs = $blogs->paginate(5);
        // $blogs = Blog::where('blog_category_id', "LIKE", '%' . $name_cat->id . '%')->get();
        $main_category = BlogCategory::all();
        $main_tag = TagBlog::all();
        $cities = City::all();
        $states = State::all();
        // dd($blogs);

        return view('user.others.blogs', get_defined_vars());
    }

    public function blogsByTag(Request $request, $category)
    {

        $name_cat = TagBlog::where('slug', $category)->first();

        $blogs = Blog::where('tags', "LIKE", '%' . $name_cat->id . '%');
        $datas = SliderBanner::find(3);
        $bannerImages = OtherBanner::where('slider_id', 4)->get();
        $justJoin = $blogs->take($request->get('limit', 10))->orderby('created_at', 'desc');
        $tag = $request->tag;
        if ($tag != '') {
            $blogs = $blogs->where('tags', "LIKE", '%' . $tag . '%');
        }
        $keyword = $request->quick_search;
        if ($keyword != '') {
            // $blogs = $blogs->where('title',$keyword);
            $blogs = $blogs->where('title', 'like', '%' . $keyword . '%');
        }
        $value = $request->value;
        if ($value == 'feature') {
            $blogs = $blogs->where('is_featured', '1');
        } elseif ($value == 'popular') {
            $blogs = $blogs->where('is_popular', '1');
        }
        $shortby = $request->shortby;
        if ($shortby == "newtoold") {
            $blogs = $blogs->orderby('id', 'desc');
        } elseif ($shortby == "oldtonew") {
            $blogs = $blogs->orderby('id', 'asc');
        } else {
            $blogs = $blogs->latest();
        }
        $blogs = $blogs->paginate(5);

        $main_tag = TagBlog::all();
        $main_category = BlogCategory::all();
        $cities = City::all();
        $states = State::all();
        // dd($blogs);

        return view('user.others.blogs', get_defined_vars());
    }


    public function inboundSeo()
    {
        $blogs = InboundSeo::all();
        return view('user.others.inbound-seo', compact('blogs'));
    }

    public function inboundSeoMore($slug)
    {
        $blogs = InboundSeo::where('slug', $slug)->first();

        $count = $blogs->views + 1;

        $blogs->views = $count;
        $blogs->timestamps = false;
        $blogs->save();
        $blogs_all = Blog::all();

        return view('user.others.inbound-seo-more', compact('blogs', 'blogs_all'));
    }

    public function news()
    {
        $news = News::all();

        return view('user.others.news', compact('news'));
    }


    public function gallery_view_more($slug)
    {
        $gallery = Gallery::where('slug', $slug)->first();
        $count = $gallery->views + 1;
        $gallery->views = $count;
        $gallery->timestamps = false;
        $gallery->save();

        return view('user.others.gallery-more', compact('gallery'));
    }

    public function sitemap()
    {
        $sitemap = Sitemap::create()
            ->add(url::create('/about-us'))
            ->add(Url::create('/privacy-policy'))
            ->add(Url::create('/terms-and-conditions'))
            ->add(Url::create('/careers'))
            ->add(Url::create('/faqs'))
            ->add(Url::create('/faqs'))
            ->add(Url::create('/contact-us'))
            ->add(Url::create('/city_guide'))
            ->add(Url::create('/report_fraude'))
            ->add(Url::create('/cookie_policy'))
            ->add(Url::create('/investor_relation'))
            ->add(Url::create('/register-job-seeker'))
            ->add(Url::create('/events'))
            ->add(Url::create('/buy-and-sells'))
            ->add(Url::create('/directory'))
            ->add(Url::create('/concierge'))
            ->add(Url::create('/influencers'))
            ->add(Url::create('/jobs'))
            ->add(Url::create('/accommodation'))
            ->add(Url::create('/venue'));

        $venues = Venue::all();
        foreach ($venues as $row) {
            $sitemap->add(Url::create("/venue/{$row->slug}"));
        }

        $events = Events::all();
        foreach ($events as $row) {
            $sitemap->add(Url::create("/event/{$row->slug}"));
        }

        $buys = BuySell::all();
        foreach ($buys as $row) {
            $sitemap->add(Url::create("/buy-and-sell/{$row->slug}"));
        }

        $directories = Directory::all();
        foreach ($directories as $row) {
            $sitemap->add(Url::create("/directory/{$row->slug}"));
        }

        $concierges = Concierge::all();
        foreach ($concierges as $row) {
            $sitemap->add(Url::create("/concierge/{$row->slug}"));
        }

        $influencers = Influencer::all();
        foreach ($influencers as $row) {
            $sitemap->add(Url::create("/influencers/{$row->slug}"));
        }

        $jobs = Job::all();
        foreach ($jobs as $row) {
            $sitemap->add(Url::create("/job-details/{$row->slug}"));
        }

        $tickets = Tickets::all();
        foreach ($tickets as $row) {
            $sitemap->add(Url::create("/tickets/{$row->slug}"));
        }

        $spaces = Accommodation::all();
        foreach ($spaces as $row) {
            $sitemap->add(Url::create("/accommodation/{$row->slug}"));
        }

        $book_artists = BookArtist::all();
        foreach ($book_artists as $row) {
            $sitemap->add(Url::create("/book-artist-more/{$row->slug}"));
        }

        $attractions = Attraction::all();
        foreach ($attractions as $row) {
            $sitemap->add(Url::create("/attraction-detail/{$row->slug}"));
        }

        $galleries = Gallery::all();
        foreach ($galleries as $row) {
            $sitemap->add(Url::create("/gallery-view-more/{$row->slug}"));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }


    public function save_wish_list(Request $request)
    {
        // return $request->all();

        if (Auth::check()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = 1;
            return "1";
            exit;
            //user not login
        }

        if ($request->major_category == 1) {
        }


        $app_model_name = "";

        $wisht_list = new WishListDetails(['status' => "1", 'created_by' => $user_id]);
        if ($request->major_category == 1) {
            $type = Venue::find($request->item_id);

            $list_name = "venue";
            $created_by_id = $type->created_by;

            $app_model_name = "Venue";
        } elseif ($request->major_category == 2) {
            $type = Events::find($request->item_id);

            $list_name = "event";
            $created_by_id = $type->created_by;

            $app_model_name = 'events';
        } elseif ($request->major_category == 3) {
            $type = BuySell::find($request->item_id);

            $list_name = "buy & sell";
            $created_by_id = $type->created_by;

            $app_model_name = "BuySell";
        } elseif ($request->major_category == 4) {
            $type = Directory::find($request->item_id);

            $list_name = "directory";
            $created_by_id = $type->created_by;

            $app_model_name = "directory";
        } elseif ($request->major_category == 5) {
            $type = Concierge::find($request->item_id);

            $list_name = "concierge";
            $created_by_id = $type->created_by;

            $app_model_name = "Concierge";
        } elseif ($request->major_category == 6) {
            $type = Influencer::find($request->item_id);

            $list_name = "influencer";
            $created_by_id = $type->created_by;

            $app_model_name = "Influencer";
        } elseif ($request->major_category == 7) {
            $type = Job::find($request->item_id);

            $list_name = "job";
            $created_by_id = $type->created_by;

            $app_model_name = "Jobs";
        } elseif ($request->major_category == 9) {
            $type = Accommodation::find($request->item_id);

            $list_name = "accommodation";
            $created_by_id = $type->created_by;


            $app_model_name = "Accommodation";
        } elseif ($request->major_category == 10) {
            $type = Attraction::find($request->item_id);
            $list_name = "attraction";
            $created_by_id = $type->created_by;

            $app_model_name = "Attraction";
        } elseif ($request->major_category == 16) {
            $type = Attraction::find($request->item_id);
            $list_name = "crypto";
            $created_by_id = $type->created_by;

            $app_model_name = "Crypto";
        } elseif ($request->major_category == 17) {
            $type = Talents::find($request->item_id);
            $list_name = "talent";
            $created_by_id = $type->created_by;

            $app_model_name = "Crypto";
        }    
        elseif ($request->major_category == 12) {
            $type = GiveAway::find($request->item_id);
            $list_name = "GiveAways";
            $created_by_id = $type->created_by;
            $app_model_name = "GiveAway";
        } elseif ($request->major_category == 13) {
            $type = Motors::find($request->item_id);
            $list_name = "Motors";
            $created_by_id = $type->created_by;
            $app_model_name = "Motors";
        } elseif ($request->major_category == 14) {
            $type = Education::find($request->item_id);
            $list_name = "Education";
            $created_by_id = $type->created_by;
            $app_model_name = "Education";
        } elseif ($request->major_category == 15) {
            $type = It::find($request->item_id);
            $list_name = "IT";
            $created_by_id = $type->created_by;
            $app_model_name = "It";
        } elseif ($request->major_category == "gallery") {
            $type = Gallery::find($request->item_id);
            $list_name = "Gallery";
            $created_by_id = $type->created_by;
            $app_model_name = "Gallery";
        } elseif ($request->major_category == "recommend") {
            $type = ItemRecommendation::find($request->item_id);
            $list_name = "ItemRecommendation";
            // $created_by_id = $type->created_by;
            $app_model_name = "ItemRecommendation";
        } elseif ($request->major_category == "news") {
            $type = News::find($request->item_id);
            $list_name = "News";
            // $created_by_id = $type->created_by;
            $app_model_name = "News";
        }


        $already_wishlist = WishListDetails::where('item_id', '=', $request->item_id)
            ->where('status', '=', '1')
            ->where('created_by', '=', $user_id)
            ->where('item_type', 'LIKE', '%' . $app_model_name . '%')
            ->first();


        if ($already_wishlist != null) {
            return "2";
            exit;
            //already exist
        }


        $url_now_client = route('publisher.wishlists');

        $type->wishlists()->save($wisht_list);

        $url_now_admin = route('admin.wishlists');


        $this->send_notfication_wishlist_to_admin_and_publisher($list_name, $url_now_admin, "0", 0);

        if ($type->is_publisher == "1") {
            $this->send_notfication_wishlist_to_admin_and_publisher($list_name, $url_now_client, "1", $type->created_by);
        }


        return "3";
        exit;
    }


    public function send_notfication_to_admin_and_publisher($category_from, $url_now, $to_whom, $created_by = null)
    {

        if (!isset($created_by)) {
            $created_by = 0;
        }

        $description_event = "Enquiry submitted";
        $message_event = "New query From " . $category_from;
        $url_now = $url_now;

        event(new MyEvent($message_event, $description_event, $url_now, $to_whom, $created_by));

        $notification = new NotificationsInfo();
        $notification->title = $message_event;
        $notification->description = $description_event;
        $notification->notification_for = $to_whom;
        $notification->url = $url_now;
        $notification->notify_to = $created_by;
        $notification->save();
    }


    public function send_notfication_review_to_admin_and_publisher($category_from, $url_now, $to_whom, $created_by = null)
    {

        if (!isset($created_by)) {
            $created_by = 0;
        }

        $description_event = "Review submitted";
        $message_event = "New review From " . $category_from;
        $url_now = $url_now;

        event(new MyEvent($message_event, $description_event, $url_now, $to_whom, $created_by));

        $notification = new NotificationsInfo();
        $notification->title = $message_event;
        $notification->description = $description_event;
        $notification->notification_for = $to_whom;
        $notification->url = $url_now;
        $notification->notify_to = $created_by;
        $notification->save();
    }


    public function send_notfication_wishlist_to_admin_and_publisher($category_from, $url_now, $to_whom, $created_by = null)
    {

        if (!isset($created_by)) {
            $created_by = 0;
        }

        $description_event = "Added to Wishlist";
        $message_event = "Added to wishlist From " . $category_from;
        $url_now = $url_now;

        event(new MyEvent($message_event, $description_event, $url_now, $to_whom, $created_by));

        $notification = new NotificationsInfo();
        $notification->title = $message_event;
        $notification->description = $description_event;
        $notification->notification_for = $to_whom;
        $notification->url = $url_now;
        $notification->notify_to = $created_by;
        $notification->save();
    }

    public function recommendationSave(Request $request)
    {
        try {
            $response = [];
            $this->validate($request, [
                'g-recaptcha-response' => 'required'//|recaptchav3:fld_recaptcha,0.5'
            ]);
            DB::beginTransaction();

            $rec = new Recommendation();

            $module_id = $request->module_id;
            $module_type = $request->module_type;
            if (Auth::check()) {
                $rec->user_id = Auth::user()->id;
            }

            $rec->module_id = $module_id;
            $rec->module_type = $module_type;

            if ($request->hasfile('rphoto')) {

                $featured = rand(100, 100000) . '.' . time() . '.' . $request->rphoto->extension();
                $rec->photo_file = $featured;
                $youtubeImagePath = config('app.upload_other_path') . $featured;
                Storage::disk('s3')->put($youtubeImagePath, file_get_contents($request->rphoto));
            }
            if ($request->hasfile('rvideo')) {

                $featured = rand(100, 100000) . '.' . time() . '.' . $request->rvideo->extension();
                $rec->video_file = $featured;
                $youtubeImagePath = config('app.upload_other_path') . $featured;
                Storage::disk('s3')->put($youtubeImagePath, file_get_contents($request->rvideo));
            }
            $rec->vide_link = $request->vlink;
            $rec->comments = $request->rmessage;

            $rec->created_at = date('Y-m-d H:i:s');
            $rec->save();
            DB::commit();
            //$message = ['message' => 'Data Send Successfully', 'alert-type' => 'success'];

            $response['error'] = false;
            $response['msg'] = 'Thank you for submitting your details. Our team will contact you soon!!';
        } catch (Exception $ex) {
            DB::rollback();
            $response['error'] = true;
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
            $response['msg'] = 'There was some problem while processing your request. Please try later.';
        }
        return json_encode($response);
    }

    public function careerSave(Request $request)
    {
        try {
            $response = [];
            $this->validate($request, [
                'g-recaptcha-response' => 'required'//|recaptchav3:fld_recaptcha,0.5'
            ]);
            DB::beginTransaction();

            $career = new Career();

            $module_id = $request->module_id;
            $module_type = $request->module_type;
            if (Auth::check()) {
                $career->user_id = Auth::user()->id;
            }
            $career->module_id = $module_id;
            $career->module_type = $module_type;
            $career->name = $request->cname;
            $career->email = $request->cemail;
            $career->position_name = $request->cposition;

            if ($request->hasfile('cfile')) {

                $featured = rand(100, 100000) . '.' . time() . '.' . $request->cfile->extension();
                $menuPath = config('app.upload_other_path') . $featured;
                Storage::disk('s3')->put($menuPath, file_get_contents($request->cfile));
                $fileFullPath = $featured;
                $career->cv_path = $fileFullPath;
            }
            $career->created_at = date('Y-m-d H:i:s');
            $career->save();
            DB::commit();
            $message = ['message' => 'Data Send Successfully', 'alert-type' => 'success'];
            $response['error'] = false;
            $response['msg'] = 'Thank you for submitting your details. Our team will contact you soon!!';
        } catch (Exception $ex) {
            DB::rollback();
            $response['error'] = true;
            $this->log()->error($ex->getMessage());
            $this->log()->error($ex->getTraceAsString());
            $response['msg'] = 'There was some problem while processing your request. Please try later.';
        }
        return json_encode($response);
    }
}
