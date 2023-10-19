<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentImages extends Model
{
    use HasFactory;

    protected $fillable = [
        'talent_id', 'image_type', 'image', 'alt_texts'
    ];

    protected $casts = [
        'alt_texts' => 'array',
    ];

    public function talent()
    {
        return $this->belongsTo(Talents::class, 'talent_id');
    }
}
