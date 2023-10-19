<?php

namespace App\Models;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Talents extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'lang',
        'sub_category_id',
        'title',
        'lat',
        'lng',
        'location',
        'country',
        'city',
        'area',
        'is_featured',
        'is_popular',
        'is_hot',
        'is_trending',
        'discount_offer',
        'images',
        'feature_image',
        'logo',
        'video',
        'views',
        'prices',
        'youtube_img',
        'map_review',
        'map_rating',
        'description',
        'slug',
        'whatsapp',
        'mobile',
        'email',
        'status',
        'created_by',
        'meta_keywords',
        'meta_title',
        'meta_description',
        'is_draft',
        'is_publisher',
        'stories',
        'social_links',
        'status_text',
    ];

    protected $casts = [
        'social_links' => 'array',
        'video' => 'array',
    ];
    public function scopePopular($query)
    {
        return $query->where('is_popular', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

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
        if(Str::contains($img, 'uploads/talent')) {
            return Storage::disk('s3')->url($img);
        }
        return Storage::disk('s3')->url(config('app.upload_talent_path') . $img);
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
        return 'Talent';
    }

    public function featureImage()
    {
        return $this->hasOne(TalentImages::class, 'talent_id')->where('image_type', 'feature_image')->latest();
    }

    public function storyImages()
    {
        return $this->hasMany(TalentImages::class, 'talent_id')->where('image_type', 'stories')->latest();
    }

    public function mainImage()
    {
        return $this->hasOne(TalentImages::class, 'talent_id')->where('image_type', 'main_image')->latest();
    }

    public function mainImages()
    {
        return $this->hasMany(TalentImages::class, 'talent_id')->where('image_type', 'images')->latest();
    }

    public function logoImage()
    {
        return $this->hasOne(TalentImages::class, 'talent_id')->where('image_type', 'logo')->latest();
    }

    public function getStoredImage($img, $img_type = 'feature_image')
    {
        if(Storage::disk('s3')->exists(config('app.upload_talent_path') . $img_type . '/' . $img)) {
            return Storage::disk('s3')->url(config('app.upload_talent_path') . $img_type . '/' . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }
}
