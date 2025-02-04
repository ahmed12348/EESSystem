<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['address', 'city_id', 'region_id', 'latitude', 'longitude'];

    public function city() {
        return $this->belongsTo(City::class, 'city_id');
    }

   
    public function region() {
        return $this->belongsTo(Region::class, 'region_id');
    }

  
    public function vendors() {
        return $this->hasMany(Vendor::class, 'location_id');
    }
}
