<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPageContent extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'recruiter_heading', 'recruiter_content', 'recruiter_image',
        'jobseeker_heading', 'jobseeker_content', 'jobseeker_image',
        'resume_heading', 'resume_content', 'resume_image',
        'interview_heading', 'interview_content', 'interview_image', 'interview_link'
    ];

    protected $casts = ['interview_content' => 'array'];
}
