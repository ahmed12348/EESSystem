<?php
namespace App\Http\Controllers\Admin;

use App\Exports\ProductsExport;
use App\Exports\ProductsExportSample;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Imports\ProductsImport;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:products-view')->only(['index', 'show']);
        $this->middleware('can:products-create')->only(['create', 'store']);
        $this->middleware('can:products-edit')->only(['edit', 'update']);
        $this->middleware('can:products-delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
    
        $products = Product::when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%");
            })->paginate(10);
    
        $requestedProducts = Product::where('status', false)
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10);
    
        return view('admin.products.index', compact('products', 'requestedProducts'));
    }
    

    public function create()
    {
        $categories = Category::all();
        $vendors = User::where('type', 'vendor')
        ->with(['vendor'])->get();
      
        return view('admin.products.create', compact('categories','vendors'));
    }

    public function store(ProductRequest $request)  // Use ProductRequest here
    {
    
        // dd($request->all());
        $data = $request->validated(); // Get the validated data

        $data['vendor_id'] = $request->vendor_id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
 
        $users = User::where('type', 'vendor')->with(['vendor'])->get();
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories','users'));
    }
    public function update(ProductRequest $request, Product $product)
    {
       
        try {
            // Validate the request
            $data = $request->validated();
    
            if ($request->has('vendor_id')) {
                $data['vendor_id'] = $request->vendor_id;
            }
    
            // Handle Image Upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }
    
            // Ensure Product Exists
            if (!$product) {
                return redirect()->route('admin.products.index')->with('error', 'Product not found.');
            }
    
            $updated = $product->update($data);
    
            if ($updated) {
                return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update product.');
            }
    
        } catch (\Exception $e) {
            \Log::error('Product update failed: ' . $e->getMessage());
    
            return redirect()->back()->with('error', 'An error occurred while updating the product.');
        }
    }
    


    public function show($id)
    {
        // $product = Product::with('category')->findOrFail($id);
        $product = Product::findOrFail($id);  
        $categories = Category::all();  
    
        return view('admin.products.show', compact('product', 'categories'));
      
    }

    public function destroy(Product $product)
    {
        // if (Auth::id() !== $product->vendor_id && !Auth::user()->hasRole('admin')) {
        //     abort(403);
        // }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }


    
    public function approve($id)
    {
        $product = Product::findOrFail($id);
        $product->status = 'approved';
        $saved = $product->save();
        if ($saved) {
            return redirect()->back()->with('success', 'Product approved successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to approve product!');
        }
    }
    
    public function reject($id)
    {
        $product = Product::findOrFail($id);
        
        $product->status = 'rejected';
        $saved = $product->save();
        if ($saved) {
            return redirect()->back()->with('error', 'Product rejected successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to rejected product!');
        }
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv'
        ]);

        try {
            // Import the file
            Excel::import(new ProductsImport, $request->file('file'));

            // On success, redirect back to the product index
            return redirect()->route('admin.products.index')->with('success', 'Products imported successfully.');

        } catch (ValidationException $e) {
            // Catch the validation exception and pass the errors to the session
            return redirect()->route('admin.products.index')->with('import_error', $e->errors());
        }
    }

    // Handle product export
    public function export()
    {
        // Export all products to an Excel file
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function exportSample()
    {
        // Export the sample template with headers only
        return Excel::download(new ProductsExportSample, 'products_sample.xlsx');
    }
}

