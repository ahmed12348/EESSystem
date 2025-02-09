<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::latest()->paginate(10);
    return view('admin.discounts.index', compact('discounts'));
    }

    public function show($id)
    {
        $discount = Discount::with('product')->findOrFail($id);

        return view('admin.discounts.show', compact('discount'));
    }

    public function create()
    {
        $products = Product::all();
        $categories = Category::all();
        $vendors = Vendor::all();
    
        return view('admin.discounts.create', compact('products', 'categories', 'vendors'));
    }   

    public function store(Request $request)
    {
       
        $request->validate([
            'discount_value' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:product,category,vendor',
        ]);
    
        $discount = Discount::create([
            'discount_value' => $request->discount_value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
        ]);
    
        // Apply to products, categories, or vendors
        if ($request->type === 'product' && $request->has('products')) {
            $discount->product()->attach($request->products);
        } elseif ($request->type === 'category' && $request->has('category_id')) {
            $category = Category::find($request->category_id);
            $discount->product()->attach($category->products);
        } elseif ($request->type === 'vendor' && $request->has('vendor_id')) {
            $vendor = Vendor::find($request->vendor_id);
            $discount->product()->attach($vendor->products);
        }
    
        return redirect()->route('admin.discounts.index')->with('success', 'Discount created successfully.');
    }
    
}
