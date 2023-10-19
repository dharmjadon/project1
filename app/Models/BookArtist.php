<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
class BookArtist extends Model
{
    protected $guarded = [];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function storedImage($img)
    {
        return Storage::disk('s3')->url(config('app.upload_book_artist_path') . $img);
    }

    public function category()
    {
        return $this->belongsTo(MainCategory::class,'category_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function enquiries()
    {
        return $this->morphMany(EnquireForm::class, 'item');
    }

    public function clickCount()
    {
        return $this->morphMany(CountClick::class, 'product');
    }

    public function clickCountWhatsapp()
    {
        return $this->morphMany(CountClick::class, 'product')->where('type_of_click','=', 1);;
    }
    public function clickCountEmail()
    {
        return $this->morphMany(CountClick::class, 'product')->where('type_of_click','=', 2);;
    }
    public function clickCountPhone()
    {
        return $this->morphMany(CountClick::class, 'product')->where('type_of_click','=', 3);;
    }

    public function getTitleAttribute()
    {
        return $this->name;
    }
}
