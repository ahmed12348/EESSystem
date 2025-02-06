<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    
    public function createCart(Request $request)
    {
        $user = auth()->user();

        $cart = $user->cart()->orderBy('id', 'desc')->first();

        if (!$cart || $cart->checkExpiration()) {
            // Create a new cart with updated expiration time
            $cart = Cart::create([
                'user_id' => $user->id,
                // 'expires_at' => Carbon::now()->addMinutes(Setting::getValue('cart_expiration_minutes', 60)), // Dynamic expiration from settings
                'status' => 1
            ]);

            return response()->json([
                'statusCode' => 200,
                'message' => 'تم إضافة سلة جديدة.',
                'data' => $cart
            ]);
        }

        return response()->json([
            'statusCode' => 400,
            'message' => 'لديك سلة بالفعل لم تنتهِ صلاحيتها بعد.',
            'data' => $cart
        ]);
    }
    
    public function addItemToCart(Request $request)
    {
        $user = auth()->user();
        $cart = $user->cart()->orderBy('id', 'desc')->first();

        // Check if cart is expired
        if (!$cart || $cart->checkExpiration()) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'سلتك منتهية الصلاحية أو غير موجودة.', // Arabic message for expired cart
            ]);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($request->product_id);

        // Add the item to the cart
        $cartItem = $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price * $request->quantity,
        ]);

        return response()->json([
            'statusCode' => 200,
            'message' => 'تم إضافة العنصر إلى السلة.',
            'data' => $cartItem
        ]);
    }

    public function viewCart(Request $request)
    {
        $user = auth()->user();
        $cart = $user->cart()->orderBy('id', 'desc')->first();
    
        if (!$cart || $cart->checkExpiration()) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'سلتك منتهية الصلاحية أو غير موجودة.',
            ]);
        }
    
        // Calculate total price of all items in the cart
        $total = $cart->items->sum(function ($item) {
            return $item->price;
        });
    
        return response()->json([
            'statusCode' => 200,
            'message' => 'تم استرجاع سلتك بنجاح.',
            'data' => [
                'cart' => $cart,
                'items' => $cart->items,
                'total' => $total, 
            ]
        ]);
    }
    

 
}
