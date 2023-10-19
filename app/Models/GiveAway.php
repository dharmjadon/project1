<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GiveAway extends Model
{
    //
    public function enquiries()
    {
        return $this->morphMany(EnquireForm::class, 'item');
    }

    public function storedImage($img)
    {
        return Storage::disk('s3')->url(config('app.upload_giveaway_path') . $img);
    }

    public function claim()
    {
        return $this->morphMany(GiveAwayClaim::class, 'item');
    }

    public function get_subcat(){

        return $this->belongsTo(SubCategory::class,'sub_category_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class)->withDefault();
    }

    public function landmarks()
    {
        return $this->morphMany(Landmarkable::class, 'landmarkable');
    }

    public function wishlists(){
        return $this->morphMany(WishListDetails::class, 'item');
    }



}
