<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
  protected $table = 'recommendation';
  public $timestamps = false;
  public function spaceModule()
  {
    return $this->hasOne(Accommodation::class, 'id', 'module_id');
  }
  public function motorsModule()
  {
    return $this->hasOne(Motors::class, 'id', 'module_id');
  }

  public function venueModule()
  {
    return $this->hasOne(Venue::class, 'id', 'module_id');
  }

  public function eventModule()
  {
    return $this->hasOne(Events::class, 'id', 'module_id');
  }

  public function buySellModule()
  {
    return $this->hasOne(BuySell::class, 'id', 'module_id');
  }

  public function directoryModule()
  {
    return $this->hasOne(Directory::class, 'id', 'module_id');
  }
  public function educationModule()
  {
    return $this->hasOne(Education::class, 'id', 'module_id');
  }
  public function influencerModule()
  {
    return $this->hasOne(Influencer::class, 'id', 'module_id');
  }
  public function cryptoModule()
  {
    return $this->hasOne(Crypto::class, 'id', 'module_id');
  }
  public function attractionModule()
  {
    return $this->hasOne(Attraction::class, 'id', 'module_id');
  }

  public function conciergeModule()
  {
    return $this->hasOne(Concierge::class, 'id', 'module_id');
  }
}
