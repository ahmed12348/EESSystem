<?php

namespace App\Models;

use App\Jobs\ExpireCartItems;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status','expires_at'];  // Assuming status field exists

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }

    public function isExpired()
    {
        return $this->expires_at <= Carbon::now();
    }


    public function refreshExpiration()
    {
        $cartExpirationHours = Setting::getValue('cart_expiration_hours', 2);
        $newExpirationTime = Carbon::now()->addHours($cartExpirationHours);
        // Dispatch expiration job
        ExpireCartItems::dispatch($this)->delay($newExpirationTime );
    }
}
