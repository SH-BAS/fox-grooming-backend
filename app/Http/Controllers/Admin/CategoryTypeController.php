<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryType;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryTypeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        $categoryTypes = CategoryType::with('category')
            ->when($request->category_id, function ($query) use ($request) {
                return $query->where('category_id', $request->category_id);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.category-types.index', compact('categoryTypes', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.category-types.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:150',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            //image original Name
            $imageName = $request->file('image')->getClientOriginalName();
            $data['image'] = $request->file('image')->storeAs('category-types', $imageName, 'public');
        }
        $data['is_active'] = $request->has('is_active');

        CategoryType::create($data);

        return redirect()->route('admin.category-types.index')->with('success', 'Category type created successfully.');
    }

    public function edit(CategoryType $category_type)
    {
        $categories = Category::all();
        return view('admin.category-types.edit', compact('category_type', 'categories'));
    }

    public function update(Request $request, CategoryType $category_type)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:150',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->getClientOriginalName();
            $data['image'] = $request->file('image')->storeAs('category-types', $imageName, 'public');
        }
        $data['is_active'] = $request->has('is_active');

        $category_type->update($data);

        return redirect()->route('admin.category-types.index')->with('success', 'Category type updated successfully.');
    }

    public function destroy(CategoryType $category_type)
    {
        $category_type->delete();
        return redirect()->route('admin.category-types.index')->with('success', 'Category type deleted successfully.');
    }
}
