<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:orders-view')->only(['index', 'show']);
        $this->middleware('can:orders-create')->only(['create', 'store']);
        $this->middleware('can:orders-edit')->only(['edit', 'update']);
        $this->middleware('can:orders-delete')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $vendorId = Auth::id(); 
    
        $query = Order::where('vendor_id', $vendorId)->with(['customer']);
    
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('customer', function ($subQuery) use ($searchTerm) {
                      $subQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }
    
        $orders = $query->latest()->paginate(10);
    
        return view('vendor.orders.index', compact('orders'));
    }
    

    public function show(Order $order)
    {
        // $this->authorizeVendorOrder($order);
        return view('vendor.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $vendorId = Auth::id();
        $order = Order::with('items.product', 'customer')->where('vendor_id', $vendorId)->findOrFail($id);

        $customers = User::where('type', 'customer')->get();
        $products = Product::where('vendor_id', $vendorId)->get();
        $selectedProducts = $order->items->pluck('product_id')->toArray();

        return view('vendor.orders.edit', compact('order', 'customers', 'products', 'selectedProducts'));
    }

    public function update(Request $request, $id)
    {
       
        try {
            DB::beginTransaction();

            $vendorId = Auth::id();
            $order = Order::where('vendor_id', $vendorId)->findOrFail($id);

            $request->validate([
                'status' => 'nullable|in:pending,completed,cancelled',
            ]);

            $order->update([
                'status' => $request->status,
            ]);

            DB::commit();
            return redirect()->route('vendor.orders.index')->with('success', 'Order updated successfully!');
        } catch (\Exception $e) {
         
            DB::rollBack();
            return back()->with('error', 'Error updating order: ' . $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        // $this->authorizeVendorOrder($order);
        try {
            $order->items()->delete();
            $order->delete();
            return redirect()->route('vendor.orders.index')->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }

    private function authorizeVendorOrder(Order $order)
    {
        if ($order->vendor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}

