<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price','discount','final_price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    // public function applyDiscount()
    // {
    //     $product = $this->product;
    //     $discountValue = 0;

    //     $discount = Discount::whereRaw("FIND_IN_SET(?, product_ids)", [$product->id])
    //                         ->where(function ($query) {
    //                             $query->whereNull('end_date') // No expiration date
    //                                   ->orWhere('end_date', '>', now()); // Not expired
    //                         })
    //                         ->first();

    //     if ($discount) {
    //             $discountValue = ($product->price * $discount->discount_value) / 100;
    //     }

    //     $finalPrice = max(0, $product->price - $discountValue);

    //     $this->update([
    //         'discount' => $discountValue,
    //         'final_price' => $finalPrice,
    //     ]);
    // }

    // /**
    //  * Automatically apply discount whenever an order item is saved.
    //  */
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::saving(function ($item) {
    //         $item->applyDiscount();
    //     });
    // }
}
