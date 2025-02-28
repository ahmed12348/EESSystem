<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Location;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check_type:admin');
        $this->middleware('can:users-view')->only(['index', 'show']);
        $this->middleware('can:users-create')->only(['create', 'store']);
        $this->middleware('can:users-edit')->only(['edit', 'update']);
        $this->middleware('can:users-delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $search = $request->query('search');

        $users = User::where('type', 'admin')
            ->with(['vendor.location'])
            ->withCount('orders')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%")
                      ->orWhereHas('vendor', function ($vendorQuery) use ($search) {
                          $vendorQuery->where('business_name', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // Fetch available roles and permissions for assignment
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.users.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:255',
                'phone'    => 'required|unique:users,phone',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'role'     => 'required',
                'photo'    => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Handle photo upload if provided
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('profile_pictures', 'public');
            } else {
                $photoPath = null;
            }

            // Create the admin user
            $user = User::create([
                'name'     => $request->name,
                'phone'    => $request->phone,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
                'status'   => 'approved',
                'type'     => 'admin',
                'photo'    => $photoPath,
            ]);

            // Assign role and permissions if provided
            $user->assignRole($request->role);
            if ($request->permissions) {
                $user->givePermissionTo($request->permissions);
            }

            return redirect()->route('admin.users.index')
                             ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the user: ' . $e->getMessage())
                         ->withInput();
        }
    }

    public function show($id, Request $request)
    {
        $user = User::with('roles')->findOrFail($id);
        $categories = Category::all();

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
        $user = User::with('vendor.location')->findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.users.edit', compact('user', 'roles', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'nullable|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'phone'    => 'nullable|string|max:255|unique:users,phone,' . $id,
            'password' => 'nullable|string|min:6',
            'photo'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role'     => 'nullable|string',
        ]);
    
        $user = User::findOrFail($id);
        $user->name  = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->phone = $request->input('phone', $user->phone);
    
        // Update password if a new password is provided.
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        // Update photo if a new file is uploaded.
        if ($request->hasFile('photo')) {
            if (!empty($user->photo) && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('profile_pictures', 'public');
            $user->photo = $path;
        }
    
        $user->save();
    
        // Update role if provided.
        if ($request->role) {
            $user->syncRoles($request->role);
        }
    
        return redirect()->route('admin.users.index')
                         ->with('success', 'User updated successfully.');
    }
    
    
    

    public function destroy(User $user)
    {
        try {
            if (!empty($user->photo) && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            // Clear assigned roles and permissions before deletion
            $user->syncRoles([]);
            $user->syncPermissions([]);
            $user->delete();

            return redirect()->route('admin.users.index')
                             ->with('success', 'User deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'User not found.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'approved']);
        return back()->with('success', 'User approved successfully!');
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'rejected']);
        return back()->with('error', 'User rejected.');
    }

    public function showProfile()
    {
        $user = Auth::user();
        return view('admin.users.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name'     => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'photo'    => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.profile.show')
                             ->withErrors($validator)
                             ->withInput();
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            if (!empty($user->photo) && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $image      = $request->file('photo');
            $imageName  = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('profile_pictures', $imageName, 'public');
            $user->photo = 'profile_pictures/' . $imageName;
        }

        $user->save();
        return redirect()->route('admin.profile.show')
                         ->with('success', 'Profile updated successfully.');
    }
}
