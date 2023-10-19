<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    //

    public $table = "news";
    protected $fillable = ['views'];


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function getPublishDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function wishlists(){
        return $this->morphMany(WishListDetails::class, 'item');
    }

    public function storedImage($img)
    {
        if(Storage::disk('s3')->exists(config('app.upload_other_path') . $img)) {
            return Storage::disk('s3')->url(config('app.upload_other_path') . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }

}
