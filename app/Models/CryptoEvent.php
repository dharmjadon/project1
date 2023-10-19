<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoEvent extends Model
{
    protected $guarded = [];
    protected $table = "coins_events";
    protected $fillable = ['crypto_id', 'name', 'datetime'];

    public function crypto()
    {
        return $this->belongsTo(Crypto::class);
    }
}
