<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttractionEvent extends Model
{
    protected $guarded = [];
    protected $table = "attraction_events";
    protected $fillable = ['attraction_id', 'name', 'datetime'];

    public function attraction()
    {
        return $this->belongsTo(Attraction::class);
    }
}
