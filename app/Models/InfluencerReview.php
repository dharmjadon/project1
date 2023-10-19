<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class InfluencerReview extends Model
{
    //
    public $table = "influencer_reviews";
    protected $fillable = ['views'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function getPublishDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }



    public function publish_by(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function storedImage($img)
    {
        if(Storage::disk('s3')->exists(config('app.upload_other_path') . $img)) {
            return Storage::disk('s3')->url(config('app.upload_other_path') . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }


}
