<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Location;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })
        ->with(['vendor.location'])
        ->withCount('orders') 
        ->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) { 
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        })
        ->paginate(10); // Paginate results
    
        // Return view with users
        return view('admin.users.index', compact('users'));
    }



    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        } else {
            $imagePath = null; 
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'profile_picture' => $imagePath,  
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

   public function show($id, Request $request)
    {
        
    $user = User::with('roles')->findOrFail($id);

    // Fetch categories for filtering
    $categories = Category::all();

    // Fetch orders with filters
    $orders = Order::where('user_id', $user->id)
                ->when($request->search_name, function ($query, $search_name) {
                    return $query->where('product_name', 'LIKE', "%{$search_name}%");
                })
                ->when($request->category, function ($query, $category) {
                    return $query->where('category_id', $category);
                })
                ->paginate(6);

        return view('admin.users.show', compact('user', 'orders', 'categories'));
    }


    public function edit($id)
    {
         $user = User::with('vendor.location')->find($id); // Eager load vendor and location
     
        return view('admin.users.edit', compact('user'));
    }

 
    
    public function update(Request $request, $id)
    {
        // Validate the inputs
        $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'zone' => 'nullable|string|max:255', // Only validate the zone
            'tax_id' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
    
        
        // Profile Picture (optional)
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = $file->store('profile_pictures', 'public');
            $user->photo = $path;
        }
    
        $user->save();
    
        // Update vendor's zone (this assumes you're using the vendor's zone for a specific logic)
        if ($user->vendor) {
            $vendor = $user->vendor;
            $vendor->zone = $request->input('zone');
            $vendor->save();
        }
    
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
    

    
    public function destroy(User $user)
    {
        try {
           
            if (!empty($user->profile_picture) && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
    
            
            // $user->orders()->delete(); // If user has orders, delete them
            // $user->posts()->delete();  // If user has posts, delete them
            // $user->roles()->detach();  // Remove user roles (if using roles system)
    
           
            $user->delete();
    
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.users.index')->with('error', 'User not found.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);

        return back()->with('success', 'User approved successfully!');
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'inactive']);

        return back()->with('error', 'User rejected.');
    }


    public function showProfile()
    {
        $user = Auth::user();
        return view('admin.users.profile', compact('user'));
    }

    // Update Profile Information
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
       
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_picture' => 'nullable|image|max:2048',  // Image file with size check
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.profile.show')->withErrors($validator)->withInput();
        }

        // Update user information
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('password')) {
     
            $user->password = Hash::make($request->password);
        }
        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            // Delete old picture if exists
            if (!empty($user->profile_picture) && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
    
            // Upload new picture
            $image = $request->file('profile_picture');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('profile_pictures', $imageName, 'public');
    
            // Save new profile picture path
            $user->update(['profile_picture' => 'profile_pictures/' . $imageName]);
        }

        $user->save();
        return redirect()->route('admin.profile.show')->with('success', 'Profile updated successfully.');
    }



}
