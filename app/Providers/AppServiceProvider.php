<?php

namespace App\Providers;

use App\Models\Attraction;
use App\Models\ItemRecommendation;
use App\Models\News;
use View;
use App\Models\Banner;
use App\Models\SocialMedia;
use App\Models\MainCategory;
use App\Models\MajorCategory;
use App\Models\NotificationsInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultStringLength(191);

        //footer banner
        view()->composer('user.layout.footer', function ($view) {
            //$footer_banners = Banner::where('banner_type', 3)->get();
            $social = SocialMedia::all();
            $view->with('social', $social);
        });

        // Header
        view()->composer('user.layout.header', function ($view) {
            $venue = MainCategory::with('subCategory')->where('major_category_id', 1)->take(6)->get();
            $event = MainCategory::with('subCategory')->where('major_category_id', 2)->take(6)->get();
            $buy_sell = MainCategory::with('subCategory')->where('major_category_id', 3)->take(6)->get();
            $directory = MainCategory::with('subCategory')->where('major_category_id', 4)->take(6)->get();
            $concierge = MainCategory::with('subCategory')->where('major_category_id', 5)->take(6)->get();
            $influencer = MainCategory::with('subCategory')->where('major_category_id', 6)->take(6)->get();
            $job = MainCategory::with('subCategory')->where('major_category_id', 7)->take(6)->take(6)->get();
            $ticket = MainCategory::with('subCategory')->where('major_category_id', 8)->get()->take(6);
            $accommodation = MainCategory::with('subCategory')->where('major_category_id', 9)->take(6)->get();
            $attraction = MainCategory::with('subCategory')->where('major_category_id', 10)->take(6)->get();

            $main_category = compact('venue', 'event', 'buy_sell', 'directory', 'concierge', 'influencer', 'job', 'ticket', 'accommodation', 'attraction');
            $view->with('main_category', $main_category);
        });
        view()->composer([
            'user.venue.venue-listing', 'user.buysell.buysell-listing',
            'user.attractions.attraction-listing', 'user.crypto.crypto-listing',
            'user.directory.directory-listing', 'user.education.education-listing',
            'user.event.event-listing', 'user.influencers.influencer-listing',
            'user.jobs.job-listing', 'user.talent.talent-listing', 'new-landing-page',
            'user.home.index', 'user.home.main', 'user.others.blogs', 'user.jobs.jobs-more',
            'user.talents.jobs-more'
            ],
            function ($view) {
            $social = SocialMedia::all();
            $browseCategories = MajorCategory::select(['id', 'name', 'slug'])->with(['mainCategory','mainCategory.subCategory'])->get();
            $fewNews = News::select(['id', 'title', 'slug', 'feature_image'])->latest()->limit(4)->get();
            $fewRecommendations = ItemRecommendation::latest()->limit(4)->get();
            $fewAttractions = Attraction::latest()->limit(4)->get();
            // $major = compact('majorCategory');
            $view->with('browseCategories', $browseCategories)
                ->with('fewNews', $fewNews)
                ->with('fewRecommendations', $fewRecommendations)
                ->with('fewAttractions', $fewAttractions)
                ->with('social', $social);
        });

        // Publisher Header
        view()->composer('publisher.layout.app', function ($view) {
            //$majorCategory = MajorCategory::get();
            $majorCategory = MajorCategory::with(['mainCategory', 'mainCategory.subCategory'])->get();
            // $major = compact('majorCategory');
            $view->with('major', $majorCategory);
        });
        // dd(Auth::check());
        # Share to all view
        view()->composer('*', function ($view) {
            $url_event = url()->current();

            if (Auth::check()) {
                $logged_in_user = Auth::user();
                if ($logged_in_user->user_type == "1") {

                    NotificationsInfo::where('url', '=', $url_event)
                        ->where('read_status', '=', '0')
                        ->where('notify_to', '=', '0')
                        ->where('notification_for', '=', '0')
                        ->update(['read_status' => '1']);

                    $admin_notifcation_count = NotificationsInfo::where('read_status', '=', '0')->where('notification_for', '=', '0')->count();
                    View::share('admin_notifcation_count', $admin_notifcation_count);
                } else {

                    NotificationsInfo::where('url', '=', $url_event)
                        ->where('notify_to', '=', $logged_in_user->id)->update(['read_status' => '1']);

                    $admin_notifcation_count = NotificationsInfo::where('read_status', '=', '0')->where('notify_to', '=', Auth::user()->id)->count();
                    View::share('admin_notifcation_count', $admin_notifcation_count);
                }

            }
        });

    }
}
