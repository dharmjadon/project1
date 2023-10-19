<?php

namespace App\Models;

use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Influencer extends Model
{
    protected $guarded = [];
    protected $fillable = ['sub_category_id', 'slug', 'name', 'location', 'country', 'city', 'area', 'lat', 'lng', 'city_id', 'social', 'follow', 'followers', 'likes', 'phone', 'whatsapp', 'email', 'website', 'description', 'images', 'feature_image', 'views', '  featured', 'is_popular', 'is_hot', 'is_trending', 'discount_offer', 'review', 'dynamic_main_ids', 'dynamic_sub_ids', 'social_links', 'posts', 'map_review', 'map_rating', 'created_by', 'is_draft', 'is_publisher', 'meta_title', 'meta_description', 'meta_tags', 'meta_img_alt', 'meta_img_title', 'meta_img_description', 'stories','status', 'status_text', 'youtube_links', 'lang'
    ];

    protected $appends = array('main_category');

    public function getMainCategoryAttribute()
    {
        return isset($this->get_subcat->mainCategory) ? $this->get_subcat->mainCategory->id : 0;
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

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function storedImage($img)
    {
        if(Str::contains($img, 'uploads/influencers')) {
            return Storage::disk('s3')->url($img);
        }
        return Storage::disk('s3')->url(config('app.upload_influencer_path') . $img);
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
        return $this->hasOne(InfluencerImage::class, 'influencer_id')->where('image_type', 'feature_image')->latest();
    }

    /**
     * Get the story images of the event.
     */
    public function storyImages()
    {
        return $this->hasMany(InfluencerImage::class, 'influencer_id')->where('image_type', 'stories')->latest();
    }

    /**
     * Get the 4  images of the event.
     */
    public function mainImages()
    {
        return $this->hasMany(InfluencerImage::class, 'influencer_id')->where('image_type', 'images')->latest();
    }

    public function getStoredImage($img, $img_type = 'feature_image')
    {
        if(Storage::disk('s3')->exists(config('app.upload_influencer_path') . $img_type . '/' . $img)) {
            return Storage::disk('s3')->url(config('app.upload_influencer_path') . $img_type . '/' . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }

    public function getTitleAttribute () {
        return $this->name;
    }

    public function scopeTrending($query)
    {
        $query->where('is_trending', 1);
    }

    public function scopeHot($query)
    {
        $query->where('is_hot', 1);
    }
}
