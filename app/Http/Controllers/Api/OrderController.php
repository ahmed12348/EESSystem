<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Place an order
    public function createOrder(Request $request)
    {
        $user = auth()->user();
        $cart = $user->cart()->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Cart is empty or not found.',
            ]);
        }

        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'vendor_id' => $cart->items->first()->product->vendor_id,
            'status' => 'pending', // Default status
            'total_price' => $cart->items->sum('price'),
            'placed_at' => now(),
        ]);

        // Create order items
        foreach ($cart->items as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
            ]);
        }

        // Mark cart as completed
        $cart->delete(); // Or you can add a status like 'completed'

        return response()->json([
            'statusCode' => 200,
            'message' => 'Order created successfully.',
            'data' => [
                'order' => $order,
                'order_items' => $order->items,
            ]
        ]);
    }

  
}
