<?php

namespace App\Models;

use App\Models\Amenitable;
use App\Models\SubCategory;
use App\Models\Landmarkable;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Venue extends Model
{
    protected $guarded = [];

    protected $fillable = ['lang', 'title', 'slug', 'description', 'sub_category_id', 'assign_featured', 'start_date_time', 'end_date_time','views', 'reservation', 'lat', 'long', 'location', 'country', 'city', 'city_id', 'area','cusine_name', 'view_floor_plan', 'images', 'feature_image', 'stories', 'icon', 'amenity_id', 'landmark_id','whatsapp', 'contact', 'email', 'prices', 'view_menu', 'is_draft', 'is_publisher','video', 'youtube_img', 'map_review', 'map_rating', 'status','meta_tags', 'meta_title', 'meta_description',
        'status_text', 'is_popular', 'popular_types', 'created_by', 'venue_capacity', 'is_hot', 'is_trending', 'discount_offer'
    ];

    protected $casts = [
        'amenity_id' => 'array',
        'landmark_id' => 'array',
    ];

    protected $appends = array('main_category');


    public function getMainCategoryAttribute()
    {
        return $this->get_subcat->mainCategory->id;
    }
    // public function getCreatedAtAttribute($date){
    //     return \Carbon\Carbon::createFromFormat('d-m-Y H:i:s', $date)->format('Y-m-d');
    // }
    public function storedImage($img)
    {
        return Storage::disk('s3')->url(config('app.upload_venue_path') . $img);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function publish_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class)->withDefault();
    }

    public function amenities()
    {
        return $this->morphMany(Amenitable::class, 'amenitable');
    }

    public function landmarks()
    {
        return $this->morphMany(Landmarkable::class, 'landmarkable');
    }

    public function enquiries()
    {
        return $this->morphMany(EnquireForm::class, 'item');
    }

    public function recommend()
    {
        return $this->morphMany(ItemRecommendation::class, 'item');
    }

    public function wishlists()
    {
        return $this->morphMany(WishListDetails::class, 'item');
    }


    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function approvedReviews()
    {
        return $this->morphMany(Review::class, 'reviewable')->where('approved', '=', 1);
    }

    public function clickCount()
    {
        return $this->morphMany(CountClick::class, 'product');
    }

    public function clickCountWhatsapp()
    {
        return $this->morphMany(CountClick::class, 'product')->where('type_of_click', '=', 1);;
    }

    public function scopeFeatured($query)
    {
        return $query->where('assign_featured', 1);
    }

    /**
     * Scope a query to only include clearance products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopular($query)
    {
        return $query->where('is_popular', 1);
    }

    /**
     * Scope a query to only include clearance products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to only include clearance products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTrending($query)
    {
        return $query->where('is_trending', 1);
    }

    /**
     * Scope a query to only include clearance products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHot($query)
    {
        return $query->where('is_hot', 1);
    }

    public function clickCountEmail()
    {
        return $this->morphMany(CountClick::class, 'product')->where('type_of_click', '=', 2);;
    }

    public function clickCountPhone()
    {
        return $this->morphMany(CountClick::class, 'product')->where('type_of_click', '=', 3);;
    }

    public function events()
    {
        return $this->hasMany(VenueEvent::class);
    }

    public function upcomingEvents()
    {
        return $this->hasMany(VenueEvent::class)->where('datetime', '>', now());
    }

    public function getAccommodationTypeNameAttribute($value)
    {
        return 'Venue';
    }

    public function get_subcat()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function featureImage()
    {
        return $this->hasOne(VenueImage::class)->where('image_type', 'feature_image')->latest();
    }

    /**
     * Get the story images of the venue.
     */
    public function storyImages()
    {
        return $this->hasMany(VenueImage::class)->where('image_type', 'stories')->latest();
    }

    /**
     * Get the story images of the venue.
     */
    public function mainImage()
    {
        return $this->hasOne(VenueImage::class)->where('image_type', 'main_image')->latest();
    }

    /**
     * Get the 4  images of the venue.
     */
    public function mainImages()
    {
        return $this->hasMany(VenueImage::class)->where('image_type', 'images')->latest()->limit(4);
    }

    public function logoImage()
    {
        return $this->hasOne(VenueImage::class)->where('image_type', 'logo')->latest();
    }

    public function floorPlanImage()
    {
        return $this->hasOne(VenueImage::class)->where('image_type', 'floor_plan')->latest();
    }

    public function menuImage()
    {
        return $this->hasOne(VenueImage::class)->where('image_type', 'menu')->latest();
    }

    public function getStoredImage($img, $img_type = 'feature_image')
    {
        if(Storage::disk('s3')->exists(config('app.upload_venue_path') . $img_type . '/' . $img)) {
            return Storage::disk('s3')->url(config('app.upload_venue_path') . $img_type . '/' . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }
}
