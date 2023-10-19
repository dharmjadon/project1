<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'lang', 'title', 'slug', 'blog_category_id', 'publish_date', 'publisher',
        'content', 'tags', 'meta_title', 'page_heading', 'meta_keywords',
        'meta_description', 'is_featured', 'is_popular', 'status', 'is_draft',
        'views'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['blogCategory', 'thumbnailImage'];

    /**
     * Get the thumbnail of the blog.
     */
    public function thumbnailImage()
    {
        return $this->hasOne(BlogImage::class)->where('image_type', 'thumbnail')->latest();
    }

    /**
     * Get the feature images of the blog.
     */
    public function featureImages()
    {
        return $this->hasMany(BlogImage::class)->where('image_type', 'feature_image')->latest();
    }

    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
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

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function getPublishDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function storedImage($img, $img_type = 'thumbnail')
    {
        return Storage::disk('s3')->url(config('app.upload_blog_path') . $img_type.'/'. $img);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }






}
