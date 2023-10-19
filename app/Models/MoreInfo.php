<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MoreInfo extends Model
{
  protected $table = 'more_info';

  protected $fillable = [
      'section_name', 'section_heading', 'section_summary', 'file_name', 'file_path', 'module_name', 'module_id', 'user_id', 'user_type'
  ];

  public function storedOtherImage($img)
    {
        if(Storage::disk('s3')->exists(config('app.upload_other_path') . $img)) {
            return Storage::disk('s3')->url(config('app.upload_other_path') . $img);
        }
        return '/v2/images/image-placeholder.jpeg';
    }
}
