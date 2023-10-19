<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    //
    use  SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'name',
        'slug',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'canonical_url',
    ];


    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

}
