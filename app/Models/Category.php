<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'parent_id'];

    // Relation to parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relation to subcategories
    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // A category has many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // A category may have one active discount
    public function activeDiscount()
    {
        return $this->hasOne(Discount::class)
            ->whereNull('product_id')
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now());
    }
    // // Helper function to get all products including subcategories (if needed)
    // public function getAllProductsIncludingSubcategories()
    // {
    //     $products = $this->products;

    //     foreach ($this->subcategories as $subcategory) {
    //         $products = $products->merge($subcategory->products);
    //     }

    //     return $products;
    // }
}
