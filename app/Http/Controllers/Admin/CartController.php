<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
        public function __construct()
    {
        $this->middleware('can:carts-view')->only(['index', 'show']);
        $this->middleware('can:carts-create')->only(['create', 'store']);
        $this->middleware('can:carts-edit')->only(['edit', 'update']);
        $this->middleware('can:carts-delete')->only(['destroy']);
    }


        // Show the form to edit the cart expiration
        public function edit($id)
        {
            $cart = Cart::findOrFail($id);
            return view('admin.carts.edit', compact('cart'));
        }
    
        // Update the cart expiration
        public function update(Request $request, $id)
        {
            $request->validate([
                'expires_at' => 'required|date|after:now', // Ensure the expiration date is in the future
            ]);
    
            $cart = Cart::findOrFail($id);
            $cart->expires_at = $request->expires_at; // Update expires_at field
            $cart->save();
    
            return redirect()->route('admin.carts.index')->with('success', 'Cart expiration time updated.');
        }
    
        public function index()
        {
            // Fetch all expired order items
            $expiredItems = CartItem::where('status', 'expired')->with('product')->get();
    
            return view('admin.carts.index', compact('expiredItems'));
        }
    
        /**
         * Re-add expired cart items by updating status back to pending.
         */
        public function readdExpiredItems(Request $request)
        {
            
            $expiredItems = CartItem::where('status', 'expired')->get();
    
            if ($expiredItems->isEmpty()) {
                return redirect()->route('admin.cart.index')->with('warning', 'No expired items to re-add.');
            }
    
            // Update the status of expired items to 'pending'
            foreach ($expiredItems as $item) {
                $item->update(['status' => 'active']);
            }
    
            return redirect()->route('admin.cart.index')->with('success', 'Expired items have been re-added.');
        }
    
}
