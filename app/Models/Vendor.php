<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Vendor extends Model
{
    use HasFactory,SoftDeletes;
    


    protected $fillable = ['business_name','zone','user_id','is_verified','tax_id', 'location_id','photo','business_type','notes','address','latitude','longitude','city_id','region_id',]; 
 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    



    public function isActiveVendor()
    {
        return $this->user->isActive() && $this->user->isVendor();
    }

    public function products() 
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }
    
    public function city() {
        return $this->belongsTo(City::class, 'city_id');
    }

   
    public function region() {
        return $this->belongsTo(Region::class, 'region_id');
    }
    public function orders()
    {
        return $this->hasManyThrough(
            Order::class,       // Final model we want to retrieve (Orders)
            OrderItem::class,   // Intermediate model (Order Items)
            'product_id',       // Foreign key in OrderItem referencing Product
            'id',               // Primary key in Order model
            'id',               // Primary key in Vendor
            'order_id'          // Foreign key in OrderItem referencing Order
        );
    }
}
