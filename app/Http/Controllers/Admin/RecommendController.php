<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RecommendController extends Controller
{
    public function writeRecommendation($id){
        $datas = Venue::where('id', $id)->first();
        return view('admin.recommend.venue', compact('datas'));
    }

    public function writeEventRecommendation($id){
        $datas = Events::where('id', $id)->first();
        return view('admin.recommend.event', compact('datas'));
    }

    public function writeEducationRecommendation($id){
        $datas = Education::where('id', $id)->first();
        return view('admin.recommend.education', compact('datas'));
    }

    public function writeBuysellRecommendation($id){
        $datas = BuySell::where('id', $id)->first();
        return view('admin.recommend.buysell', compact('datas'));
    }

    public function writeDirectoryRecommendation($id){
        $datas = Directory::where('id', $id)->first();
        return view('admin.recommend.directory', compact('datas'));
    }

    public function writeConciergeRecommendation($id){
        $datas = Concierge::where('id', $id)->first();
        return view('admin.recommend.concierge', compact('datas'));
    }

    public function writeAccommodationRecommendation($id){
        $datas = Accommodation::where('id', $id)->first();
        return view('admin.recommend.accommodation', compact('datas'));
    }
    public function writeAttractionRecommendation($id){
        $datas = Attraction::where('id', $id)->first();
        return view('admin.recommend.attraction', compact('datas'));
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
                Storage::disk('s3')->put($imagePath, file_get_contents($file));
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
            $type = Events::find($request->item_id);
        }
        if($request->major_category == 14) {
            $type = Education::find($request->item_id);
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

    public function listRecommendation(){
        $datas = ItemRecommendation::get();
        return view('admin.recommend.list-recommendation', compact('datas'));
    }

    public function delet_list_recommend(Request $request){


         $recommend = ItemRecommendation::find($request->id);
         $recommend->delete();

        $message = [
            'message' => 'Recommended list deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($message);

    }

    public function updateRecommendation(Request $request){
        $obj = ItemRecommendation::find($request->id);
        $obj->status = $request->status;
        $obj->save();
    }
}
