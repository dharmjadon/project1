<?php

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use App\Models\City;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class It extends Model
{
    protected $guarded = [];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getDateAndTimeAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    /**
     * Get the sub_category that owns the It
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function publish_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the city that owns the It
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function storedImage($img)
    {
        if (Str::contains($img, 'uploads/it')) {
            return Storage::disk('s3')->url($img);
        }
        return Storage::disk('s3')->url(config('app.upload_it_path') . $img);
    }

    /**
     * Get the subCategory that owns the It
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function wishlists(){
        return $this->morphMany(WishListDetails::class, 'item');
    }

    public function recommend()
    {
        return $this->morphMany(ItemRecommendation::class, 'item');
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
        return $this->morphMany(CountClick::class, 'it');
    }

    public function clickCountWhatsapp()
    {
        return $this->morphMany(CountClick::class, 'it')->where('type_of_click','=', 1);;
    }
    public function clickCountEmail()
    {
        return $this->morphMany(CountClick::class, 'it')->where('type_of_click','=', 2);;
    }
    public function clickCountPhone()
    {
        return $this->morphMany(CountClick::class, 'it')->where('type_of_click','=', 3);;
    }
}
