<?php

namespace App\Repositories;

use App\Helpers\ApiResponse;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Setting;
use Carbon\Carbon;

class CartRepository
{
    public function getOrCreateCart($userId)
    {
        $cart = Cart::where('user_id', $userId)->where('status', 1)->first();

        if (!$cart) {
            $cartExpirationHours = Setting::getValue('cart_expiration_hours', 2);
            $cart = Cart::create([
                'user_id' => $userId,
                'expires_at' => Carbon::now()->addHours($cartExpirationHours),
                'status' => 1
            ]);
        } else {
            $cart->update(['expires_at' => Carbon::now()->addHours(Setting::getValue('cart_expiration_hours', 2))]);
        }

        return $cart;
    }

    public function checkVendorConsistency($cart, $product)
    {
        $existingCartItem = CartItem::where('cart_id', $cart->id)
            ->where('status', 'active')
            ->with('product')
            ->first();

        return !$existingCartItem || $existingCartItem->product->vendor_id === $product->vendor_id;
    }

    public function addOrUpdateCartItem($cartId, $product, $quantity, $priceDetails)
    {
        $cartItem = CartItem::where('cart_id', $cartId)
            ->where('product_id', $product->id)
            ->where('status', 'active')
            ->first();
          
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
           
            if ($newQuantity > $product->max_order_quantity) {
                return ApiResponse::error([
                    'message' => 'تجاوزت الحد الأقصى للطلب.',
                    'data' => []
                ], 400);
            }

            $newPriceDetails = $product->calculateFinalPrice($newQuantity);
            // dd($newPriceDetails);
            $cartItem->update([
                'quantity' => $newQuantity,
                'price' => $product->price,
                'discount' => $newPriceDetails['total_discount'],
                'final_price' => $newPriceDetails['final_price']
            ]);
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cartId,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'discount' => $priceDetails['total_discount'],
                'final_price' => $priceDetails['final_price'],
                'status' => 'active'
            ]);
        }

        return $cartItem;
    }
}

