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
        'stock_quantity', 'status', 'image', 'min_order_quantity', 'max_order_quantity'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
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

    public function getFinalPriceAttribute()
    {
        $discount = $this->productDiscount ?? $this->categoryDiscount;
        return $discount ? $this->price - ($this->price * ($discount->discount_value / 100)) : $this->price;
    }
}
