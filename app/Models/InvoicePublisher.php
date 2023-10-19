<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class InvoicePublisher extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }
}
