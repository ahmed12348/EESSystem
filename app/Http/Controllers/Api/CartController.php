<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Setting;
use App\Services\CartService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $user = auth()->user();
            
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
            
            $cartItem = $this->cartService->addProductToCart($user, $request->product_id, $request->quantity);

            return ApiResponse::success('تمت إضافة المنتج إلى السلة بنجاح.', ['cart_item' => $cartItem], 200);
        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'حدث خطأ أثناء إضافة المنتج إلى السلة.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getCart()
    {
        try {
            $user = auth()->user();
            $cart = $this->cartService->fetchUserCart($user);
    
            if (!$cart) {
                return ApiResponse::error(['message' => 'السلة فارغة.', 'data' => []], 404);
            }
    
            $cart->refreshExpiration();
    
            return ApiResponse::success('تم جلب بيانات السلة بنجاح.', ['cart' => $cart], 200);
        } catch (Exception $e) {
            return ApiResponse::error([
                'message' => 'حدث خطأ أثناء جلب بيانات السلة.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
