<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ads extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'image',
        'type',
        'vendor_id',
        'reference_id',
        'zone',
        'active',
        'status'
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // Dynamic reference relationship based on type (product or category)
    public function reference()
    {
        return match ($this->type) {
            'product' => $this->belongsTo(Product::class, 'reference_id'),
            'category' => $this->belongsTo(Category::class, 'reference_id'),
            default => null,  // If the type is unknown, return null
        };
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
   public function category()
    {
        return $this->belongsTo(Category::class, 'reference_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'reference_id');
    }
}
