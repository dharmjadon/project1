<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class VenueReservation extends Model
{
    //

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function getBookingTypeAttribute($value) {
        if($value == 1) {
            return "Table";
        }
        elseif($value == 2) {
            return "Ticket";
        }
        elseif($value == 3) {
            return "Guestlist";
        }
            return "N/A";
    }
}
