<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FaqsCategory;

class FaqsQuestionAndAnswer extends Model
{
    //
    public function faqs_cate_name()
    {
        return $this->belongsTo(FaqsCategory::class,'faqs_category');
    }

}
