<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'price','total_price'];

    public function cart() {
        return $this->belongsTo(Cart::class);
    }

    // Relationship with Product model
    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function getTotalPriceAttribute() {
        return $this->quantity * $this->price;
    }

    public static function validateQuantity($quantity) {
        return $quantity > 0;
    }
}
