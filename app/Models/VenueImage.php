<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id', 'image_type', 'image', 'alt_texts'
    ];

    protected $casts = [
      'alt_texts' => 'array'
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
