<?php

namespace App\Models;

use App\Models\State;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function states()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city_events(){
        return $this->hasMany(Events::class,'city_id');
    }
    public function city_venues(){
        return $this->hasMany(Venue::class,'city_id');
    }
    public function city_concierges(){
        return $this->hasMany(Concierge::class,'city_id');
    }
}
