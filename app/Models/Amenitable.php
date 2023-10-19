<?php

namespace App\Models;

use App\Models\Venue;
use App\Models\Accommodation;
use App\Models\Amenties;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Amenitable extends Model
{
    protected $guarded = [];

    public function amenitable()
    {
        return $this->morphTo();
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function amenity()
    {
        return $this->belongsTo(Amenties::class);
    }

    public function venues()
    {
        return $this->morphedByMany(Venue::class, 'amenitable');
    }
	public function accommodations()
    {
        return $this->morphedByMany(Accommodation::class, 'amenitable');
    }
}
