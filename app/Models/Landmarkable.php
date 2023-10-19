<?php

namespace App\Models;

use App\Models\Landmark;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Landmarkable extends Model
{
    protected $guarded = [];

    public function landmarkable()
    {
        return $this->morphTo();
    }

    public function venues()
    {
        return $this->morphedByMany(Venue::class, 'landmarkable');
    }
    public function accommodations()
    {
        return $this->morphedByMany(Accommodation::class, 'landmarkable');
    }
    public function landmarks()
    {
        return $this->belongsTo(Landmark::class, 'landmark_id');
    }
    public function landmark()
    {
        return $this->belongsTo(Landmark::class, 'landmark_id');
    }

    public function storedImage($img)
    {
        if(Storage::disk('s3')->exists(config('app.upload_other_path') . $img)) {
            return Storage::disk('s3')->url(config('app.upload_other_path') . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }
}
