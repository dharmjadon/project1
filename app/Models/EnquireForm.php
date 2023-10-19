<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EnquireForm extends Model
{
    protected $guarded = [];
    public function item()
    {
        return $this->morphTo();
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function getDateAndTimeAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function getBuySellType(){
        return $this->hasOne(EnquireTypeBuySell::class);
    }
}
