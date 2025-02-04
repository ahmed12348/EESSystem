<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name'];
    use HasFactory;

    public function locations() {
        return $this->hasMany(Location::class, 'city_id');
    }
}
