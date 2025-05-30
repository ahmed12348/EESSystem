<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\City;
use App\Models\Region;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:vendors-view')->only(['index', 'show']);
        $this->middleware('can:vendors-create')->only(['create', 'store']);
        $this->middleware('can:vendors-edit')->only(['edit', 'update']);
        $this->middleware('can:vendors-delete')->only(['destroy']);
    }
    
    public function index(Request $request)
    {
        $search = $request->query('search');
       
        $vendors = User::where('type', 'vendor')
            ->with('vendor')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%")
                      ->orWhereHas('vendor', function ($vendorQuery) use ($search) {
                          $vendorQuery->where('business_name', 'LIKE', "%{$search}%");
                      });
                });
            })
            ->paginate(10);
    
        return view('admin.vendors.index', compact('vendors'));
    }
    

        public function create()
        {
            $cities = City::all();
            $regions = Region::all();
            $roles = \Spatie\Permission\Models\Role::all(); 
            return view('admin.vendors.create', compact('roles','cities', 'regions'));
        }
        public function show($id)
        {

            $vendor = User::with(['vendor.city', 'vendor.region'])->findOrFail($id);
            return view('admin.vendors.show', compact('vendor'));
        }

        public function store(Request $request)
        {
            // dd($request->all());
            try {
                // Validate input data
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:users,email',
                    'phone' => 'required|digits_between:11,13|unique:users,phone',
                    'password' => 'required|string|min:6|confirmed',
                    'business_name' => 'required|string',
                    'business_type' => 'required|string',
                    'zone' => 'nullable|string',
                    'address' => 'required|string',
                    'city_id' => 'nullable|exists:cities,id',
                    'region_id' => 'nullable|exists:regions,id',
                    'latitude' => 'nullable|numeric',
                    'longitude' => 'nullable|numeric',
                    'notes' => 'nullable|string|max:500',
                    'role' => 'required|exists:roles,name', 
                ]);
    
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                // Create User
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'type' => 'vendor',
                    'status' => 'approved',
                ]);
        
                // Assign Role to the User
                $user->assignRole($request->role);
        
                // Create Vendor with Location Fields in Vendor Table
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
        
                return redirect()->route('admin.vendors.index')->with('success', 'Vendor created successfully.');
            } catch (\Exception $e) {
                // dd($e); 
                Log::error('Vendor creation error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while creating the vendor.');
            }
        }
        
        public function edit($id)
        {
            $vendor = User::with('vendor')->where('id', $id)->where('type', 'vendor')->firstOrFail();
            $roles = \Spatie\Permission\Models\Role::all();
            $cities = City::all();
            $regions = Region::all();
            return view('admin.vendors.edit', compact('vendor', 'roles', 'cities', 'regions'));
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
                    'role' => 'required|exists:roles,name',
                    'city_id' => 'nullable|exists:cities,id',
                    'region_id' => 'nullable|exists:regions,id',
                ]);
        
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
        
                // Find the user and vendor
                $user = User::findOrFail($id);
                $vendor = Vendor::where('user_id', $id)->firstOrFail();
        
                // Update User details
                $user->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                ]);
        
                // Assign New Role (if changed)
                if ($request->role !== $user->roles->first()?->name) {
                    $user->syncRoles([$request->role]);
                }
        
                // Update Vendor details
                $vendor->update([
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
        
                return redirect()->route('admin.vendors.index')->with('success', 'Vendor updated successfully.');
            } catch (\Exception $e) {
                Log::error('Vendor update error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while updating the vendor.');
            }
        }
        
        
        
        
    
        public function destroy($id)
        {
            try {
                $user = User::where('id', $id)->where('type', 'vendor')->firstOrFail();
    
                // Delete vendor & location related to this vendor
                 $vendor = Vendor::where('user_id', $user->id)->first();
                if ($vendor) {
                    $vendor->delete();
                }
    
                // Delete the user
                $user->delete();
    
                return redirect()->route('admin.vendors.index')->with('success', 'vendor deleted successfully.');
            } catch (\Exception $e) {
                Log::error('vendor delete error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while deleting the vendor.');
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
            return back()->with('success', 'Approved successfully!');
        }
    
        public function reject($id)
        {
            $user = User::findOrFail($id);
            $user->update(['status' => 'rejected']);
            return back()->with('error', 'Rejected successfully!');
        }
    
    
        public function vendorReviews($vendorId)
        {
           $vendor = User::where('id', $vendorId)->with('vendor')->first();
         
            $reviews = Review::where('vendor_id', $vendor->vendor->id)->with('user')->paginate(10);
            
            return view('admin.vendors.vendor_reviews', compact('vendor', 'reviews'));
        }
    
    }
    