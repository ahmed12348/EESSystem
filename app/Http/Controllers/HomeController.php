<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    
        return view('admin.index');
    }

    public function vendor_index()
    {
           // Get the vendor's ID
           $vendorId = Auth::id(); 

           // Fetch total orders for the vendor
           $totalOrders = Order::where('vendor_id', $vendorId)->count();
   
         
           $totalRevenue = Order::where('vendor_id', $vendorId)->sum('total_price');
   
           $totalCustomers = User::whereHas('orders', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
           })->whereHas('roles', function ($query) {
            $query->where('name', 'customer'); // Filter by the customer role
           })->distinct()->count();
   
           // Fetch total views for the vendor (You can link it to products or use a dedicated views table)
            $totalViews = Product::where('vendor_id', $vendorId)->sum('id'); // Assuming a 'views' column in the products table
   
           // Return view with dynamic data
           return view('vendor.index', compact('totalOrders', 'totalRevenue', 'totalCustomers', 'totalViews'));
       }
 

    public function showProfile()
    {
        $user = Auth::user(); // Get authenticated user

        // Ensure vendor details are loaded
        $vendor = Vendor::where('user_id', $user->id)->first();
    
        if (!$vendor) {
            return redirect()->back()->with('error', 'Vendor profile not found.');
        }
    
        return view('vendor.profile', compact('user', 'vendor'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
    
            // Validation Rules
            $validator = Validator::make($request->all(), [
                'name'             => 'nullable|string|max:255',
                'password'         => 'nullable|string|min:6|confirmed',
                'profile_picture'  => 'nullable|image|max:2048',
                'business_name'    => 'nullable|string|max:255',
                'business_number'  => 'nullable|string|max:255',
                'zone'             => 'nullable|string|max:255',
            ]);
    
            if ($validator->fails()) {
                return redirect()->route('vendor.profile.show')
                    ->withErrors($validator)
                    ->withInput();
            }
    
         
    
            // **Update Profile Picture**
            if ($request->hasFile('profile_picture')) {
                $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                if ($user->profile_picture && Storage::exists($user->profile_picture)) {
                    Storage::delete($user->profile_picture);
                }
            } else {
                $imagePath = null; 
            }
    
            $user->profile_picture = $imagePath;

            if ($user->vendor) {
                $vendor = $user->vendor; // Assuming the relation is `user()->hasOne(Vendor::class)`
    
                if ($request->has('business_name')) {
                    $vendor->business_name = $request->business_name;
                }
                if ($request->has('business_number')) {
                    $vendor->business_number = $request->business_number;
                }
               
                if ($request->has('zone')) {
                    $vendor->zone = $request->zone;
                }
    
                $vendor->save();
            }
    
            $user->save();
    
            return redirect()->route('vendor.profile.show')->with('success', 'Profile updated successfully.');
    
        } catch (\Exception $e) {
            return redirect()->route('vendor.profile.show')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    
}
