<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategoriesWithProducts()
    {
        try {
            // Fetch all categories with only active products
            $categories = Category::with(['products' => function ($query) {
                $query->where('status', 1); // Fetch only active products
            }])->get();
    
            return response()->json([
                'statusCode' => 200,
                'message' => 'تم استرجاع الفئات والمنتجات بنجاح.',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'حدث خطأ أثناء استرجاع البيانات.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
