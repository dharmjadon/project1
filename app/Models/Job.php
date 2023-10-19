<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Job extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'lang', 'job_company_id', 'company', 'status', 'featured', 'job_title', 'slug', 'date_time',
        'city_id', 'min_salary', 'max_salary', 'experience', 'listed_by',
        'gender', 'career_level', 'logo', 'qualification', 'description',
        'responsibility', 'benefit', 'location', 'lat', 'long', 'area',
        'city', 'country', 'number_of_employee', 'social_links', 'about_company', 'company_website',
        'reviews', 'views', 'map_review', 'map_rating', 'sub_category_id', 'created_by', 'is_draft',
        'is_publisher', 'is_popular', 'is_remote', 'job_type', 'last_date_to_apply', 'company_founded',
        'meta_tags', 'meta_title', 'meta_description', 'stories', 'status_text',
        'vacancy', 'is_hot', 'is_trending', 'discount_offer', 'whatsapp', 'contact', 'email'
    ];

    protected $dates = ['date_time', 'last_date_to_apply'];

    protected $appends = array('main_category');

    public function jobCompany()
    {
        return $this->belongsTo(JobCompany::class);
    }

    public function getMainCategoryAttribute()
    {
        return isset($this->sub_category->mainCategory) ? $this->sub_category->mainCategory->id : 0;
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function storedImage($img)
    {
        return Storage::disk('s3')->url(config('app.upload_job_path') . $img);
    }

    public function getDateTimeAttribute($value)
    {
        return Carbon::parse($value)->format('m/d/Y h:i A');
    }

    /*public function getLastDateToApplyAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('m/d/Y') : '';
    }*/

    public function city()
    {
        return $this->belongsTo(City::class)->withDefault();
    }

    public function city_name()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function publish_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
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
    public function applied_candidate()
    {
        return $this->hasMany(JobApplied::class, 'job_id');
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
        return $query->where('featured', 1);
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


    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function featureImage()
    {
        return $this->hasOne(JobImage::class)->where('image_type', 'feature_image')->latest();
    }

    /**
     * Get the story images of the venue.
     */
    public function storyImages()
    {
        return $this->hasMany(JobImage::class)->where('image_type', 'stories')->latest();
    }

    /**
     * Get the story images of the venue.
     */
    public function mainImage()
    {
        return $this->hasOne(JobImage::class)->where('image_type', 'main_image')->latest();
    }

    /**
     * Get the 4  images of the venue.
     */
    public function mainImages()
    {
        return $this->hasMany(JobImage::class)->where('image_type', 'images')->latest();
    }

    public function logoImage()
    {
        return $this->hasOne(JobImage::class)->where('image_type', 'logo')->latest();
    }

    public function getStoredImage($img, $img_type = 'feature_image')
    {
        if(Storage::disk('s3')->exists(config('app.upload_job_path') . $img_type . '/' . $img)) {
            return Storage::disk('s3')->url(config('app.upload_job_path') . $img_type . '/' . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }

    public function getTitleAttribute () {
        return $this->job_title;
    }
}
