<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    // Create an order from the cart


    
    public function createOrder()
    {
        try {
            $user = auth()->user();
            $cart = $user->cart()->first();
    
            // Check if cart exists and is not expired
            if (!$cart || $cart->checkExpiration()) {
                return ApiResponse::error('سلتك منتهية الصلاحية، الرجاء إنشاء سلة جديدة.', 400);
            }
    
            // Ensure the cart has items
            if ($cart->items->isEmpty()) {
                return ApiResponse::error('لا يمكنك تقديم طلب بسلة فارغة.', 400);
            }
    
            // Create the order and calculate the total price using the model method
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'placed_at' => now(),
            ]);
    
            
    
            // Move items from cart to order
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);
                // Calculate total price and update the order
            $order->total_price = $order->calculateTotalPrice();
            $order->save();
            }
    
            // Expire the cart after order creation
            $cart->update(['status' => 0]);
    
            return ApiResponse::success('تم إنشاء الطلب بنجاح', $order, 201);
    
        } catch (\Exception $e) {
            return ApiResponse::error('حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage(), 500);
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

    // Get order details by ID
    public function getOrderDetails($id)
    {
        $user = auth()->user();
        $order = $user->orders()->with('items')->find($id);

        if (!$order) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'الطلب غير موجود.',
            ]);
        }

        return response()->json([
            'statusCode' => 200,
            'message' => 'تم استرجاع تفاصيل الطلب بنجاح.',
            'data' => $order
        ]);
    }

    // Cancel an order
    public function cancelOrder($id)
    {
        $user = auth()->user();
        $order = $user->orders()->find($id);

        if (!$order) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'الطلب غير موجود.',
            ]);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'statusCode' => 400,
                'message' => 'لا يمكن إلغاء هذا الطلب في الوقت الحالي.',
            ]);
        }

        $order->setStatus('cancelled');

        return response()->json([
            'statusCode' => 200,
            'message' => 'تم إلغاء الطلب بنجاح.',
            'data' => $order
        ]);
    }
}
