<?php

namespace App\Http\Controllers\Vendor;

use App\Exports\ProductsExport;
use App\Exports\ProductsExportSample;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Imports\ProductsImport;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function __construct()
    {
        // Ensure that vendors are authenticated
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $vendorId = Auth::id(); // Get vendor ID
    
        // Base query for all products
        $productsQuery = Product::where('vendor_id', $vendorId);
        $requestedProductsQuery = Product::where('vendor_id', $vendorId)->where('status', false);
    
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
    
            // Apply search filter to both queries
            $productsQuery->where('name', 'like', '%' . $searchTerm . '%');
            $requestedProductsQuery->where('name', 'like', '%' . $searchTerm . '%');
        }
    
        $products = $productsQuery->paginate(10);
        $requestedProducts = $requestedProductsQuery->paginate(10);
    
        return view('vendor.products.index', compact('products', 'requestedProducts'));
    }
    

    public function create()
    {
        $categories = Category::all();
        return view('vendor.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)  
    {
        // Get the validated data
        $data = $request->validated(); 

        $data['vendor_id'] = Auth::id();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Create the product for the vendor
        Product::create($data);

        return redirect()->route('vendor.products.index')->with('success', 'Product created successfully.');
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);  
        $categories = Category::all();  
    
        return view('vendor.products.show', compact('product', 'categories'));
    }

    public function edit(Product $product)
    {
        // Check if the product belongs to the authenticated vendor
        if (Auth::id() !== $product->vendor_id) {
            abort(403);  // Unauthorized access if not the owner
        }

        // Retrieve categories for the vendor to choose from
        $categories = Category::all();
        return view('vendor.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)  
    {
        // Check if the product belongs to the authenticated vendor
        if (Auth::id() !== $product->vendor_id) {
            abort(403);  // Unauthorized access if not the owner
        }

        // Get the validated data
        $data = $request->validated();

        // Handle the product image if it's uploaded
        if ($request->hasFile('image')) {
            // If a new image is uploaded, store it and update the image path
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Update the product
        $product->update($data);

        // Redirect back to the vendor's product index
        return redirect()->route('vendor.products.index')->with('success', 'Product updated successfully.');
    }


    // Vendor-specific import function
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv'
        ]);

        try {
            // Import the products for the vendor
            Excel::import(new ProductsImport, $request->file('file'));

            // On success, redirect back to the vendor product index
            return redirect()->route('vendor.products.index')->with('success', 'Products imported successfully.');

        } catch (ValidationException $e) {
            // Catch the validation exception and pass the errors to the session
            return redirect()->route('vendor.products.index')->with('import_error', $e->errors());
        }
    }

   

    // Export the sample template for vendor
    public function exportSample()
    {
        // Export the sample template with headers only
        return Excel::download(new ProductsExportSample, 'products_sample.xlsx');
    }
}
