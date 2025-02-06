<?php

namespace App\Http\Controllers\Api;

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


    public function createOrder(Request $request)
    {
        $user = Auth::user(); // Get authenticated user
    
        if (!$user) {
            return response()->json([
                'statusCode' => 401,
                'message' => 'فشل التحقق من البيانات.', 
            ]);
            
        }
    
        // Retrieve the user's cart items
        $cartItems = $user->cart()->get(); 
    
        if ($cartItems->isEmpty()) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'سلتك فارغة.', 
            ]);
         
        }
    
        // Process order creation using cart items
        $orderData = [
            'user_id' => $user->id,
            'total_price' => $cartItems->sum('price'), // Assuming cart has a 'price' field
            'status' => 'pending',
        ];
    
        $order = Order::create($orderData); // Save order
    
        // Attach cart items to the order
        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }
    
        // Optionally, clear the cart after order creation
        $user->cart()->delete();
    
        return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
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
