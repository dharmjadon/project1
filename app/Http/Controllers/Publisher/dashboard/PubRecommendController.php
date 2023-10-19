<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Models\Venue;
use App\Models\Events;
use App\Models\BuySell;
use App\Models\Concierge;
use App\Models\Directory;
use App\Models\Attraction;
use App\Models\Accommodation;
use Illuminate\Http\Request;
use App\Models\ItemRecommendation;
use App\Http\Controllers\Controller;
use App\Models\Motors;
use App\Models\Recommendation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PubRecommendController extends Controller
{
    public function writeRecommendation($id){
        $datas = Venue::where('id', $id)->first();
        return view('publisher.recommend.venue', compact('datas'));
    }

    public function writeEventRecommendation($id){
        $datas = Events::where('id', $id)->first();
        return view('publisher.recommend.event', compact('datas'));
    }

    public function writeBuysellRecommendation($id){
        $datas = BuySell::where('id', $id)->first();
        return view('publisher.recommend.buysell', compact('datas'));
    }
    public function writeDirectoryRecommendation($id){
        $datas = Directory::where('id', $id)->first();
        return view('publisher.recommend.directory', compact('datas'));
    }
    public function writeConciergeRecommendation($id){
        $datas = Concierge::where('id', $id)->first();
        return view('publisher.recommend.concierge', compact('datas'));
    }
    public function writeAccommodationRecommendation($id){
        $datas = Accommodation::where('id', $id)->first();
        return view('publisher.recommend.accommodation', compact('datas'));
    }
    public function writeAttractionRecommendation($id){
        $datas = Attraction::where('id', $id)->first();
        return view('publisher.recommend.attraction', compact('datas'));
    }

    public function recommendation(Request $request){

        $id = Auth::user()->id;
        $space = Accommodation::where('created_by', $id)->pluck('id')->toArray();
        $motor = Motors::where('created_by', $id)->where('created_by', $id)->pluck('id')->toArray();
        $venue = Venue::where('created_by', $id)->pluck('id')->toArray();
        $events = Events::where('created_by', $id)->pluck('id')->toArray();
        $buySell = BuySell::where('created_by', $id)->pluck('id')->toArray();
        $directory = Directory::where('created_by', $id)->pluck('id')->toArray();
        $concierge = Concierge::where('created_by', $id)->pluck('id')->toArray();

        $venue_data = Recommendation::with('venueModule')->whereIn('module_id', $venue)->where('module_type', 'venue')
            ->orderBy('id', 'DESC')->get();

        $event_data = Recommendation::with('eventModule')->whereIn('module_id', $events)->where('module_type', 'event')
            ->orderBy('id', 'DESC')->get();

        $buysell_data = Recommendation::with('buySellModule')->whereIn('module_id', $buySell)->where('module_type', 'buysell')
            ->orderBy('id', 'DESC')->get();

        $directory_data = Recommendation::with('directoryModule')->whereIn('module_id', $directory)->where('module_type', 'directory')
            ->orderBy('id', 'DESC')->get();

        $concierge_data = Recommendation::with('conciergeModule')->whereIn('module_id', $concierge)->where('module_type', 'concierge')
            ->orderBy('id', 'DESC')->get();

        $space_data = Recommendation::with('spaceModule')->whereIn('module_id', $space)->where('module_type', 'space')
            ->orderBy('id', 'DESC')->get();

        $motor_data = Recommendation::with('motorsModule')->whereIn('module_id', $motor)->where('module_type', 'motor')
            ->orderBy('id', 'DESC')->get();

        $venue_data = (!empty($venue_data)) ? $venue_data : NULL;
        $event_data = (!empty($event_data)) ? $event_data : NULL;
        $buysell_data = (!empty($buysell_data)) ? $buysell_data : NULL;
        $directory_data = (!empty($directory_data)) ? $directory_data : NULL;
        $concierge_data = (!empty($concierge_data)) ? $concierge_data : NULL;
        $space_data = (!empty($space_data)) ? $space_data : NULL;
        $motor_data = (!empty($motor_data)) ? $motor_data : NULL;

        return view('publisher.recommendation.index', get_defined_vars());

    }

    public function recommendation_ajax_tabs_table(Request $request){

        $id = Auth::user()->id;
        $type  = $request->module_name;
        if($request->module_name=="venue"){

            $venue = Venue::where('created_by', $id)->pluck('id')->toArray();
            $data = Recommendation::with('venueModule')->whereIn('module_id', $venue)->where('module_type', 'venue')
            ->orderBy('id', 'DESC')->get();
            $view = view('publisher.recommendation.ajax_recommend_tabs_table',compact('data','type'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="event"){

            $events = Events::where('created_by', $id)->pluck('id')->toArray();
            $data = Recommendation::with('eventModule')->whereIn('module_id', $events)->where('module_type', 'event')
            ->orderBy('id', 'DESC')->get();
            $view = view('publisher.recommendation.ajax_recommend_tabs_table',compact('data','type'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="buysell"){

            $buySell = BuySell::where('created_by', $id)->pluck('id')->toArray();
            $data = Recommendation::with('buySellModule')->whereIn('module_id', $buySell)->where('module_type', 'buysell')
            ->orderBy('id', 'DESC')->get();

            $view = view('publisher.recommendation.ajax_recommend_tabs_table',compact('data','type'))->render();
            return response()->json(['html'=>$view]);
        }elseif($request->module_name=="directory"){

            $directory = Directory::where('created_by', $id)->pluck('id')->toArray();
            $data = Recommendation::with('directoryModule')->whereIn('module_id', $directory)->where('module_type', 'directory')
            ->orderBy('id', 'DESC')->get();

            $view = view('publisher.recommendation.ajax_recommend_tabs_table',compact('data','type'))->render();
            return response()->json(['html'=>$view]);
        }elseif($request->module_name=="concierge"){

            $concierge = Concierge::where('created_by', $id)->pluck('id')->toArray();
            $data = Recommendation::with('conciergeModule')->whereIn('module_id', $concierge)->where('module_type', 'concierge')
            ->orderBy('id', 'DESC')->get();

            $view = view('publisher.recommendation.ajax_recommend_tabs_table',compact('data','type'))->render();
            return response()->json(['html'=>$view]);
        }elseif($request->module_name=="accomdation"){


            $space = Accommodation::where('created_by', $id)->pluck('id')->toArray();
            $data = Recommendation::with('spaceModule')->whereIn('module_id', $space)->where('module_type', 'space')
            ->orderBy('id', 'DESC')->get();

            $view = view('publisher.recommendation.ajax_recommend_tabs_table',compact('data','type'))->render();
            return response()->json(['html'=>$view]);

        }elseif($request->module_name=="motor"){


            $motor = Motors::where('created_by', $id)->where('created_by', $id)->pluck('id')->toArray();

            $data = Recommendation::with('motorsModule')->whereIn('module_id', $motor)->where('module_type', 'motor')
            ->orderBy('id', 'DESC')->get();

            $view = view('publisher.recommendation.ajax_recommend_tabs_table',compact('data','type'))->render();
            return response()->json(['html'=>$view]);

        }



    }

    public function storeRecommendation(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
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

        $featureImage = '';
        if($request->feature_img) {
            $featureImage = rand(100,100000).'.'.time(). '.'. $request->feature_img->extension();
            $imagePath = config('app.upload_other_path') . $featureImage;
            Storage::disk('s3')->put($imagePath, file_get_contents($request->feature_img));
        }

        $otherImage = [];
        if($request->other_img) {
            foreach($request->other_img as $file) {
                $img = rand(100,100000).'.'.time(). '.'. $file->extension();
                $imagePath = config('app.upload_other_path') . $img;
                Storage::disk('s3')->put($imagePath, file_get_contents($request->other_img));
                $otherImage[] = $img;
            }
        }

        $obj = new ItemRecommendation();
        $obj->title = $request->title;
        $obj->description = $request->description;
        $obj->feature_image = $featureImage;
        $obj->other_image = json_encode($otherImage);

        if($request->major_category == 1) {
            $type = Venue::find($request->item_id);
        }
        if($request->major_category == 2) {
            $type= Events::find($request->item_id);
        }
        if($request->major_category == 3) {
            $type = BuySell::find($request->item_id);
        }
        if($request->major_category == 4) {
            $type = Directory::find($request->item_id);
        }
        if($request->major_category == 5) {
            $type = Concierge::find($request->item_id);
        }
        if($request->major_category == 9) {
            $type = Accommodation::find($request->item_id);
        }
        if($request->major_category == 10) {
            $type = Attraction::find($request->item_id);
        }

        $type->recommend()->save($obj);

        $message = [
            'message' => 'Recommendation Added Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);
    }
}
