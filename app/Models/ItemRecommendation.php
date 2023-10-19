<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemRecommendation extends Model
{
    protected $guarded = [];

    public function item()
    {
        return $this->morphTo();
    }

    public function wishlists(){
        return $this->morphMany(WishListDetails::class, 'item');
    }
}
