<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class GiveAwayClaim extends Model
{
    //
    protected $guarded = [];
    public function enquiries()
    {
        return $this->morphMany(EnquireForm::class, 'item');
    }
    public function claim()
    {
        return $this->morphMany(EnquireForm::class, 'item');
    }
    public function giveaway()
    {
        return $this->belongsTo(GiveAway::class, 'item_id');
    }

    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by');
    }

}
