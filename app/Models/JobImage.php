<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'image_type',
        'image',
        'alt_texts'
    ];

    protected $casts = [
        'alt_texts' => 'array',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}