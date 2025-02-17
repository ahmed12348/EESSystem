<?php

namespace App\Http\Controllers;

use App\Exports\AdminDashboardExport;
use App\Exports\VendorDashboardExport;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

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

    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_price') ?: 0;
        $totalCustomers = User::whereHas('orders')->count();
        $totalProducts = Product::count();
        $averageOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;
        $pendingOrders = Order::where('status', 'pending')->count();
    
        // Fetch latest orders
        $orders = Order::with(['customer', 'vendor'])
            ->latest()
            ->limit(10)
            ->get();
    
            $topProducts = Product::withCount('sales') // Count sales per product
            ->orderByDesc('sales_count') // Order by most sold
            ->limit(5)
            ->get();
    
        // Fetch top 5 vendors by total revenue
        $topVendors = Vendor::select('vendors.*')
            ->withSum('products as total_revenue', 'price')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();
    
        return view('admin.index', compact(
            'totalOrders', 'totalRevenue', 'totalCustomers', 'totalProducts', 'averageOrderValue',
            'pendingOrders', 'orders', 'topProducts', 'topVendors'
        ));
    }
    
    

    public function vendor_index()
    {
        $vendorId = Auth::id();
    
        // Fetch total data
        $totalOrders = Order::where('vendor_id', $vendorId)->count();
        $totalRevenue = Order::where('vendor_id', $vendorId)->sum('total_price') ?: 0; 
        $totalCustomers = User::whereHas('orders', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->distinct()->count();
        $totalProducts = Product::where('vendor_id', $vendorId)->count();
        $averageOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;
    
        // Fetch count of pending orders
        $pendingOrders = Order::where('vendor_id', $vendorId)
            ->where('status', 'pending')
            ->count();
    
        // Fetch latest orders
        $orders = Order::where('vendor_id', $vendorId)
            ->with('customer')
            ->latest()
            ->limit(10)
            ->get();
    
        // Fetch top 5 selling products by sales count
        $topProducts = Product::where('vendor_id', $vendorId)
            ->withCount('orderItems') // Assuming each product has many `orderItems`
            ->orderByDesc('order_items_count')
            ->limit(5)
            ->get();
    
        // Fetch top 5 customers by total spending
         $topCustomers = User::whereHas('orders', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })
        ->withSum(['orders' => function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        }], 'total_price')
        ->orderByDesc('orders_sum_total_price')
        ->limit(5)
        ->get();
    
        return view('vendor.index', compact(
            'totalOrders', 'totalRevenue', 'totalCustomers', 'totalProducts', 'averageOrderValue',
            'pendingOrders', 'orders', 'topProducts', 'topCustomers'
        ));
    }
    
    public function export()
    {
        return Excel::download(new VendorDashboardExport, 'vendor_dashboard.xlsx');
    }
    
    public function export_admin()
    {
        return Excel::download(new AdminDashboardExport, 'admin_dashboard.xlsx');
    }
    
    public function showProfile()
    {
        $user = Auth::user();
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
                'photo'  => 'nullable|image|max:2048',
                'business_name'    => 'nullable|string|max:255',
                'tax_id'  => 'nullable|string|max:255',
                'zone'             => 'nullable|string|max:255',
            ]);
    
            if ($validator->fails()) {
                return redirect()->route('vendor.profile.show')
                    ->withErrors($validator)
                    ->withInput();
            }
    
         
    
            // **Update Profile Picture**
            if ($request->hasFile('photo')) {
                $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                if ($user->photo && Storage::exists($user->photo)) {
                    Storage::delete($user->photo);
                }
            } else {
                $imagePath = null; 
            }
    
            $user->photo = $imagePath;

            if ($user->vendor) {
                $vendor = $user->vendor; // Assuming the relation is `user()->hasOne(Vendor::class)`
    
                if ($request->has('business_name')) {
                    $vendor->business_name = $request->business_name;
                }
                if ($request->has('tax_id')) {
                    $vendor->tax_id = $request->tax_id;
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
