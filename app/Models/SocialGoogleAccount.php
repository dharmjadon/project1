<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class SocialGoogleAccount extends Model
{
    protected $fillable = ['user_id', 'google_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
