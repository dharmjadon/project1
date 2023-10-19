<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoImage extends Model
{
    use HasFactory;
    protected $table = "coins_images";
    protected $fillable = [
        'crypto_id', 'image_type', 'image', 'alt_texts'
    ];

    protected $casts = [
      'alt_texts' => 'array'
    ];

    public function venue()
    {
        return $this->belongsTo(Crypto::class);
    }
}
