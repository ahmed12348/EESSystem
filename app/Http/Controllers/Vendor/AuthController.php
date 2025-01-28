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
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->business_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                 'otp' => '1234',
                // 'otp' => rand(1000, 9999), // Generate random OTP
                'is_verified' => 'no', 
                'is_approved' => 'no', // Admin approval required
            ]);

            if (!Role::where('name', 'vendor')->exists()) {
                Role::create(['name' => 'vendor']);
            }
            $user->assignRole('vendor');

            Vendor::create([
                'user_id' => $user->id,
                'business_name' => $request->business_name,
                'business_number' => $request->business_number,
                'city' => $request->city,
                'state' => $request->state,
                'zone' => $request->zone,
            ]);

            DB::commit();

            // Redirect to OTP verification
            return redirect()->route('vendor.otp.form', ['phone' => $user->phone]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    // Show OTP Verification Form
    public function showOtpForm(Request $request)
    {
        return view('vendor.auth.otp', ['phone' => $request->phone]);
    }

    public function verifyOtp(Request $request)
    {
        // Validate OTP input
        $request->validate([
            // 'phone' => 'required|string', // Ensure phone is provided
            'otp' => 'required|string',
        ]);
    
        // Retrieve user by phone number
        $user = User::where('phone', $request->phone)->first();
    
        // Check if user exists and OTP is correct
        if (!$user || $user->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }
    
        // Update user as verified
        $user->update(['is_verified' => 'yes']);
    
        // Redirect to login with success message
        return redirect()->route('vendor.login')->with('success', 'OTP verified successfully. Awaiting admin approval.');
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
            'phone' => 'required|string',
            'password' => 'required',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'Phone number not found.']);
        }

        if ($user->is_verified == 'no') {
            return redirect()->route('vendor.otp.form', ['phone' => $user->phone]);
        }

        if ($user->status == 'inactive') {
            return back()->withErrors(['phone' => 'Admin has not approved your account yet.']);
        }
      
        if ($user->roles->first()->name !== 'vendor') {
            return back()->withErrors(['phone' => 'failed Login.']);
        }

        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            return redirect()->route('vendor.vendor_index');
        }

        return back()->withErrors(['login' => 'Authentication failed.']);
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('vendor.login');
    }


}
