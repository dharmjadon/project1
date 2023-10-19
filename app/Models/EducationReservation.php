<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EducationReservation extends Model
{
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function education()
    {
        return $this->belongsTo(Education::class);
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
