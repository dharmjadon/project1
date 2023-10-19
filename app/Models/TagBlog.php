<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagBlog extends Model
{
    //
    use  SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];


    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}
