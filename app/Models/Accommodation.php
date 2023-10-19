<?php

namespace App\Models;

use App\Models\Amenitable;
use App\Models\SubCategory;
use App\Models\Landmarkable;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Accommodation extends Model
{
    // protected $guarded = [];
    // protected $casts = [
    //     'stories' => 'array',
    // ];
    // protected $attributes = [
    //     'stories' => [],
    // ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function storedImage($img)
    {
        return Storage::disk('s3')->url(config('app.upload_accommodation_path') . $img);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function publish_by(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function wishlists(){
        return $this->morphMany(WishListDetails::class, 'item');
    }

    public function recommend()
    {
        return $this->morphMany(ItemRecommendation::class, 'item');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function amenities()
    {
        return $this->morphMany(Amenitable::class, 'amenitable');
    }
    public function amenities_amenity()
    {
        return $this->morphMany(Amenitable::class, 'amenitable')->with('amenity');
    }

    public function landmarks()
    {
        return $this->morphMany(Landmarkable::class, 'landmarkable');
    }
    public function landmarks_with_landmark()
    {
        return $this->morphMany(Landmarkable::class, 'landmarkable')->with('landmark');
    }

    public function enquiries()
    {
        return $this->morphMany(EnquireForm::class, 'item');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function approvedReviews()
    {
        return $this->morphMany(Review::class, 'reviewable')->where('approved','=', 1);
    }

    public function clickCount()
    {
        return $this->morphMany(CountClick::class, 'product');
    }

    public function clickCountWhatsapp()
    {
        return $this->morphMany(CountClick::class, 'product')->where('type_of_click','=', 1);;
    }
    public function clickCountEmail()
    {
        return $this->morphMany(CountClick::class, 'product')->where('type_of_click','=', 2);;
    }
    public function clickCountPhone()
    {
        return $this->morphMany(CountClick::class, 'product')->where('type_of_click','=', 3);;
    }

    public function getAccommodationTypeNameAttribute($value) {
        if($this->accommodation_type == 1) {
            return "Party";
        }
        elseif($this->accommodation_type == 2) {
            return "Office";
        }
        elseif($this->accommodation_type == 3) {
            return "Accommodation";
        }
        elseif($this->accommodation_type == 4) {
            return "Buy";
        }
        elseif($this->accommodation_type == 5) {
            return "Rent";
        }
            return "N/A";
    }


}
