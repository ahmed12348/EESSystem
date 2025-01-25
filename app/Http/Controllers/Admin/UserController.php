<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
        // Filter users based on the role 'customers'
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'vendor');
        })
        ->with('orders')
        ->when($request->search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%");
        })
        // Paginate results
        ->paginate(6);
    
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
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',  // 2MB Max Image Size

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


    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

 
    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required',
            'password' => 'nullable|min:6', // Password is optional
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // 2MB max
        ]);
    
        // Update user details
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];
    
        // Update password only if a new one is entered
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }
    
        $user->update($updateData);
    
        // Handle profile picture update only if a new one is uploaded
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
    
        $user->syncRoles([$request->role]);
    
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

        // Validation for name, password, and profile picture
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

        if ($request->hasFile('profile_picture')) {
            // Handle file upload and save the path
            $filename = time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $path = $request->file('profile_picture')->storeAs('profile_pictures', $filename);
            // Delete old picture if exists
            if ($user->profile_picture && Storage::exists($user->profile_picture)) {
                Storage::delete($user->profile_picture);
            }
            $user->profile_picture = $path;
        }

        $user->save();

        return redirect()->route('admin.profile.show')->with('success', 'Profile updated successfully.');
    }

}
