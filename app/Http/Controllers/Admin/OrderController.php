<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'vendor']);

        // Search functionality
        if ($request->has('search')) {
            $query->where('id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('vendor.vendorProfile', function ($q) use ($request) {
                      $q->where('business_name', 'like', '%' . $request->search . '%');
                  });
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $customers = User::where('type', 'customer')->get();
        $products = Product::with('vendor')->get();

        return view('admin.orders.create', compact('customers', 'products'));
    }

     
     public function store(Request $request)
     {
         try {
             DB::beginTransaction(); // Start transaction
     
             $request->validate([
                 'customer_id' => 'required|exists:users,id',
                 'product_ids' => 'required|array|min:1',
                 'product_ids.*' => 'exists:products,id',
                 'total_price' => 'required|numeric',
                 'status' => 'required|in:pending,completed,cancelled',
             ]);
     
             // Ensure all products belong to the same vendor
             $products = Product::whereIn('id', $request->product_ids)->get();
             $vendorIds = $products->pluck('vendor_id')->unique();
     
             if ($vendorIds->count() > 1) {
                 return back()->withErrors(['product_ids' => 'All selected products must belong to the same vendor.'])->withInput();
             }
     
             $vendorId = $vendorIds->first();
             $totalFinalPrice = 0;
     
             // Create Order
             $order = Order::create([
                 'user_id' => $request->customer_id,
                 'vendor_id' => $vendorId,
                 'total_price' => 0, // Will be updated after calculation
                 'status' => $request->status,
                 'placed_at' => now(),
             ]);
     
             // Add Order Items
             foreach ($products as $product) {
                 $discount = $product->productDiscount ?? null;
                 $discountValue = $discount ? $discount->discount_value : 0;
                 $finalPrice = $product->price - ($product->price * ($discountValue / 100));
     
                 OrderItem::create([
                     'order_id' => $order->id,
                     'product_id' => $product->id,
                     'quantity' => 1,
                     'price' => $product->price,
                     'discount' => ($product->price * ($discountValue / 100)),
                     'final_price' => $finalPrice,
                 ]);
     
                 $totalFinalPrice += $finalPrice;
             }
     
             // Update order with the correct total price
             $order->update(['total_price' => $totalFinalPrice]);
     
             DB::commit(); // Commit transaction
     
             return redirect()->route('admin.orders.index')->with('success', 'Order created successfully!');
         } catch (\Exception $e) {
             DB::rollBack(); // Rollback changes on error
             Log::error('Order creation failed: ' . $e->getMessage());
     
             return back()->withErrors(['error' => 'Failed to create order. Please try again.'])->withInput();
         }
     }
     
     

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::with('items.product', 'customer', 'vendor')->findOrFail($id);
    
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->get();
    
        $vendors = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })->get();
   
    
        // Get products from the same vendor as the order
        $products = Product::where('vendor_id', $order->vendor_id)->get(); 
        $selectedProducts = $order->items->pluck('product_id')->toArray();

        return view('admin.orders.edit', compact('order', 'customers', 'vendors', 'products','selectedProducts'));
    }
    
    public function update(Request $request, $id)
    {
        try {
            // Validate Request
            $request->validate([
                'customer_id' => 'required|exists:users,id',
                'vendor_id' => 'required|exists:users,id',
                'product_ids' => 'required|array|min:1',
                'product_ids.*' => 'exists:products,id',
                'status' => 'required|in:pending,completed,cancelled',
            ]);
    
            // Find the order
            $order = Order::findOrFail($id);
            $order->update([
                'user_id' => $request->customer_id,
                'vendor_id' => $request->vendor_id,
                'status' => $request->status,
            ]);
    
            // Get existing order item product IDs
            $existingItems = $order->items->keyBy('product_id'); // Key by product_id for easy lookup
            $newProductIds = array_map('intval', $request->product_ids);
    
            $totalFinalPrice = 0;
    
            foreach ($newProductIds as $productId) {
                $product = Product::findOrFail($productId);
    
                // Get discount
                $discount = $product->discount; 
                $price = $product->price;
                $discountValue = 0;
    
                if ($discount) {
                    if ($discount->discount_type === 'percentage') {
                        $discountValue = ($price * $discount->discount_value) / 100;
                    } else {
                        $discountValue = $discount->discount_value;
                    }
                }
    
                $finalPrice = $price - $discountValue;
    
                if (isset($existingItems[$productId])) {
                    // Update existing order item
                    $existingItems[$productId]->update([
                        'quantity' => 1, 
                        'price' => $price,
                        'discount' => $discountValue,
                        'final_price' => $finalPrice,
                    ]);
                } else {
                    // Add new order item if not exists
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => 1, 
                        'price' => $price,
                        'discount' => $discountValue,
                        'final_price' => $finalPrice,
                    ]);
                }
    
                $totalFinalPrice += $finalPrice;
            }
    
            // Remove order items that were not selected in the update
            $order->items()->whereNotIn('product_id', $newProductIds)->delete();
    
            // Update order total price
            $order->update(['total_price' => $totalFinalPrice]);
    
            return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully!');
    
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating order: ' . $e->getMessage());
        }
    }
    
    
    public function destroy(Order $order)
    {
        try {
           
            $order->items()->delete();
    
            $order->delete();
            return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }
}
