<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class JobCompany extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        'company_name', 'status', 'job_title', 'slug', 'logo',
        'location', 'lat', 'long', 'area', 'city', 'country',
        'number_of_employee', 'social_links', 'about_company', 'company_website',
        'created_by', 'is_publisher', 'is_featured', 'is_popular', 'company_founded',
        'meta_tags', 'meta_title', 'meta_description', 'whatsapp', 'contact', 'email',
        'views', 'map_review', 'map_rating',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function openJobs()
    {
        return $this->hasMany(Job::class)->active()->where('jobs.last_date_to_apply', '<', now());
    }

    /**
     * Scope a query to only include popular companies.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopular($query)
    {
        return $query->where('is_popular', 1);
    }

    /**
     * Scope a query to only include active companies.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    public function getLogoImage()
    {
        if(Storage::disk('s3')->exists(config('app.upload_job_path') .'logo/'. $this->logo)) {
            return Storage::disk('s3')->url(config('app.upload_job_path') .'logo/'. $this->logo);
        }
        return '/v2/images/image-placeholder.jpeg';
    }
}
