<?php

namespace App\Models;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Events extends Model
{
    protected $guarded = [];

    protected $fillable = ['lang', 'title', 'slug', 'description', 'sub_category_id', 'is_featured',
        'start_date_time', 'end_date_time', 'views', 'reservation', 'lat', 'lng', 'location', 'country',
        'city', 'city_id', 'area', 'cuisine_name', 'view_floor_plan', 'images', 'feature_image', 'stories',
        'logo', 'amenties', 'landmarks', 'whatsapp', 'mobile', 'email', 'prices', 'view_menu', 'view_floor_plan', 'is_draft',
        'is_publisher', 'video', 'youtube_img', 'map_review', 'map_rating', 'status', 'meta_keywords', 'meta_title',
        'meta_description', 'status_text', 'is_popular', 'popular_types', 'created_by', 'event_capacity',
        'assign_weekly_suggestion', 'routine', 'is_hot', 'is_trending', 'discount_offer'
    ];

    protected $casts = [
        'amenties' => 'array',
        'landmarks' => 'array',
    ];

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
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    public function scopeTrending($query)
    {
        $query->where('is_trending', 1);
    }

    public function scopeHot($query)
    {
        $query->where('is_hot', 1);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function storedImage($img)
    {
        if(Str::contains($img, 'uploads/events')) {
            return Storage::disk('s3')->url($img);
        }
        return Storage::disk('s3')->url(config('app.upload_event_path') . $img);
    }



    public function getDateTimeAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function recommend()
    {
        return $this->morphMany(ItemRecommendation::class, 'item');
    }

    public function get_subcat(){

        return $this->belongsTo(SubCategory::class,'sub_category_id');
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function publish_by(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function enquiries()
    {
        return $this->morphMany(EnquireForm::class, 'item');
    }

    public function wishlists(){
        return $this->morphMany(WishListDetails::class, 'item');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function approvedReviews()
    {
        return $this->morphMany(Review::class, 'reviewable')->where('approved','=', 1);
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
	public function getAccommodationTypeNameAttribute($value) {
        return 'Event';
    }

    public function featureImage()
    {
        return $this->hasOne(EventImage::class, 'event_id')->where('image_type', 'feature_image')->latest();
    }

    /**
     * Get the story images of the event.
     */
    public function storyImages()
    {
        return $this->hasMany(EventImage::class, 'event_id')->where('image_type', 'stories')->latest();
    }

    /**
     * Get the story images of the event.
     */
    public function mainImage()
    {
        return $this->hasOne(EventImage::class, 'event_id')->where('image_type', 'main_image')->latest();
    }

    /**
     * Get the 4  images of the event.
     */
    public function mainImages()
    {
        return $this->hasMany(EventImage::class, 'event_id')->where('image_type', 'images')->latest();
    }

    public function logoImage()
    {
        return $this->hasOne(EventImage::class, 'event_id')->where('image_type', 'logo')->latest();
    }

    public function floorPlanImage()
    {
        return $this->hasOne(EventImage::class, 'event_id')->where('image_type', 'floor_plan')->latest();
    }

    public function menuImage()
    {
        return $this->hasOne(EventImage::class, 'event_id')->where('image_type', 'menu')->latest();
    }

    public function getStoredImage($img, $img_type = 'feature_image')
    {
        if(Storage::disk('s3')->exists(config('app.upload_event_path') . $img_type . '/' . $img)) {
            return Storage::disk('s3')->url(config('app.upload_event_path') . $img_type . '/' . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }
}
