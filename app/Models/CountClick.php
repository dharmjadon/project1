<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountClick extends Model
{
    protected $guarded = [];
    public function product()
    {
        return $this->morphTo();
    }
}
