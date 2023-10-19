<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MainCategory extends Model
{
    protected $guarded = [];
    public function MajorCategory()
    {
        return $this->belongsTo(MajorCategory::class);
    }

    public function subCategory()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function topSearch()
    {
        return $this->hasMany(SubCategory::class)->where('is_top_search', 1);
    }

    public function popularSearch()
    {
        return $this->hasMany(SubCategory::class)->where('is_popular_search', 1);
    }

    public function venue()
    {
        return $this->hasManyThrough(Venue::class, SubCategory::class)->where('venues.status', 1);
    }

    public function attraction()
    {
        return $this->hasManyThrough(Attraction::class, SubCategory::class)->where('attractions.status', 1);
    }

    public function attractionThings()
    {
        return $this->hasManyThrough(Attraction::class, SubCategory::class)->where('attractions.status', 1)->where('attraction_type', 1);
    }

    public function attractionPopular()
    {
        return $this->hasManyThrough(Attraction::class, SubCategory::class)->where('attractions.status', 1)->where('attraction_type', 2);
    }

    public function crypto()
    {
        return $this->hasManyThrough(Crypto::class, SubCategory::class)->where('coins.status', 1);
    }

    public function education()
    {
        return $this->hasManyThrough(Education::class, SubCategory::class)->where('educations.status', 1);
    }


    public function event()
    {
        return $this->hasManyThrough(Events::class, SubCategory::class)->where('events.status', 1);
    }

    public function talent()
    {
        return $this->hasManyThrough(Talents::class, SubCategory::class)->where('talents.status', 1);
    }

    public function buysells()
    {
        return $this->hasManyThrough(BuySell::class, SubCategory::class, 'main_category_id', 'sub_category_id')->where('buy_sells.status', 1);
    }

    public function directory()
    {
        return $this->hasManyThrough(Directory::class, SubCategory::class)->where('directories.status', 1);
    }

    public function concierge()
    {
        return $this->hasManyThrough(Concierge::class, SubCategory::class)->where('concierges.status', 1);
    }

    public function influencer()
    {
        return $this->hasManyThrough(Influencer::class, SubCategory::class)->where('influencers.status', 1);
    }

    public function job()
    {
        return $this->hasManyThrough(Job::class, SubCategory::class)->where('jobs.status', 1);
    }

    public function ticket()
    {
        return $this->hasManyThrough(Tickets::class, SubCategory::class)->where('tickets.status', 1);
    }

    public function accommodation()
    {
        return $this->hasManyThrough(Accommodation::class, SubCategory::class)->where('accommodations.status', 1);
    }

    public function bookArtist()
    {
        return $this->hasManyThrough(BookArtist::class, SubCategory::class, 'id', 'category_id')->where('book_artists.status', 1);
    }

    public function it()
    {
        return $this->hasManyThrough(It::class, SubCategory::class)->where('its.status', 1);
    }

    public function storedImage($img)
    {
        if(Storage::disk('s3')->exists(config('app.upload_other_path') . $img)) {
            return Storage::disk('s3')->url(config('app.upload_other_path') . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }
}
