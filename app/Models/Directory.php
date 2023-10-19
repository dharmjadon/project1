<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Directory extends Model
{
    protected $guarded = [];

    protected $fillable = ['lang', 'main_category_id', 'sub_category_id', 'status', 'title', 'founded_date', 'images', 'feature_image', 'qr_code', 'description', 'social_links', 'lat', 'long', 'location', 'country', 'city', 'area', 'address', 'website', 'quick_contacts', 'city_id', 'enquiry_email', 'company_logo', 'views_counter', 'created_at', 'updated_at', 'slug', 'is_feature', 'dynamic_main_ids', 'dynamic_sub_ids', 'youtube_img', 'youtube_image', 'map_review', 'map_rating', 'whatsapp', 'created_by', 'is_draft', 'is_publisher', 'is_popular', 'meta_title', 'meta_description', 'meta_tags', 'meta_img_alt', 'meta_img_title', 'meta_img_description', 'stories', 'status_text', 'video', 'is_hot', 'is_trending', 'discount_offer', 'online_market'
    ];

    protected $casts = [
        'social_links' => 'array'
    ];

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
        return $query->where('is_feature', 1);
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
        if(Str::contains($img, 'uploads/directory')) {
            return Storage::disk('s3')->url($img);
        }
        return Storage::disk('s3')->url(config('app.upload_directory_path') . $img);
    }



    public function getFoundedDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
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
        return 'Directory';
    }

    public function featureImage()
    {
        return $this->hasOne(DirectoryImage::class)->where('image_type', 'feature_image')->latest();
    }

    /**
     * Get the story images of the directory.
     */
    public function storyImages()
    {
        return $this->hasMany(DirectoryImage::class)->where('image_type', 'stories')->latest();
    }

    /**
     * Get the story images of the directory.
     */
    public function mainImage()
    {
        return $this->hasOne(DirectoryImage::class)->where('image_type', 'main_image')->latest();
    }

    /**
     * Get the 4  images of the directory.
     */
    public function mainImages()
    {
        return $this->hasMany(DirectoryImage::class)->where('image_type', 'images')->latest();
    }

    public function logoImage()
    {
        return $this->hasOne(DirectoryImage::class)->where('image_type', 'logo')->latest();
    }

    public function floorPlanImage()
    {
        return $this->hasOne(DirectoryImage::class)->where('image_type', 'floor_plan')->latest();
    }

    public function menuImage()
    {
        return $this->hasOne(DirectoryImage::class)->where('image_type', 'menu')->latest();
    }

    public function qrCodeImage()
    {
        return $this->hasOne(DirectoryImage::class)->where('image_type', 'qr_code')->latest();
    }

    public function getStoredImage($img, $img_type = 'feature_image')
    {
        if(Storage::disk('s3')->exists(config('app.upload_directory_path') . $img_type . '/' . $img)) {
            return Storage::disk('s3')->url(config('app.upload_directory_path') . $img_type . '/' . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }
}
