<?php

namespace App\Http\Controllers\User;

use DB;
use App\Models\City;
use App\Models\News;
use App\Models\State;
use App\Models\Gallery;
use App\Models\BookArtist;
use App\Models\Influencer;
use App\Models\SliderImage;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\MajorCategory;
use App\Models\HomeTrendBanner;
use App\Models\InfluencerReview;
use App\Models\DynamicMainCategory;
use App\Http\Controllers\Controller;

class BookArtistController extends Controller
{

    public function book_artist_view(Request $request)
    {


        $banner = SliderImage::where('major_category_id', 11)->get();
        $book_artist_categories = MainCategory::where('major_category_id', 11);

        if($request->quick_search){
            $search = $request->quick_search;
            $book_artist_categories->where(function ($query) use ($search) {
                $query->orWhere('name','LIKE','%'. $search .'%');
            });
        }

        if($request->main_category_id){
            $book_artist_categories->where('id', $request->main_category_id);
        }
        $book_artist_categories = $book_artist_categories->get();
        $mainCategory = MainCategory::where('major_category_id', 11)->get();

        $major_category = MajorCategory::find(11);

        $justJoin = $book_artist_categories->take($request->get('limit', 10))->sortByDesc('created_at');

        return view('user.book-artist.book-artist', get_defined_vars());

        //return view('user.book-artist.book-artist-category', compact('banner', 'book_artist_categories','major_category', 'mainCategory'));
    }


    public function index(Request $request)
    {
        $result_array = $this->search_result($request);
        $book_artists = $result_array['book_artists'];


        $main_category = MainCategory::where('major_category_id', '=', '11')->get();

        $banner = SliderImage::where('major_category_id', 11)->get();
        $states = State::all();
        $major_category = MajorCategory::find(11);
        $hot_trends = News::orderBy('created_at', 'desc')->limit(10)->get();
        $influencer_reviews = InfluencerReview::where('status',1)->get();

        return view('user.book-artist.index', compact('banner', 'major_category', 'book_artists', 'main_category', 'states','hot_trends','influencer_reviews'));
    }
    public function book_artist_index_select(Request $request)
    {
        $book_artists = BookArtist::with('category')->where('category_id', 191)->where('status', 1)->get();
        $main_category = MainCategory::where('major_category_id', '=', '11')->get();

        $banner = SliderImage::where('major_category_id', 11)->get();
        $states = State::all();
        $major_category = MajorCategory::find(11);
        $hot_trends = News::orderBy('created_at', 'desc')->limit(10)->get();
        $influencer_reviews = InfluencerReview::where('status',1)->get();

        return view('user.book-artist.index', compact('banner', 'major_category', 'book_artists', 'main_category', 'states','hot_trends','influencer_reviews'));
   }
    public function book_artist_index($cat_id, Request $request)
    {

        $book_artists = BookArtist::with('category')->where('category_id', $cat_id)->where('status', 1);
        $main_category = MainCategory::where('major_category_id', '=', '11')->get();
        $book_artists = $this->filter($request, $book_artists);

        $banner = SliderImage::where('major_category_id', 11)->get();
        $states = State::all();
        $major_category = MajorCategory::find(11);
        $hot_trends = News::orderBy('created_at', 'desc')->limit(3)->get();
        $influencer_reviews = InfluencerReview::where('status',1)->limit(3)->get();
        $categoryForSidebar = MainCategory::with('subCategory.bookArtist')->withCount('bookArtist')->where('major_category_id', 11)->orderBy('book_artist_count', 'DESC')->take(10)->get();
        $bookArtist_category = BookArtist::where('status', '=' , '1')->select('category_id', DB::raw('count(*) as total'));
        $allCategory = MajorCategory::with(['mainCategory','mainCategory.subCategory']);
        $home_trend = HomeTrendBanner::all();


        return view('user.book-artist.index', compact('banner', 'major_category', 'book_artists', 'main_category', 'states','hot_trends', 'influencer_reviews', 'categoryForSidebar', 'bookArtist_category', 'home_trend'));
    }

