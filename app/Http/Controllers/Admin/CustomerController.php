<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\customer;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\City;
use App\Models\Region;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class customerController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:customers-view')->only(['index', 'show']);
        $this->middleware('can:customers-create')->only(['create', 'store']);
        $this->middleware('can:customers-edit')->only(['edit', 'update']);
        $this->middleware('can:customers-delete')->only(['destroy']);
    }
    
    public function index(Request $request)
    {
        $search = $request->query('search');
       
        $customers = User::where('type', 'customer')
            ->with('vendor')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%")
                      ->orWhereHas('vendor', function ($customerQuery) use ($search) {
                          $customerQuery->where('business_name', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->paginate(10);
    
        return view('admin.customers.index', compact('customers'));
    }
    

        public function create()
        {
            $cities = City::all();
            $regions = Region::all();
            $roles = \Spatie\Permission\Models\Role::all(); 
            return view('admin.customers.create', compact('roles','cities', 'regions'));
        }
        public function show($id)
        {

            $customer = User::with(['vendor.city', 'vendor.region'])->findOrFail($id);
            return view('admin.customers.show', compact('customer'));
        }

        public function store(Request $request)
        {
            // dd($request->all());
            try {
                // Validate input data
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string',
                    'phone' => 'required|digits_between:11,13|unique:users,phone',
                    'business_name' => 'required|string',
                    'business_type' => 'required|string',
                    'zone' => 'nullable|string',
                    'address' => 'required|string',
                    'city_id' => 'nullable|exists:cities,id',
                    'region_id' => 'nullable|exists:regions,id',
                    'latitude' => 'nullable|numeric',
                    'longitude' => 'nullable|numeric',
                    'notes' => 'nullable|string|max:500', 
                ]);
    
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                // Create User
                $user = User::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'type' => 'customer',
                    'status' => 'approved',
                ]);
        
                // Assign Role to the User
                $user->assignRole($request->role);
        
                // Create customer with Location Fields in customer Table
                Vendor::create([
                    'business_name' => $request->business_name,
                    'business_type' => $request->business_type,
                    'zone' => $request->zone,
                    'user_id' => $user->id,
                    'address' => $request->address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'city_id' => $request->city_id,
                    'region_id' => $request->region_id,
                    'is_verified' => true,
                    'notes' => $request->notes,
                ]);
        
                return redirect()->route('admin.customers.index')->with('success', 'customer created successfully.');
            } catch (\Exception $e) {
                // dd($e); 
                Log::error('customer creation error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while creating the customer.');
            }
        }
        
        public function edit($id)
        {
            $customer = User::with('vendor')->where('id', $id)->where('type', 'customer')->firstOrFail();
            $roles = \Spatie\Permission\Models\Role::all();
            $cities = City::all();
            $regions = Region::all();
            return view('admin.customers.edit', compact('customer', 'roles', 'cities', 'regions'));
        }
    
        public function update(Request $request, $id)
        {
          
            try {
                // Validate input data
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'phone' => 'required|digits_between:11,15|unique:users,phone,' . $id, 
                    'business_name' => 'required|string|max:255',
                    'business_type' => 'nullable|string|max:255',
                    'zone' => 'nullable|string',
                    'address' => 'required|string|max:500',
                    'latitude' => 'nullable|numeric',
                    'longitude' => 'nullable|numeric',
                    'notes' => 'nullable|string|max:500',
                    'city_id' => 'nullable|exists:cities,id',
                    'region_id' => 'nullable|exists:regions,id',
                ]);
        
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
        
                // Find the user and customer
                $user = User::findOrFail($id);
                $customer = Vendor::where('user_id', $id)->firstOrFail();
        
                // Update User details
                $user->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                ]);
        
                // Assign New Role (if changed)
                if ($request->role !== $user->roles->first()?->name) {
                    $user->syncRoles([$request->role]);
                }
        
                // Update customer details
                $customer->update([
                    'business_name' => $request->business_name,
                    'business_type' => $request->business_type,
                    'zone' => $request->zone,
                    'address' => $request->address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'city_id' => $request->city_id,
                    'region_id' => $request->region_id,
                    'notes' => $request->notes,
                ]);
        
                return redirect()->route('admin.customers.index')->with('success', 'customer updated successfully.');
            } catch (\Exception $e) {
                Log::error('customer update error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while updating the customer.');
            }
        }
        
        
        
        
    
        public function destroy($id)
        {
            try {
                $user = User::where('id', $id)->where('type', 'customer')->firstOrFail();
    
                // Delete customer & location related to this customer
                 $customer = Vendor::where('user_id', $user->id)->first();
                if ($customer) {
                    $customer->delete();
                }
    
                // Delete the user
                $user->delete();
    
                return redirect()->route('admin.customers.index')->with('success', 'customer deleted successfully.');
            } catch (\Exception $e) {
                Log::error('customer delete error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while deleting the customer.');
            }
        }
    
        public function getRegionsByCity(Request $request)
        {
            $cityId = $request->city_id;
            $regions = Region::where('city_id', $cityId)->get();

            return response()->json($regions);
        }
    
        public function approve($id)
        {
            $user = User::findOrFail($id);
            $user->update(['status' => 'approved']);
            return back()->with('success', 'customer approved successfully!');
        }
    
        public function reject($id)
        {
            $user = User::findOrFail($id);
            $user->update(['status' => 'rejected']);
            return back()->with('error', 'customer rejected successfully!');
        }
    
    
    
    
    }
    