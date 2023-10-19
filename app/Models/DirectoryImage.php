<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'directory_id', 'image_type', 'image', 'alt_texts'
    ];

    protected $casts = [
        'alt_texts' => 'array',
    ];

    public function directory()
    {
        return $this->belongsTo(Directory::class);
    }
}
