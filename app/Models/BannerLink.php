<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerLink extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['major_category_id', 'banner_position', 'banner_title', 'banner_text', 'banner_image', 'url'];

    protected $casts = [
        'related_items' => 'array'
    ];

    public function majorCategory()
    {
        return $this->belongsTo(MajorCategory::class);
    }
}
