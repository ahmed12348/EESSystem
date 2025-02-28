<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission as ModelsPermission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:roles-view')->only(['index', 'show']);
        $this->middleware('can:roles-create')->only(['create', 'store']);
        $this->middleware('can:roles-edit')->only(['edit', 'update']);
        $this->middleware('can:roles-delete')->only(['destroy']);
    }
    
    public function index(Request $request)
    {
        $search = $request->input('search'); // Use input() instead of query() for robustness

        $roles = Role::orderBy('id', 'DESC')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            })
            ->paginate(10);

        return view('admin.roles.index', compact('roles'));
    }

    
    public function create()
    {
        $permissions = ModelsPermission::orderBy('id')->get()->groupBy(function ($permission) {
            return explode('-', $permission->name)[0]; // Extracts the module name before '-'
        });
    
        return view('admin.roles.create', compact('permissions'));
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',  // Validate that each permission exists
        ]);

        // Create the role
        $role = Role::create([
            'name' => $request->input('name'),
            'guard_name' => 'web', // Specify the guard name if applicable
        ]);

        // Sync permissions with the role
        $role->syncPermissions($request->input('permissions'));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully');
    }


        public function edit($id)
        {
            $role = Role::findOrFail($id);
    
            // Get all permissions and group them by module
            $permissions = ModelsPermission::orderBy('id')->get()->groupBy(function ($permission) {
                return explode('-', $permission->name)[0]; // Group by module
            });
    
            // Get assigned permissions
            $rolePermissions = $role->permissions->pluck('id')->toArray();
    
            return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
        }
    
        public function update(Request $request, $id)
        {
            $role = Role::findOrFail($id);
            
            // Validate input
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
                'permissions' => 'nullable|array',
            ]);
    
            // Update role name
            $role->update(['name' => $request->name]);
    
            // Sync permissions
            $role->syncPermissions($request->permissions);
    
            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
        }
    
    

        public function show($id)
        {
            $role = Role::findOrFail($id);
        
            // Get all permissions and group them by module
            $permissions = ModelsPermission::orderBy('id')->get()->groupBy(function ($permission) {
                return explode('-', $permission->name)[0]; // Groups permissions by module
            });
        
            // Get assigned permissions
            $rolePermissions = $role->permissions->pluck('id')->toArray();
        
            return view('admin.roles.show', compact('role', 'permissions', 'rolePermissions'));
        }
        

        public function destroy($id)
        {
            $role = Role::findOrFail($id);
        
            // Check if any users have this role
            if ($role->users()->count() > 0) {
                return redirect()->route('admin.roles.index')
                    ->with('error', 'Role cannot be deleted because it is assigned to users.');
            }
        
            $role->delete(); // Delete the role
        
            return redirect()->route('admin.roles.index')
                ->with('success', 'Role deleted successfully.');
        }
        
}

