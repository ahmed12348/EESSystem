<?php

namespace App\Models;

use App\Jobs\ExpireCartItems;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id', 'product_id', 'quantity', 'status', 
        'expires_at', 'price', 'discount', 'final_price'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->where('status', 1);
    }

    public function isExpired()
    {
        return $this->expires_at && Carbon::parse($this->expires_at)->isPast();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', Carbon::now());
        });
    }

}
