<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['user_id', 'status', 'total_price', 'placed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

  
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

  
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

 
    public function getTotalPriceAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
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
}
