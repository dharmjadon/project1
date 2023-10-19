<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SubCategory extends Model
{
    protected $guarded = [];

    protected $with = ['mainCategory'];

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class, 'main_category_id');
    }

    public function get_subcat_gallery()
    {
        return $this->hasMany(Gallery::class, 'sub_category_id');
    }

    public function attraction()
    {
        return $this->hasMany(Attraction::class)->where('status', 1);
    }

    public function attractionThings()
    {
        return $this->hasMany(Attraction::class)->where('status', 1)->where('attraction_type', 1);
    }

    public function attractionPopular()
    {
        return $this->hasMany(Attraction::class)->where('status', 1)->where('attraction_type', 2);
    }

    public function venue()
    {
        return $this->hasMany(Venue::class)->where('status', 1);
    }

    public function crypto()
    {
        return $this->hasMany(Crypto::class)->where('status', 1);
    }

    public function education()
    {
        return $this->hasMany(Education::class)->where('status', 1);
    }

    public function event()
    {
        return $this->hasMany(Events::class)->where('status', 1);
    }

    public function talent()
    {
        return $this->hasMany(Talents::class)->where('status', 1);
    }

    public function buysells()
    {
        return $this->hasMany(BuySell::class, 'sub_category_id')->where('status', 1);
    }

    public function directory()
    {
        return $this->hasMany(Directory::class)->where('status', 1);
    }

    public function concierge()
    {
        return $this->hasMany(Concierge::class)->where('status', 1);
    }

    public function influencer()
    {
        return $this->hasMany(Influencer::class)->where('status', 1);
    }

    public function job()
    {
        return $this->hasMany(Job::class)->where('status', 1);
    }

    public function ticket()
    {
        return $this->hasMany(Tickets::class)->where('status', 1);
    }

    public function accommodation()
    {
        return $this->hasMany(Accommodation::class)->where('status', 1);
    }

    public function bookArtist()
    {
        return $this->hasMany(BookArtist::class, 'category_id')->where('status', 1);
    }


    public function it()
    {
        return $this->hasMany(It::class)->where('status', 1);
    }

    public function storedImage($img)
    {
        if(Storage::disk('s3')->exists(config('app.upload_other_path') . $img)) {
            return Storage::disk('s3')->url(config('app.upload_other_path') . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }


}
