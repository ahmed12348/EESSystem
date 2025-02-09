<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Discount;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Jobs\ExpireCartItems;
use Carbon\Carbon;
use Exception;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);
    
            $user = auth()->user();
            $product = Product::where('id', $request->product_id)->where('status', 1)->first();
    
            if (!$product) {
                return ApiResponse::error(['message' => 'هذا المنتج غير متاح حالياً.', 'data' => []], 404);
            }
    
            $quantity = $request->quantity;
    
            // Validate min/max order quantity
            if ($quantity < $product->min_order_quantity || ($product->max_order_quantity && $quantity > $product->max_order_quantity)) {
                return ApiResponse::error([
                    'message' => 'يجب أن تكون الكمية بين ' . $product->min_order_quantity . ' و ' . ($product->max_order_quantity ?? 'غير محدود') . '.', 
                    'data' => []
                ], 400);
            }
    
            // Get expiration time from settings
            $cartExpirationHours = Setting::getValue('cart_expiration_hours', 2);
            $newExpirationTime = Carbon::now()->addHours($cartExpirationHours);
    
            // Find or create a cart for the user
            $cart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['expires_at' => $newExpirationTime, 'status' => 1]
            );
    
            $cart->refreshExpiration();
            $cart->update(['expires_at' => $newExpirationTime]);
    
            // **🔍 Validate if new product vendor matches existing cart items**
            $existingCartItem = CartItem::where('cart_id', $cart->id)
                ->where('status', 'active')
                ->with('product')
                ->first();
    
            if ($existingCartItem && $existingCartItem->product->vendor_id !== $product->vendor_id) {
                return ApiResponse::error([
                    'message' => 'لا يمكنك إضافة منتجات من بائعين مختلفين إلى السلة.', 
                    'data' => []
                ], 400);
            }
    
            // Get applicable discount
            $discount = $this->getApplicableDiscount($product);
            $discountPercentage = $discount ? $discount->discount_value : 0;
    
            // Calculate prices
            $totalPrice = $product->price * $quantity;
            $totalDiscount = ($product->price * ($discountPercentage / 100)) * $quantity;
            $finalPrice = $totalPrice - $totalDiscount;
    
            // Check if item already exists in cart
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->where('status', 'active')
                ->first();
    
            if ($cartItem) {
                // Update quantity and recalculate price
                $newQuantity = $cartItem->quantity + $quantity;
                if ($newQuantity > $product->max_order_quantity) {
                    return ApiResponse::error([
                        'message' => 'تجاوزت الحد الأقصى للطلب.', 
                        'data' => []
                    ], 400);
                }
    
                $newTotalPrice = $product->price * $newQuantity;
                $newTotalDiscount = ($product->price * ($discountPercentage / 100)) * $newQuantity;
                $newFinalPrice = $newTotalPrice - $newTotalDiscount;
    
                $cartItem->update([
                    'quantity' => $newQuantity,
                    'price' => $product->price,
                    'discount' => $newTotalDiscount,
                    'final_price' => $newFinalPrice
                ]);
            } else {
                // Create new cart item
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'discount' => $totalDiscount,
                    'final_price' => $finalPrice,
                    'status' => 'active'
                ]);
            }
    
            // Reload cart items
            $cart->load(['items' => function ($query) {
                $query->where('status', 'active'); // Only fetch active items
            }, 'items.product']);
    
            return response()->json([
                'statusCode' => 200,
                'message' => [
                    'message' => 'تمت إضافة المنتج إلى السلة بنجاح.',
                    'data' => [
                        'cart' => [
                            'id' => $cart->id,
                            'expires_at' => $cart->expires_at, // Always updated
                            'status' => $cart->status,
                            'total_final_price' => (float) $cart->items->sum('final_price'), // Corrected sum
                            'items' => $cart->items->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'product_id' => $item->product_id,
                                    'product_name' => $item->product?->name,
                                    'quantity' => $item->quantity,
                                    'price' => (float) $item->price, // ✅ Ensure correct float value
                                    'discount' => (float) $item->discount,
                                    'final_price' => (float) $item->final_price, // ✅ Ensure correct float value
                                    'status' => $item->status,
                                ];
                            })
                        ]
                    ]
                ]
            ], 200);
        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'حدث خطأ أثناء إضافة المنتج إلى السلة.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    private function getApplicableDiscount(Product $product)
    {
        // Check for product-specific discount
        $productDiscount = Discount::where('product_id', $product->id)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->first();

        if ($productDiscount) return $productDiscount;

        // Check for category discount
        return Discount::where('category_id', $product->category_id)
            ->whereNull('product_id')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->first();
    }

    public function getCart()
    {
        try {
            $user = auth()->user();
    
            $cart = Cart::where('user_id', $user->id)
                ->where('status', 1)
                ->with(['items' => function ($query) {
                    $query->where('status', 'active'); // Fetch only active items
                }, 'items.product'])
                ->first();
    
            if (!$cart) {
                return ApiResponse::error(['message' => 'السلة فارغة.', 'data' => []], 404);
            }
    
            $cart->refreshExpiration();
    
            $totalFinalPrice = 0;
            $cartItems = [];
    
            foreach ($cart->items as $item) {
                if (!$item->product) continue; // Skip if product is missing
    
                // Recalculate discount dynamically
                $discount = $this->getApplicableDiscount($item->product);
                $discountPercentage = $discount ? $discount->discount_value : 0;
    
                $totalPrice = $item->product->price * $item->quantity;
                $totalDiscount = ($item->product->price * ($discountPercentage / 100)) * $item->quantity;
                $finalPrice = $totalPrice - $totalDiscount;
    
                // Update the item with the new discount and final price
                $item->update([
                    'discount' => $totalDiscount,
                    'final_price' => $finalPrice,
                ]);
    
                $totalFinalPrice += $finalPrice;
    
                $cartItems[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => (float) $item->product->price,
                    'discount' => (float) $totalDiscount,
                    'final_price' => (float) $finalPrice,
                    'status' => $item->status,
                ];
            }
    
            return ApiResponse::success('تم جلب بيانات السلة بنجاح.', [
                'cart' => [
                    'id' => $cart->id,
                    'expires_at' => $cart->expires_at,
                    'status' => $cart->status,
                    'total_final_price' => (float) $totalFinalPrice,
                    'items' => $cartItems,
                ]
            ], 200);
        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'حدث خطأ أثناء جلب بيانات السلة.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
