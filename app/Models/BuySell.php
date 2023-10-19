<?php

namespace App\Models;

use App\Models\Amenitable;
use App\Models\SubCategory;
use App\Models\Landmarkable;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BuySell extends Model
{
    protected $guarded = [];

    protected $fillable = ['lang', 'sub_category_id', 'product_name', 'location', 'lat', 'lng', 'city_id', 'date_and_time','images', 'feature_image', 'price', 'how_old', 'view_count', 'product_type', 'enquiry_email', 'description', 'status','is_feature', 'slug', 'dynamic_main_ids', 'dynamic_sub_ids', 'map_review', 'map_rating', 'whatsapp', 'contact','created_by', 'is_draft', 'is_publisher', 'meta_title', 'meta_description', 'meta_tags', 'stories', 'status_text', 'property_type', 'country', 'city', 'area', 'is_deals','is_verified','is_popular','popular_types','youtube_img','video','brand_id', 'is_hot', 'is_trending', 'discount_offer'
    ];
    protected $appends = array('main_category');


    public function getMainCategoryAttribute()
    {
        return isset($this->get_subcat->mainCategory) ? $this->get_subcat->mainCategory->id : 0;
    }
    // public function getCreatedAtAttribute($date){
    //     return \Carbon\Carbon::createFromFormat('d-m-Y H:i:s', $date)->format('Y-m-d');
    // }
    public function storedImage($img)
    {
        return Storage::disk('s3')->url(config('app.upload_buysell_path') . $img);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
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
        return $this->morphMany(CountClick::class, 'product')->where('type_of_click', '=', 1);
        ;
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_feature', 1);
    }

    /**
     * Scope a query to only include clearance products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDealsOfTheDay($query)
    {
        return $query->where('is_deals', 1);
    }

    public function scopeVerifiedSuppliers($query)
    {
        return $query->where('is_verified', 1);
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

    public function clickCountEmail()
    {
        return $this->morphMany(CountClick::class, 'product')->where('type_of_click', '=', 2);
        ;
    }

    public function clickCountPhone()
    {
        return $this->morphMany(CountClick::class, 'product')->where('type_of_click', '=', 3);
        ;
    }

    public function getAccommodationTypeNameAttribute($value)
    {
        return 'BuySell';
    }

    public function get_subcat()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function featureImage()
    {
        return $this->hasOne(BuysellImage::class, 'buysell_id')->where('image_type', 'feature_image')->latest();
    }

    /**
     * Get the story images of the venue.
     */
    public function storyImages()
    {
        return $this->hasMany(BuysellImage::class, 'buysell_id')->where('image_type', 'stories')->latest();
    }

    /**
     * Get the story images of the venue.
     */
    public function mainImage()
    {
        return $this->hasOne(BuysellImage::class, 'buysell_id')->where('image_type', 'main_image')->latest();
    }

    /**
     * Get the 4  images of the venue.
     */
    public function mainImages()
    {
        return $this->hasMany(BuysellImage::class, 'buysell_id')->where('image_type', 'images')->latest();
    }

    public function logoImage()
    {
        return $this->hasOne(BuysellImage::class, 'buysell_id')->where('image_type', 'logo')->latest();
    }

    public function floorPlanImage()
    {
        return $this->hasOne(BuysellImage::class, 'buysell_id')->where('image_type', 'floor_plan')->latest();
    }

    public function menuImage()
    {
        return $this->hasOne(BuysellImage::class, 'buysell_id')->where('image_type', 'menu')->latest();
    }

    public function getStoredImage($img, $img_type = 'feature_image')
    {
        if(Storage::disk('s3')->exists(config('app.upload_buysell_path') . $img_type . '/' . $img)) {
            return Storage::disk('s3')->url(config('app.upload_buysell_path') . $img_type . '/' . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }
    public function scopePopular($query)
    {
        return $query->where('is_popular', 1);
    }
    public function getTitleAttribute () {
        return $this->product_name;
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
