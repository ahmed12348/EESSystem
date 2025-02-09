<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'product_id', 'discount_value', 'start_at', 'end_at','type','vendor_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // A discount may apply to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($discount) {
            if ($discount->category_id && !$discount->product_id) {
                Product::where('category_id', $discount->category_id)->update(['updated_at' => now()]);
            }
        });
    }
}
