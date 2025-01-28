<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::with('parent')->paginate(5); // Fetch categories with parent info
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get(); // Fetch only main categories
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created category in the database.
     */
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

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category in storage.
     */
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

    /**
     * Remove the specified category from storage along with its subcategories and products.
     */
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
