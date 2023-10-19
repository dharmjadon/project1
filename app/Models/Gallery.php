<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    protected $guarded = [];
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
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
