<?php

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ConciergeReservation extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function concierge()
    {
        return $this->belongsTo(Concierge::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }
}
