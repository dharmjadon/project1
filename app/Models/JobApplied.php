<?php

namespace App\Models;

use App\Models\User;
use App\Models\Job;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class JobApplied extends Model
{
    //
    protected $table = 'job_applieds';

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function user_detail(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function nation_detail(){
        return $this->belongsTo(Nationality::class,'nation');
    }

    /**
     * Get the jobs that owns the JobApplied
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobs()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
