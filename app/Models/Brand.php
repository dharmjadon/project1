<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
    protected $guarded = [];

    protected $fillable = ['title', 'slug', 'logo', 'alt_title', 'description','meta_title', 'meta_description', 'meta_tags', 'status'
    ];


    public function storedImage($img)
    {
        return Storage::disk('s3')->url(config('app.upload_education_path') . $img);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function publish_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
