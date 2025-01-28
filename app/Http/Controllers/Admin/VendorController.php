<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
    
        // Fetch users who have the "vendor" role and apply the search filter
        $vendors = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })
        ->when($search, function ($query) use ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        })
       
        ->paginate(10);
    
        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request)
    {
        // Validate and store the vendor
    }

    public function show($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('admin.vendors.show', compact('vendor'));
    }

    public function edit($id)
    {
        $vendor = user::findOrFail($id);
        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, User $vendor)
    {
        
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_number' => 'required|max:255',
            // 'phone' => 'required|regex:/^[0-9]{10,15}$/',
            'zone' => 'nullable|string|max:255',
        ]);

        // Update vendor details
        if ($vendor->vendor) {
            $vendor->vendor->update(['business_name' => $request->business_name]);
            $vendor->vendor->update(['business_number' => $request->business_number]);
        }

        $vendor->update([
            // 'phone' => $request->phone,
            'zone' => $request->zone,
        ]);

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor updated successfully.');
    }


    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $user = $vendor->user;  
        if ($user) {
            $user->delete();  
        }

        $vendor->delete();

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor and associated user deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $vendor = User::findOrFail($id);
        $vendor->status = 'active'; // Update status to active
        $vendor->save();

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor approved successfully.');
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
       
        if ($user->hasRole('vendor')) {
          
            $user->update(['status' => 'active']);
        }
        return back()->with('success', 'User approved successfully.');
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        if ($user->hasRole('vendor')) {
            $user->update(['status' => 'inactive']);
        }
        return back()->with('error', 'User rejected.');
    }
 
}
