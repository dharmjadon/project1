<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopularPlaceSuggestion extends Model
{
    public function popularPlace()
    {
        return $this->belongsTo(PopularPlaceVenue::class, 'pp_id');
    }

    public function getSuggestionTypeNameAttribute($value) {
        if($this->suggestion_id == 1) {
            return "List as accommodation";
        }
        if($this->suggestion_id == 2) {
            return "List as things to do";
        }
        if($this->suggestion_id == 2) {
            return "List as restaurant";
        }
        if($this->suggestion_id == 4) {
            return "List as holiday rental";
        }
            return "N/A";
    }

}
