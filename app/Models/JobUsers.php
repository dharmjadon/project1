<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobUsers extends Model
 {
    //

    public function getCreatedAtAttribute( $value )
 {
        return Carbon::parse( $value )->format( 'd-m-Y h:i:s' );
    }

    public function getDateOfBirthAttribute( $value )
 {
        return Carbon::parse( $value )->format( 'd-m-Y h:i:s' );
    }

    public function nation_detail() {
        return $this->belongsTo( Nationality::class, 'nationality' );
    }

    public function city_detail() {
        return $this->belongsTo( City::class, 'city_id' );
    }

    /**
    * Get the user that owns the JobUsers
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */

    public function user()
 {
        return $this->belongsTo( User::class, 'user_id', 'id' );
    }

    public function emirates() {
        return $this->belongsTo( State::class, 'region_id' );
    }

    public function storedImage( $img )
 {
        if ( Str::contains( $img, 'uploads/profile_image' ) ) {
            return Storage::disk( 's3' )->url( $img );
        }
        return Storage::disk( 's3' )->url( config( 'app.upload_users_path' ) . $img );
    }
}

