<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id', 'category_id', 'name', 'description', 'price',
        'stock_quantity', 'status', 'image', 'min_order_quantity', 'max_order_quantity', 'color', 'shape','items','notes'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // public function vendor()
    // {
    //     return $this->hasOne(Vendor::class, 'user_id', 'id');
    // }

    // public function vendor()
    // {
    //     return $this->belongsTo(User::class, 'vendor_id', 'id')->where('type', 'vendor');
    // }
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id')->where('type', 'vendor');
    }
    
    
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderItem::class, 'product_id', 'id', 'id', 'order_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function productDiscount()
    {
        return $this->hasOne(Discount::class)
            ->whereNotNull('product_id')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function categoryDiscount()
    {
        return $this->hasOne(Discount::class, 'category_id', 'category_id')
            ->whereNull('product_id')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function calculateFinalPrice(int $quantity)
    {
        $discount = Discount::getApplicableDiscount($this);
        $discountPercentage = $discount ? $discount->discount_value : 0;
        // Calculate price with discount
        $totalPrice = $this->price * $quantity;
        $totalDiscount = ($this->price * ($discountPercentage / 100)) * $quantity;
        $finalPrice = $totalPrice - $totalDiscount;

        return [
            'total_price' => $totalPrice,
            'total_discount' => $totalDiscount,
            'final_price' => $finalPrice
        ];
    }
    public function sales()
    {
        return $this->hasMany(OrderItem::class, 'product_id'); 
    }
    

}


   
