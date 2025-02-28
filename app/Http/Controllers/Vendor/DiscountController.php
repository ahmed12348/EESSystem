<?php

namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
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
    
        return view('vendor.discounts.index', compact('discounts'));
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
            $vendorIds = Vendor::where('zone', $discount->type_id)->pluck('id');
            $products = Product::whereIn('vendor_id', $vendorIds)->pluck('name');
        } elseif ($discount->type === 'product') {
            $productIds = is_string($discount->product_ids) ? explode(',', $discount->product_ids) : [(string) $discount->product_ids]; 
            $products = Product::whereIn('id', $productIds)->pluck('name');
        } 
        $products =[];
        return view('vendor.discounts.show', compact('discount', 'products'));
   
    }
    

    public function create()
    {
        $products = Product::where('vendor_id', Auth::id())
        ->where('status', 1)
        ->get();

        $categories = Category::all();
        $vendors = Vendor::all();
    
        return view('vendor.discounts.create', compact('products', 'categories', 'vendors'));
    }   
    public function store(Request $request)
    {
      
        try {
            // Validate Input Data
            $request->validate([
                'discount_value' => 'required|integer|min:1|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
               
            ]);
        
            // Initialize Product Collection
            $products = collect();
        
            // **Determine Product IDs Based on Type**
          if ($request->type === 'product') {
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
        
            return redirect()->route('vendor.discounts.index')->with('success', 'Discount created successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error creating discount: ' . $e->getMessage());
        }
        
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
            $vendorIds = Vendor::where('zone', $discount->type_id)->pluck('id');
            $products = Product::whereIn('vendor_id', $vendorIds)->pluck('name');
        } elseif ($discount->type === 'product') {
            $productIds = Arr::wrap(is_string($discount->product_ids) ? explode(',', $discount->product_ids) : $discount->product_ids);
            $products = Product::whereIn('id', $productIds)->pluck('name');
        } 
        $products =[];
        // dd()
        return view('vendor.discounts.edit', compact('discount', 'products'));
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
    
            return redirect()->route('vendor.discounts.index')->with('success', 'Discount updated successfully!');
        
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error updating discount: ' . $e->getMessage());
        }
    }
    



    
    
}
