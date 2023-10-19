<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueEvent extends Model
{
    protected $guarded = [];

    protected $fillable = ['venue_id', 'name', 'datetime'];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
