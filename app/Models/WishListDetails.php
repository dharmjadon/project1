<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class WishListDetails extends Model
{
    //

    protected $guarded = [];
    public function item()
    {
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

}
