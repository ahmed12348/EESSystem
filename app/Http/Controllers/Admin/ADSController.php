<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdsRequest;

use App\Models\Ads;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ADSController extends Controller
{
    public function index()
    {
        $ads = Ads::paginate(10);
        return view('admin.ads.index', compact('ads'));
    }

    public function create()
    {
        $products = Product::all();
        $categories = Category::all();
        $zones = ['zone_1', 'zone_2','zone_3']; // Static Zones

        return view('admin.ads.create', compact('products', 'categories', 'zones'));
    }

    public function store(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|in:product,category,zone',
            'reference_id' => 'nullable|exists:categories,id', // Or use 'products' table if it's a product
            'zone' => 'nullable|string',
            'image' => 'nullable|image|max:1024',
        ]);
    
        // Create the ad
        $ad = new Ads();
        $ad->title = $validatedData['title'];
        $ad->description = $validatedData['description'];
        // $ad->type = $validatedData['type'];
    
        // if ($ad->type == 'product' || $ad->type == 'category') {
        //     // Set the reference_id (Category or Product based on type)
        //     $ad->reference_id = $validatedData['reference_id'];
        // } elseif ($ad->type == 'zone') {
        //     // Handle zone field if needed
        //     $ad->zone = $validatedData['zone'];
        // }
    
        // Handle file upload if needed
        if ($request->hasFile('image')) {
            $ad->image = $request->file('image')->store('ads', 'public');
        }
    
        $ad->save();
    
        return redirect()->route('admin.ads.index')->with('success', 'Ad created successfully.');
    }
    

    public function getReferences(Request $request)
    {
       
        $type = $request->type;
        
        if ($type === 'product') {
            $references = Product::select('id', 'name')->get();
        } elseif ($type === 'category') {
            $references = Category::select('id', 'name')->get();
        } else {
            $references = [];
        }
    
        return response()->json($references);
    }

    public function edit(Ads $ad)
    {
        $products = Product::all();
        $categories = Category::all();
        $zones = ['Zone 1', 'Zone 2','Zone 3'];

        return view('admin.ads.edit', compact('ad', 'products', 'categories', 'zones'));
    }

   // In AdController.php

    public function show(Ads $ad)
    {
        // Pass the ad data to the show view
        return view('admin.ads.show', compact('ad'));
    }

    public function update(Request $request, Ads $ad)
        {
            // Validate input
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'nullable|in:product,category,zone',
                'reference_id' => 'nullable|exists:categories,id', // Use 'products' table if it's a product
                'zone' => 'nullable|string',
                'image' => 'nullable|image|max:1024',
            ]);
        
            // Update the ad details
            $ad->title = $validatedData['title'];
            $ad->description = $validatedData['description'];
            $ad->type = $validatedData['type'];
        
            if ($ad->type == 'product' || $ad->type == 'category') {
                $ad->reference_id = $validatedData['reference_id'];
            } elseif ($ad->type == 'zone') {
                $ad->zone = $validatedData['zone'];
            }
        
            // Handle file upload if needed
            if ($request->hasFile('image')) {
                $ad->image = $request->file('image')->store('ads', 'public');
            }
        
            // Save the updated ad
            $ad->save();
        
            return redirect()->route('admin.ads.index')->with('success', 'Ad updated successfully.');
        }
        
    

    public function destroy(Ads $ad)
    {
        $ad->delete();
        return redirect()->route('admin.ads.index')->with('success', 'Ad deleted successfully.');
    }
}
