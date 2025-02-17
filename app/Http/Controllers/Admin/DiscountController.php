<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
    
        $discounts = Discount::latest()
            ->when($search, function ($query) use ($search) {
                return $query->where('title', 'like', "%{$search}%");
            })->paginate(10);
    
        return view('admin.discounts.index', compact('discounts'));
    }
    

    public function show($id)
    {
        $discount = Discount::findOrFail($id);
        $products = collect(); // Initialize to prevent undefined variable error
    
        // Fetch related products
        if ($discount->type === 'category') {
            $products = Product::where('category_id', $discount->type_id)->get();
        } elseif ($discount->type === 'vendor') {
            $products = Product::where('vendor_id', $discount->type_id)->get();
        } elseif ($discount->type === 'zone') {
            $vendorIds = Vendor::where('zone', $discount->type_id)->pluck('id');
            $products = Product::whereIn('vendor_id', $vendorIds)->get();
        } elseif ($discount->type === 'product') {
            $productIds = Arr::wrap(is_string($discount->product_ids) ? explode(',', $discount->product_ids) : $discount->product_ids);
            $products = Product::whereIn('id', $productIds)->get();
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

        try {
            // Validate Input Data
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
                if (!$request->has('category_id')) {
                    throw new \Exception("Category ID is required for category type.");
                }
                $products = Product::where('category_id', $request->category_id)->pluck('id');
            } elseif ($request->type === 'vendor') {
                if (!$request->has('vendor_id')) {
                    throw new \Exception("Vendor ID is required for vendor type.");
                }
                $products = Product::where('vendor_id', $request->vendor_id)->pluck('id');
            } elseif ($request->type === 'zone') {
                if (!$request->has('zone_id')) {
                    throw new \Exception("Zone ID is required for zone type.");
                }
                $vendorIds = Vendor::where('zone', $request->zone_id)->pluck('id');
                $products = Product::whereIn('vendor_id', $vendorIds)->pluck('id');
            } elseif ($request->type === 'product') {
                if (!$request->has('product_id')) {
                    throw new \Exception("Product IDs are required for product type.");
                }
                // Convert comma-separated product IDs to a collection
                $products = collect(explode(',', $request->product_id));
            }
        
            $productIdsString = $products->implode(',');
        
            Discount::create([
                'discount_value' => $request->discount_value,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'type' => $request->type,
                'type_id' => $request->input("{$request->type}_id"),
                'product_ids' => $productIdsString,
            ]);
        
            return redirect()->route('admin.discounts.index')->with('success', 'Discount created successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error creating discount: ' . $e->getMessage());
        }
        
    }
    
    
    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        $products = collect(); // Initialize empty collection
    
        // Fetch categories, vendors, and all products for dropdowns
        $categories = Category::all();
        $vendors = Vendor::all();
        
        // Fetch product names based on discount type
        if ($discount->type === 'category') {
            $products = Product::where('category_id', $discount->type_id)->get();
        } elseif ($discount->type === 'vendor') {
            $products = Product::where('vendor_id', $discount->type_id)->get();
        } elseif ($discount->type === 'zone') {
            $vendorIds = Vendor::where('zone', $discount->type_id)->pluck('id');
            $products = Product::whereIn('vendor_id', $vendorIds)->get();
        } elseif ($discount->type === 'product') {
            $productIds = Arr::wrap(is_string($discount->product_ids) ? explode(',', $discount->product_ids) : $discount->product_ids);
            $products = Product::whereIn('id', $productIds)->get();
        } 
    
        return view('admin.discounts.edit', compact('discount', 'products', 'categories', 'vendors'));
    }
    

    public function update(Request $request, Discount $discount)
    {

        try {
            // Validate request data
            $request->validate([
                'discount_value' => 'required|numeric|min:1|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
 
            ]);
    
            // Determine type_id dynamically
            $typeId = $request->input("{$request->type}_id");
    
            // Update discount
            $discount->update([
                'discount_value' => $request->discount_value,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);
    
            return redirect()->route('admin.discounts.index')->with('success', 'Discount updated successfully!');
        
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error updating discount: ' . $e->getMessage());
        }
    }
    

    public function destroy($id)
    {
        try {
            $discount = Discount::findOrFail($id);
    
            // Delete the discount record
            $discount->delete();
    
            return redirect()->route('admin.discounts.index')->with('success', 'Discount deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting discount: ' . $e->getMessage());
        }
    }
    

    
    
}
