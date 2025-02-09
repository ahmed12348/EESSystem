<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Vendor extends Model
{
    use HasFactory,SoftDeletes;
    


    protected $fillable = ['business_name','zone','user_id', 'tax_id', 'location_id','photo','business_type']; 
 

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function location() {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function isActiveVendor()
    {
        return $this->user->isActive() && $this->user->isVendor();
    }

    public function products() 
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }
  
}
