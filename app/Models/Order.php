<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\type;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['user_id','vendor_id', 'status', 'total_price', 'placed_at','coupon'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class,'user_id');
    }

  
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

  
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }


    public function setStatus($status)
    {
        $validStatuses = ['pending', 'completed', 'cancelled'];
        if (in_array($status, $validStatuses)) {
            $this->status = $status;
            $this->save();
        } else {
            throw new \Exception("Invalid status.");
        }
    }

    public function getPlacedAtAttribute()
    {
        return $this->created_at;
    }

  

    public function calculateTotalPrice()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    public function getDiscountedPrice()
    {
        $discount = Discount::whereRaw("FIND_IN_SET(?, product_ids)", [$this->product_id])
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderByDesc('discount_value') // Get the highest discount
            ->first();

        if ($discount) {
            return $this->price * ((100 - $discount->discount_value) / 100);
        }
        return $this->price;
    }

    // public function applyDiscounts()
    // {
    //     $totalFinalPrice = 0;

    //     foreach ($this->items as $item) {
    //         $finalPrice = $item->applyDiscount();
    //         $totalFinalPrice += $finalPrice;
    //     }

    //     // Update order's total price
    //     DB::table('orders')->where('id', $this->id)->update([
    //         'total_price' => $totalFinalPrice,
    //     ]);
    // }

    /**
     * Automatically apply discounts when an order is saved.
     */
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::saved(function ($order) {
    //         if ($order->wasRecentlyCreated || $order->isDirty(['status'])) {
    //             $order->applyDiscounts();
    //         }
    //     });
    // }
    
    
}
