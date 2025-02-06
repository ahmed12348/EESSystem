<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price','total_price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function($orderItem) {
            if ($orderItem->quantity <= 0) {
                throw new \Exception("Quantity must be a positive number.");
            }
        });
    }
}
