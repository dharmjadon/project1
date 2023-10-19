<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id', 'image_type', 'image', 'alt_text_en', 'alt_text_ar', 'alt_text_zh'
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
