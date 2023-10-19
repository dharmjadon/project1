<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogComments extends Model
{
    //

    use  SoftDeletes;

    public function postdetail(){
        return $this->belongsTo(Blog::class,'blog_id');
    }
}
