<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'expires_at', 'status'];  // Assuming status field exists
    protected $dates = ['expires_at'];

    public function checkExpiration()
    {
        $cartExpirationTime = Setting::getValue('cart_expiration_minutes', 60); // Dynamic expiration time from settings

        if ($this->expires_at <= Carbon::now()) {
            $this->update([
                'status' => 0
            ]);
            return true; 
        }
        return false;
    }

    public function user() {
        return $this->belongsTo(User::class);
    }


    public function items() {
        return $this->hasMany(CartItem::class);
    }


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($cart) {
            $expirationHours = Setting::getValue('cart_expiration_hours', 2);
            $cart->expires_at = Carbon::now()->addHours($expirationHours);
            $cart->status = 1; 
        });
    }


    public function isExpired()
    {
        return $this->expires_at <= Carbon::now();
    }


    public function updateStatusIfExpired()
    {
        if ($this->isExpired()) {
            $this->update(['status' => 0]); 
        }
    }
}
