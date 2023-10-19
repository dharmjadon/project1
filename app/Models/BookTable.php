<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class BookTable extends Model
{
    protected $guarded = [];
    public function get_category(){

        return $this->belongsTo(MainCategory::class,'category_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function enquiries()
    {
        return $this->morphMany(EnquireForm::class, 'item');
    }

    public function city()
    {
        return $this->belongsTo(State::class);
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
}
