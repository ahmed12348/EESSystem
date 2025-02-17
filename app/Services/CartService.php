<?php
namespace App\Services;

use App\Helpers\ApiResponse;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\CartRepository;

class CartService
{
    protected $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function addProductToCart($user, $productId, $quantity)
    {
        // Fetch product
        $product = Product::where('id', $productId)->where('status', 1)->first();
        if (!$product) {
            return ApiResponse::error(['message' => 'هذا المنتج غير متاح حالياً.', 'data' => []], 404);
        }

        // Validate min/max order quantity
        if ($quantity < $product->min_order_quantity || ($product->max_order_quantity && $quantity > $product->max_order_quantity)) {
            return ApiResponse::error([
                'message' => 'يجب أن تكون الكمية بين ' . $product->min_order_quantity . ' و ' . ($product->max_order_quantity ?? 'غير محدود') . '.',
                'data' => []
            ], 400);
        }

        // Get or create a cart for the user
        $cart = $this->cartRepository->getOrCreateCart($user->id);
       
        $priceDetails = $product->calculateFinalPrice($quantity);
    
        // Validate vendor consistency
        if (!$this->cartRepository->checkVendorConsistency($cart, $product)) {
            return ApiResponse::error([
                'message' => 'لا يمكنك إضافة منتجات من بائعين مختلفين إلى السلة.',
                'data' => []
            ], 400);
        }

        // Get applicable discount
        $priceDetails = $product->calculateFinalPrice($quantity);

        return $this->cartRepository->addOrUpdateCartItem($cart->id, $product, $quantity, $priceDetails);
    }

    public function fetchUserCart($user)
    {
        $cart = Cart::where('user_id', $user->id)
            ->where('status', 1)
            ->with(['items' => function ($query) {
                $query->where('status', 'active');
            }, 'items.product'])
            ->first();

        if ($cart) {
            $cart->refreshExpiration();
        }

        return $cart;
    }
}

