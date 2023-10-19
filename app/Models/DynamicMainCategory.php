<?php

namespace App\Models;

use App\Models\MajorCategory;
use App\Models\DynamicSubCategory;
use Illuminate\Database\Eloquent\Model;

class DynamicMainCategory extends Model
{
    public function MajorCategory()
    {
        return $this->belongsTo(MajorCategory::class);
    }

    public function SubCategories()
    {
        return $this->hasMany(DynamicSubCategory::class,'main_category_id');
    }
}
