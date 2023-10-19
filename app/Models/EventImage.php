<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'image_type', 'image', 'alt_texts'
    ];

    protected $casts = [
        'alt_texts' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_id');
    }
}
