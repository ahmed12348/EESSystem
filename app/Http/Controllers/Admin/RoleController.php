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
        $permissions = ModelsPermission::all();
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
         $permissions = ModelsPermission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
       
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id', // Validate each permission
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->input('name'),
            'guard_name' => 'web', // Specify the guard name if applicable
        ]);

        // Sync permissions with the role
        $role->syncPermissions($request->input('permissions'));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete(); // Delete the role

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully');
    }
}

