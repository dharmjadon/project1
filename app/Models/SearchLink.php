<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLink extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = ['major_category_id', 'link_name', 'link_position', 'url'];

    function category()
    {
        return $this->belongsTo(MajorCategory::class);
    }
}
