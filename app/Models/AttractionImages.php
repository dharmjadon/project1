<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttractionImages extends Model
{
    use HasFactory;

    protected $fillable = [
        'attraction_id', 'image_type', 'image', 'alt_texts'
    ];

    protected $casts = [
        'alt_texts' => 'array',
    ];

    public function attraction()
    {
        return $this->belongsTo(Attraction::class, 'attraction_id');
    }
}
