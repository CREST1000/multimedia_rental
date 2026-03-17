<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::with('equipment')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
            'description' => 'nullable|string'
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('categories.index')
                         ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        // Load equipment for this category
        $category->load('equipment');
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the category.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id . '|max:255',
            'description' => 'nullable|string'
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category)
    {
        // Check if category has equipment
        if ($category->equipment()->count() > 0) {
            return redirect()->route('categories.index')
                             ->with('error', 'Cannot delete category with associated equipment.');
        }

        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Category deleted successfully.');
    }
}