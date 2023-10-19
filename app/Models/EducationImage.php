<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'education_id', 'image_type', 'image', 'alt_texts'
    ];

    protected $casts = [
        'alt_texts' => 'array',
    ];

    public function ecucation()
    {
        return $this->belongsTo(Education::class, 'education_id');
    }
}
