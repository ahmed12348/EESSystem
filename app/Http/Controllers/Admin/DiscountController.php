<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::latest()->paginate(10);
    return view('admin.discounts.index', compact('discounts'));
    }

    public function show($id)
    {
        $discount = Discount::findOrFail($id);
    
        // Fetch product names based on discount type
        if ($discount->type === 'category') {
            $products = Product::where('category_id', $discount->type_id)->pluck('name');
        } elseif ($discount->type === 'vendor') {
            $products = Product::where('vendor_id', $discount->type_id)->pluck('name');
        } elseif ($discount->type === 'zone') {
            $vendorIds = Vendor::where('zone_id', $discount->type_id)->pluck('id');
            $products = Product::whereIn('vendor_id', $vendorIds)->pluck('name');
        } elseif ($discount->type === 'product') {
            $productIds = is_string($discount->product_ids) ? explode(',', $discount->product_ids) : [(string) $discount->product_ids]; 
            $products = Product::whereIn('id', $productIds)->pluck('name');
        } 
     
        return view('admin.discounts.show', compact('discount', 'products'));
   
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
            'discount_value' => 'required|integer|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'type' => 'required|in:product,category,vendor,zone',
        ]);
    
        // Initialize Product Collection
        $products = collect();
    
        // **Determine Product IDs Based on Type**
        if ($request->type === 'category') {
            $products = Product::where('category_id', $request->category_id)->pluck('id');
        } elseif ($request->type === 'vendor') {
            $products = Product::where('vendor_id', $request->vendor_id)->pluck('id');
        } elseif ($request->type === 'zone') {
            $vendorIds = Vendor::where('zone_id', $request->zone_id)->pluck('id');
            $products = Product::whereIn('vendor_id', $vendorIds)->pluck('id');
        } elseif ($request->type === 'product') {
            // **If product type, get selected product IDs**
            $products = collect(explode(',', $request->product_id));  // Example: "2,23,5,7,83"
        }
    
        // Convert product IDs to a comma-separated string
        $productIdsString = $products->implode(',');
    
        // **Create Discount Record and Store product_ids as a String**
        $discount = Discount::create([
            'discount_value' => $request->discount_value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'type_id' => $request->input("{$request->type}_id"),
            'product_ids' => $productIdsString,  // Save as "1,3,4,5,6,7,8,9,10"
        ]);
    
        return redirect()->route('admin.discounts.index')->with('success', 'Discount created successfully!');
    }
    
    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
    
        // Fetch product names based on discount type
        if ($discount->type === 'category') {
            $products = Product::where('category_id', $discount->type_id)->pluck('name');
        } elseif ($discount->type === 'vendor') {
            $products = Product::where('vendor_id', $discount->type_id)->pluck('name');
        } elseif ($discount->type === 'zone') {
            $vendorIds = Vendor::where('zone_id', $discount->type_id)->pluck('id');
            $products = Product::whereIn('vendor_id', $vendorIds)->pluck('name');
        } elseif ($discount->type === 'product') {
            $productIds = Arr::wrap(is_string($discount->product_ids) ? explode(',', $discount->product_ids) : $discount->product_ids);
            $products = Product::whereIn('id', $productIds)->pluck('name');
        } 
     
        return view('admin.discounts.edit', compact('discount', 'products'));
    }
    
    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'discount_value' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
    
        $discount->update([
            'discount_value' => $request->discount_value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
    
        return redirect()->route('admin.discounts.index')->with('success', 'Discount updated successfully!');
    }



    
    
}
