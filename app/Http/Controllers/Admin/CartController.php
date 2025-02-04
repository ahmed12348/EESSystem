<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{

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
    
    
}
