<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Review extends Model
{
    protected $guarded = [];
    public function reviewable()
    {
        return $this->morphTo();
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function getAccommodationTypeAttribute($value) {
        if($value == 1) {
            return "Party";
        }
        elseif($value == 2) {
            return "Office";
        }
        elseif($value == 3) {
            return "Accommodation";
        }
            return "N/A";
    }
}