    public function search_result($request)
    {
        $result_array = array();
        $book_artists = BookArtist::with('category')->where('status', 1);


        if (isset($request)) {
            if (isset($request->quick_search)) {

                $search = $request->quick_search;
                // $main_ids = MainCategory::where('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();
                $book_artists = BookArtist::with('category')
                    ->where(function ($q) use ($search) {
                        $q->orWhere('name', 'LIKE', '%' . $search . '%')->orWhere('description', 'LIKE', '%' . $search . '%');
                            // ->orWhereIn('category_id', $main_ids);
                    })->where('status', 1)->get();
            }
            if ($request->category != "all" && isset($request->category)) {

                $book_artists =  $book_artists->whereIn('category_id', $request->category);

            }

            if ($request->city != "all" && isset($request->city)) {
                $city_ids = City::where('state_id', $request->city)->pluck('id')->toArray();
                $book_artists =  $book_artists->whereIn('city_id', $city_ids);

            }

            if(isset($request->sort_by)){
                if($request->sort_by == "1"){
                    $book_artists =  $book_artists->orderBy('created_at', 'desc');
                }
                if($request->sort_by == "2"){
                    $book_artists =  $book_artists->orderBy('created_at', 'asc');
                }
                if($request->sort_by == "3"){
                    $book_artists =  $book_artists->orderBy('name', 'asc');
                }
                if($request->sort_by == "4"){
                    $book_artists =  $book_artists->orderBy('name', 'desc');
                }
            }
        }

        $book_artists =  $book_artists->get();

        $result_array = array(
            'book_artists' => $book_artists
        );
        return $result_array;
    }

    public function filter($request, $book_artists)
    {
        if(isset($request->sort_by)){
            if($request->sort_by == "1"){
                $book_artists =  $book_artists->orderBy('created_at', 'desc');
            }
            if($request->sort_by == "2"){
                $book_artists =  $book_artists->orderBy('created_at', 'asc');
            }
            if($request->sort_by == "3"){
                $book_artists =  $book_artists->orderBy('name', 'asc');
            }
            if($request->sort_by == "4"){
                $book_artists =  $book_artists->orderBy('name', 'desc');
            }
        }

        return $book_artists =  $book_artists->get();
    }

    public function book_artistMore($slug,Request $request)
    {
        $data = BookArtist::with('category')->where('slug', $slug)->first();
        $artistCategories = MainCategory::where('major_category_id', '=', '11')->get();
        $type = 'Book Artist';
        $count = $data->views + 1;
        $data->views= $count;
        $data->timestamps = false;
        $data->save();
        $influencers = Influencer::with('sub_category')->where('status',1)->get();
        $influencerCat = Influencer::where('status', '=', '1')->select('sub_category_id', DB::raw('count(*) as total'))
        ->groupBy("sub_category_id")->orderBy('total', 'desc')
        ->get();
        $galleries =  Gallery::where('active', '=', '1')->orderby('id', 'DESC')->get();
        $justJoin = $artistCategories->take($request->get('limit', 10))->sortByDesc('created_at');

        // return $data;
        return view('user.book-artist.book-artist-more', get_defined_vars());
    }

    public function book_artistSearch(Request $request)
    {
        if ($request->sort) {
            $res = '1';
            $sort_by = $request->sort_by;

            $datas = BookArtist::with('category')->where('status', 1);
            if ($sort_by == '1') {
                $datas = $datas->orderBy('created_at', 'desc');
            } elseif ($sort_by == '2') {
                $datas = $datas->orderBy('created_at', 'asc');
            } elseif ($sort_by == '3') {
                $datas = $datas->orderBy('title', 'desc');
            } elseif ($sort_by == '4') {
                $datas = $datas->orderBy('title', 'asc');
            }
            $book_artists = $datas->get();

            $categories = MainCategory::where('major_category_id', 11)->get();

            $main = MainCategory::where('major_category_id', 11)->pluck('id')->toArray();
            $subs = SubCategory::whereIn('main_category_id', $main)->get();
            $main_category = MainCategory::where('major_category_id', '=', '11')->get();
            $banner = SliderImage::where('major_category_id', 11)->first();
            $hot_trends = News::orderBy('created_at', 'desc')->limit(10)->get();
            $influencer_reviews = InfluencerReview::where('status',1)->get();

            return view('user.concierge.index', compact('book_artists',  'banner', 'categories', 'sort_by', 'res', 'main_category','hot_trends','influencer_reviews'));
        }
    }
}
