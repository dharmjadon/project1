<?php

namespace App\Models;

use App\Models\DynamicMainCategory;
use Illuminate\Database\Eloquent\Model;

class DynamicSubCategory extends Model
{
    public function mainCategory()
    {
        return $this->belongsTo(DynamicMainCategory::class);
    }
}
