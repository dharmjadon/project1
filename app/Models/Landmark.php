<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Landmark extends Model
{
    //

    public function storedImage($img)
    {
        if(Storage::disk('s3')->exists(config('app.upload_other_path') . $img)) {
            return Storage::disk('s3')->url(config('app.upload_other_path') . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }


}
