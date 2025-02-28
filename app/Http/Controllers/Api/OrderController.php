<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    public function createOrder()
    {
        try {
            $user = auth()->user();

            $cart = Cart::where('user_id', $user->id)
                ->where('status', 1)
                ->with(['items' => function ($query) {
                    $query->where('status', 'active'); 
                }, 'items.product'])
                ->first();
    
            if (!$cart || $cart->items->isEmpty()) {
                return ApiResponse::error(['message' => 'السلة فارغة أو جميع العناصر غير متاحة.'], 400);
            }
    
            // Group cart items by vendor
            $itemsByVendor = $cart->items->groupBy(function ($item) {
                return $item->product->vendor_id ?? null;
            });
    
            $createdOrders = [];
            
            foreach ($itemsByVendor as $vendorId => $items) {
                if (!$vendorId) continue; // Ignore items without a vendor
    
                // Create a new order for this vendor
                $order = Order::create([
                    'user_id' => $user->id,
                    'vendor_id' => $vendorId, 
                    'total_price' => 0, // Will update after calculating price
                    'status' => 'pending',
                    'placed_at' => Carbon::now(),
                ]);
    
                $totalFinalPrice = 0;
                $orderItems = [];
  
                foreach ($items as $item) {
                    if ($item->status !== 'approved' || !$item->product) {
                        continue;
                    }
    
                    // Create Order Item without vendor_id
                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'discount' => $item->discount,
                    ]);
    
                    if (!$orderItem) {
                        throw new \Exception('فشل في حفظ عنصر الطلب.');
                    }
    
                    $totalFinalPrice += $item->final_price;
    
                    $orderItems[] = [
                        'id' => $orderItem->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name ?? 'غير معروف',
                        'quantity' => $item->quantity,
                        'price' => (float) $item->price,
                        'discount' => (float) $item->discount,
                        'final_price' => (float) $item->final_price,
                    ];
    
                    // Mark cart item as ordered
                    $item->update(['status' => 'ordered']);
                }
    
                // Update order with total price
                $order->update(['total_price' => $totalFinalPrice]);
    
                // Add to response
                $createdOrders[] = [
                    'id' => $order->id,
                    'vendor_id' => $vendorId, // ✅ Vendor ID is stored in Order, not OrderItem
                    'status' => $order->status,
                    'placed_at' => $order->placed_at,
                    'total_price' => (float) $order->total_price,
                    'items' => $orderItems
                ];
            }
    
            return ApiResponse::success('تم إنشاء الطلبات بنجاح.', [
                'orders' => $createdOrders
            ]);
    
        } catch (\Exception $e) {
            return ApiResponse::error([
                'خطأ أثناء إنشاء الطلب.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    
    
    
    // Get all orders for the user
    public function getOrders()
    {
        $user = auth()->user();
        $orders = $user->orders()->with('items')->get();

        return response()->json([
            'statusCode' => 200,
            'message' => 'تم استرجاع الطلبات بنجاح.',
            'data' => $orders
        ]);
    }

    public function getOrderDetails($id)
    {
        $user = auth()->user();

        // Fetch order with items and product details
        $order = $user->orders()
            ->with(['items.product']) 
            ->find($id);

        if (!$order) {
            return ApiResponse::error(['message' => 'الطلب غير موجود.'], 404);
        }

        return ApiResponse::success('تم استرجاع تفاصيل الطلب بنجاح.', [
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'placed_at' => $order->placed_at,
                'total_price' => (float) $order->total_price,
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name ?? 'غير معروف',
                        'vendor_id' => $item->product?->vendor_id,
                        'quantity' => $item->quantity,
                        'price' => (float) $item->price,
                        'discount' => (float) $item->discount,
                        'final_price' => (float) $item->final_price,
                    ];
                }),
            ]
        ]);
    }
    public function cancelOrder($id)
    {
        $user = auth()->user();
        $order = $user->orders()->with('items')->find($id);
    
        if (!$order) {
            return ApiResponse::error(['message' => 'الطلب غير موجود.'], 404);
        }
    
        if ($order->status !== 'pending') {
            return ApiResponse::error(['message' => 'لا يمكن إلغاء هذا الطلب في الوقت الحالي.'], 400);
        }
    
        // Cancel the order
        $order->update(['status' => 'cancelled']);
    
        return ApiResponse::success('تم إلغاء الطلب بنجاح.', [
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'total_price' => (float) $order->total_price,
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => (float) $item->price,
                        'discount' => (float) $item->discount,
                        'final_price' => (float) $item->final_price,
                    ];
                }),
            ]
        ]);
    }
    
}
