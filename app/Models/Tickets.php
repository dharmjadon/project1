<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Tickets extends Model
{
    //
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }

    public function storedImage($img)
    {
        return Storage::disk('s3')->url(config('app.upload_ticket_path') . $img);
    }

    public function getDateAndTimeAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i:s');
    }




    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id');
    }

    public function publish_by(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function enquiries()
    {
        return $this->morphMany(EnquireForm::class, 'item');
    }
}
