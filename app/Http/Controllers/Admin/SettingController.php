<?php

namespace App\Http\Controllers\Admin;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
  
        // Show the settings page
        public function index()
        {
            $expirationHours = Setting::getValue('cart_expiration_hours', 2); // Default to 2 hours
            return view('admin.settings.cart', compact('expirationHours'));
        }
    
        // Update expiration setting
        public function update(Request $request)
        {
            $request->validate([
                'cart_expiration_hours' => 'required|integer|min:1',
            ]);
    
            Setting::setValue('cart_expiration_hours', $request->cart_expiration_hours);
    
            return redirect()->route('admin.settings.cart')->with('success', 'Cart expiration time updated.');
        }
    
    
}
