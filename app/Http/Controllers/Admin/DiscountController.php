<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:discounts-view')->only(['index', 'show']);
        $this->middleware('can:discounts-create')->only(['create', 'store']);
        $this->middleware('can:discounts-edit')->only(['edit', 'update']);
        $this->middleware('can:discounts-delete')->only(['destroy']);
    }
    
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
        
        // Initialize products as an empty collection
        $products = collect();
        
        // Fetch categories and vendors for dropdowns (if needed in the view)
        $categories = Category::all();
        $vendors = Vendor::all();
    
        // Always fetch product IDs from the discount (no matter the type)
        $productIds = Arr::wrap(is_string($discount->product_ids) ? explode(',', $discount->product_ids) : $discount->product_ids);
    
        // Fetch the products associated with the discount by product_ids
        $products = Product::whereIn('id', $productIds)->get();
        
        // Return the view with discount, products, categories, and vendors
        return view('admin.discounts.show', compact('discount', 'products', 'categories', 'vendors'));
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
            $validator = Validator::make($request->all(), [
                'discount_value' => 'required|integer|min:1|max:100',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'type' => 'required|in:product,category,vendor,zone',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            $products = collect();
    
            if ($request->type === 'category') {
                if (!$request->has('category_id')) {
                    return redirect()->back()->with('error', "Category ID is required for category type.")->withInput();
                }
                $products = Product::where('category_id', $request->category_id)->pluck('id');
            } elseif ($request->type === 'vendor') {
                if (!$request->has('vendor_id')) {
                    return redirect()->back()->with('error', "Vendor ID is required for vendor type.")->withInput();
                }
                $products = Product::where('vendor_id', $request->vendor_id)->pluck('id');
            } elseif ($request->type === 'zone') {
                if (!$request->has('zone')) {
                    return redirect()->back()->with('error', "Zone ID is required for zone type.")->withInput();
                }
                $vendorIds = Vendor::where('zone', $request->zone)->pluck('id');
                $products = Product::whereIn('vendor_id', $vendorIds)->pluck('id');
            } elseif ($request->type === 'product') {
                if (!$request->has('product_id')) {
                    return redirect()->back()->with('error', "Product IDs are required for product type.")->withInput();
                }
                $products = collect(explode(',', $request->product_id));
            }
    
            // Convert Product IDs to String
            $productIdsString = $products->implode(',');
    
            // Create Discount
            Discount::create([
                'discount_value' => $request->discount_value,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'type' => $request->type,
                'type_id' => $request->input("{$request->type}_id"),
                'product_ids' => $productIdsString,
            ]);
    
            return redirect()->route('admin.discounts.index')->with('success', 'Discount created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error creating discount: ' . $e->getMessage());
        }
    }
    
    
    
    
    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        
        // Initialize products as an empty collection
        $products = collect();
        
        // Fetch categories and vendors for dropdowns (if needed in the view)
        $categories = Category::all();
        $vendors = Vendor::all();
    
        // Always fetch product IDs from the discount (no matter the type)
        $productIds = Arr::wrap(is_string($discount->product_ids) ? explode(',', $discount->product_ids) : $discount->product_ids);
    
        // Fetch the products associated with the discount by product_ids
        $products = Product::whereIn('id', $productIds)->get();
        
        // Return the view with discount, products, categories, and vendors
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
                'type_id' => $typeId,  // Ensure type_id is correctly updated
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
