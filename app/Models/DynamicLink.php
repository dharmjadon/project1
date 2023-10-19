<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicLink extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['major_category_id', 'link_title', 'link_image', 'slug', 'related_items', 'meta_title', 'meta_description', 'meta_tags'];

    protected $casts = [
        'related_items' => 'array'
    ];

    public function majorCategory()
    {
        return $this->belongsTo(MajorCategory::class);
    }
}
