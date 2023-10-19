<?php

namespace App\Models;

use App\Models\SliderImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MajorCategory extends Model
{
    protected $guarded = [];

    protected $fillable = ['lang', 'name', 'slug', 'amenities', 'landmarks',
        'meta_title', 'meta_tags', 'meta_description', 'banner_images', 'images', 'whatsapp_text', 'video',
        'register_heading', 'register_summary', 'register_image', 'blogs_list_heading', 'blogs_list',
        'listings_meta_title', 'listings_meta_tags', 'listings_meta_description',
    ];

    protected $casts = ['blogs_list' => 'array'];

    public function banner_images()
    {
        return $this->hasMany(SliderImage::class,'major_category_id');
    }
    public function mainCategory()
    {
        return $this->hasMany(MainCategory::class);
    }

    public function searchLinks()
    {
        return $this->hasMany(SearchLink::class);
    }

    public function searchLinksTop()
    {
        return $this->hasMany(SearchLink::class)->where('link_position', 'top');
    }
    public function searchLinksBottom()
    {
        return $this->hasMany(SearchLink::class)->where('link_position', 'bottom');
    }

    public function statistics()
    {
        return $this->hasMany(ModuleStatistic::class);
    }

    public function dynamicLinks()
    {
        return $this->hasMany(DynamicLink::class);
    }

    public function bannerLinks()
    {
        return $this->hasMany(BannerLink::class);
    }

    public function bannerLinksBottom()
    {
        return $this->hasMany(BannerLink::class)->where('banner_position', 'bottom');
    }

    public function bannerLinksTop()
    {
        return $this->hasMany(BannerLink::class)->where('banner_position', 'top');
    }

    public function bannerLinksLeft()
    {
        return $this->hasMany(BannerLink::class)->where('banner_position', 'left');
    }

    public function bannerLinksRight()
    {
        return $this->hasMany(BannerLink::class)->where('banner_position', 'right');
    }

    public function popularDynamicLinks()
    {
        return $this->hasOne(DynamicLink::class)->where('slug', 'popular-'.$this->slug);
    }

    public function trendingDynamicLinks()
    {
        return $this->hasMany(DynamicLink::class)->where('slug', 'trending-'.$this->slug);
    }

    public function hotDynamicLinks()
    {
        return $this->hasMany(DynamicLink::class)->where('slug', 'hot-'.$this->slug);
    }

    public function storedImage($img)
    {
        if(Storage::disk('s3')->exists(config('app.upload_other_path') . $img)) {
            return Storage::disk('s3')->url(config('app.upload_other_path') . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }
}
