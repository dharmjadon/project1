<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleStatistic extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['major_category_id', 'stat_name', 'stat_value'];

    function category()
    {
        return $this->belongsTo(MajorCategory::class);
    }
}
