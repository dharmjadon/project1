<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotorImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'motor_id', 'image_type', 'image', 'alt_texts'
    ];

    protected $casts = [
      'alt_texts' => 'array'
    ];

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }
}
