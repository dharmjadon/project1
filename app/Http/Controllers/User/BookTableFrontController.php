<?php

namespace App\Http\Controllers\User;

use App\Models\BookTable;
use App\Models\AlertNews;
use App\Models\SliderImage;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\State;

class BookTableFrontController extends Controller
{
    public function index(Request $request, $category_slug = '')
    {
        $result_array = $this->search_result($request, $category_slug);

        $book_tables = $result_array['book_tables'];
        $book_table_category = $result_array['book_table_category'];
        $featured = $result_array['featured'];

        $main_category = MainCategory::where('major_category_id', '=', '11')->get();

        $sub_cat_search = array();

        // $categoryForSidebar = MainCategory::with('subCategory.book_table')->withCount('book_table')->where('major_category_id', 11)->orderBy('book_table_count', 'DESC')->take(10)->get();

        $banner = SliderImage::where('major_category_id', 11)->first();

        $justJoin = [];

        if (!empty($category_slug) || !empty($request->main_cat)) {
            return view('user.book-table.book-table-listing', get_defined_vars());
        }

        return view('user.book-table.index', get_defined_vars());
    }

    public function search_result($request, $category_slug)
    {
        $result_array = array();
        $book_tables = BookTable::with('get_category')->where('status', 1)->get();
        $book_table_category = BookTable::where('status', '=', '1')->select('category_id', DB::raw('count(*) as total'))
            ->groupBy("category_id")->get();

        $featured = BookTable::with('get_category')->where('assign_featured', 1)->where('status', 1)->get();

        if (!empty($category_slug)) {
            $main_category = MainCategory::where('slug', $category_slug)->first();
            if ($main_category) {
                $request->main_cat = $main_category->id;
            }
        }

        if (isset($request)) {
            if (isset($request->quick_search)) {

                $search = $request->quick_search;
                $book_tables = BookTable::with('get_category')
                    ->where(function ($q) use ($search) {
                        $q->orWhere('title', 'LIKE', '%' . $search . '%')->orWhere('description', 'LIKE', '%' . $search . '%');
                    })->where('status', 1)->get();
                $featured = [];
            }

            if (isset($request->name)) {

                $attribute = $request->name;
                $book_tables = $book_tables->filter(function ($item) use ($attribute) {
                    return strpos($item->title, $attribute) !== false;
                });
                $featured = [];
            }

            if ($request->main_cat != "all" && isset($request->main_cat)) {
                $book_tables =  $book_tables->where('category_id', $request->main_cat);
                $book_table_category =  $book_table_category->whereIn('category_id', $request->main_cat);
                $featured = [];
            }
        }
        $total_count = 0;
        if (isset($book_tables)) {
            $total_count = count($book_tables);
        }
        $result_array = array(
            'book_tables' => $book_tables,
            'book_table_category' => $book_table_category,
            'featured' => $featured,
            'bk_table_count' => $total_count
        );
        return $result_array;
    }

    function search_concierge_name_ajax(Request $request)
    {
        $search = $request->keyword;
        $book_tables = array();
        if (!empty($search)) {
            $book_tables = BookTable::select('title', 'id')->where('title', 'LIKE', '%' . $search . '%')->orderby('title')->limit(6)->get();
        }
        $view = view('user.book-table.autocomplete_concierge_ajax', compact('book_tables'))->render();
        return response()->json(['html' => $view]);
    }

    public function book_table_details($slug)
    {
        $data = BookTable::with('get_category')->where('slug', $slug)->first();
        $book_tables = BookTable::get();
        $similar = BookTable::with('get_category', 'city')->where('category_id', $data->category_id)->get();
        if ($data->lat && $data->long) {
            $nearby = DB::table("book_tables")
                ->select(
                    "book_tables.id",
                    DB::raw("6371 * acos(cos(radians(" . $data->lat . "))
                * cos(radians(book_tables.lat))
                * cos(radians(book_tables.long) - radians(" . $data->long . "))
                + sin(radians(" . $data->lat . "))
                * sin(radians(book_tables.lat))) AS distance"),
                    "book_tables.*",
                    "main_categories.name as category"
                )
                // ->with('get_category')
                ->join('main_categories', 'main_categories.id', 'book_tables.category_id')
                ->groupBy("book_tables.id")
                ->orderBy('distance', 'ASC')
                ->get();
        } else {
            $nearby = [];
        }
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data->video, $match)) {
            $youtube = $match[1];
        } else {
            $youtube = '';
        }

        $count = $data->views + 1;
        $data->update([
            'views' => $count
        ]);
        return view('user.book-table.book-table-more', get_defined_vars());
    }

    public function conciergeSearch(Request $request)
    {
        if ($request->sort) {
            $res = '1';
            $sort_by = $request->sort_by;

            $featured = BookTable::with('get_category')->where('featured', 1)->where('status', 1);
            if ($sort_by == '1') {
                $featured = $featured->orderBy('created_at', 'desc');
            } elseif ($sort_by == '2') {
                $featured = $featured->orderBy('created_at', 'asc');
            } elseif ($sort_by == '3') {
                $featured = $featured->orderBy('title', 'desc');
            } elseif ($sort_by == '4') {
                $featured = $featured->orderBy('title', 'asc');
            }
            $featured = $featured->get();

            $datas = BookTable::with('get_category')->where('status', 1);
            if ($sort_by == '1') {
                $datas = $datas->orderBy('created_at', 'desc');
            } elseif ($sort_by == '2') {
                $datas = $datas->orderBy('created_at', 'asc');
            } elseif ($sort_by == '3') {
                $datas = $datas->orderBy('title', 'desc');
            } elseif ($sort_by == '4') {
                $datas = $datas->orderBy('title', 'asc');
            }
            $book_tables = $datas->get();


            $categories = MainCategory::where('major_category_id', 1)->get();

            $main = MainCategory::where('major_category_id', 11)->pluck('id')->toArray();

            $concierge_category = BookTable::where('status', '=', '1')
                ->select('category_id', DB::raw('count(*) as total'))
                ->groupBy("category_id")
                ->get();

            $main_category = MainCategory::where('major_category_id', '=', '5')->get();


            $banner = SliderImage::where('major_category_id', 5)->first();

            return view('user.book-table.index', compact('book_tables', 'featured', 'banner', 'concierge_category', 'categories', 'subs', 'sort_by', 'res', 'main_category', 'sub_cat_search', 'dynamic_mains'));
        }
    }
}