<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategoriesWithProducts()
    {
        // Fetch all categories with their products
        $categories = Category::with('products')->get();

        return response()->json([
            'statusCode' => 200,
            'message' => 'تم استرجاع الفئات والمنتجات بنجاح.',
            'data' => $categories
        ]);
    }
}
