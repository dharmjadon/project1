<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuysellImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'buysell_id',
        'image_type',
        'image',
        'alt_texts'
    ];

    protected $casts = [
        'alt_texts' => 'array',
    ];

    public function buysell()
    {
        return $this->belongsTo(BuySell::class, 'buysell_id');
    }
}