<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a list of categories, including subcategories.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Fetching parent categories with their subcategories
        $categories = Category::whereNull('parent_id')
            ->with(['subcategories', 'products'])
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhereHas('subcategories', function ($subQuery) use ($search) {
                                 $subQuery->where('name', 'like', "%{$search}%");
                             });
            })
            ->paginate(10); // Paginate results

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show form to create a category.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get(); // Fetch only main categories
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a new category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing a category.
     */
    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id) // Prevent selecting itself as a parent
            ->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update a category.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id|not_in:' . $category->id, // Prevent self-parenting
        ]);

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Delete a category and all its subcategories and products.
     */
    public function destroy(Category $category)
    {
        // Fetch all subcategory IDs
        $subCategoryIds = $category->subcategories()->pluck('id')->toArray();

        // Delete all products in this category & its subcategories
        Product::whereIn('category_id', array_merge([$category->id], $subCategoryIds))->delete();

        // Delete all subcategories
        Category::whereIn('id', $subCategoryIds)->delete();

        // Delete the main category
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category and related subcategories & products deleted successfully.');
    }
}

