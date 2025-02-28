<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('vendor.auth.register');
    }

    // Register Vendor
    public function register(Request $request)
    {
     
        // Validate input
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
        ]);
    
        // If validation fails, return errors to the user
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        // Start transaction to ensure database consistency
        DB::beginTransaction();
    
        try {
            // Create a new user with type as 'vendor'
            $user = User::create([
                'name' => $request->business_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'type' => 'vendor', // Set the type as vendor
                // 'is_verified' => 1, 
                // 'status' => 'active',
                
            ]);
    
            // Create the associated vendor record
            Vendor::create([
                'user_id' => $user->id,
                'business_name' => $request->business_name,
                 'tax_id' => $request->business_number,
            ]);
    
            DB::commit();
    
            return redirect()->route('vendor.login')->with('success', 'Register successfully. Awaiting admin approval.');
    
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            
            // DB::rollBack();
            // Return back with error message
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    

    // Show Login Form
    public function showLoginForm()
    {
        return view('vendor.auth.login');
    }

    // Login Logic
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
     
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.']);
        }

        // if ($user->is_verified == 'no') {
        //     return redirect()->route('vendor.otp.form', ['email' => $user->phone]);
        // }

        if ($user->status !== 'approved') {
            return back()->withErrors(['email' => 'Admin has not approved your account yet.']);
        }
      
        if ($user->type !== 'vendor') {
            return back()->withErrors(['email' => 'failed Login.']);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('vendor.vendor_index');
        }
        return back()->withErrors(['email' => 'Invalid credentials.']);
        // return back()->withErrors(['login' => 'Authentication failed.']);
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('vendor.login');
    }


}
