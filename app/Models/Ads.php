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
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function reference()
    {
        return match ($this->type) {
            'product' => $this->belongsTo(Product::class, 'reference_id'),
            'category' => $this->belongsTo(Category::class, 'reference_id'),
            default => null,
        };
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
