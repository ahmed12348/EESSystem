<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   
    public function index(Request $request)
    {
        $search = $request->query('search');
    
        $categories = Category::with('parent')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(5);
    
        return view('admin.categories.index', compact('categories'));
    }
    

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get(); // Fetch only main categories
        return view('admin.categories.create', compact('categories'));
    }

  
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

   
    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

   
    public function destroy(Category $category)
    {
        // Delete all products under this category and its subcategories
        $subCategoryIds = $category->subcategories()->pluck('id');
        Product::whereIn('category_id', $subCategoryIds)->delete();
        Product::where('category_id', $category->id)->delete();

        // Delete all subcategories
        Category::where('parent_id', $category->id)->delete();

        // Delete the category itself
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category and all related subcategories & products deleted successfully.');
    }
}
