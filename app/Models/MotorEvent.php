<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorEvent extends Model
{
    protected $guarded = [];

    protected $fillable = ['motor_id', 'name', 'datetime'];

    public function motor()
    {
        return $this->belongsTo(Motors::class, 'id');
    }
}
