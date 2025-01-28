<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            $products = Product::paginate(10); // Admin sees all products
            return view('admin.products.index', compact('products'));
        } else {
            $products = Product::where('vendor_id', Auth::id())->paginate(10); // Vendor sees only their own products
            return view('vendor.products.index', compact('products')); // Vendor has a separate view
        };
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['vendor_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        if (Auth::id() !== $product->vendor_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if (Auth::id() !== $product->vendor_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if (Auth::id() !== $product->vendor_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    
    public function approve($id)
    {
        $product = Product::findOrFail($id);
        $product->approved = true;
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
        
        $product->approved = false;
        $saved = $product->save();
        if ($saved) {
            return redirect()->back()->with('success', 'Product rejected successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to rejected product!');
        }
    }
}

