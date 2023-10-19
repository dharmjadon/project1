<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventReservation extends Model
{
    public function event()
    {
        return $this->belongsTo(Events::class);
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
