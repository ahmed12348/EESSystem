<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'discount_value', 'start_date', 'end_date', 'type', 'category_id', 'vendor_id', 'zone_id', 'product_ids'
    ];

    // Convert product_ids from string to array
    public function getProductIdsAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }

    // Convert array to string when saving
    public function setProductIdsAttribute($value)
    {
        $this->attributes['product_ids'] = is_array($value) ? implode(',', $value) : $value;
    }

    // Get product models
    public function products()
    {
        return Product::whereIn('id', $this->product_ids)->get();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


}
