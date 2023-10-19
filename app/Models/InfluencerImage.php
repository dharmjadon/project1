<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfluencerImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'influencer_id',
        'image_type',
        'image',
        'alt_texts'
    ];

    protected $casts = [
        'alt_texts' => 'array',
    ];

    public function influencer()
    {
        return $this->belongsTo(Influencer::class, 'influencer_id');
    }
}