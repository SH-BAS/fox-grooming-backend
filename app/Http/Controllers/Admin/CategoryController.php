<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Size;
use App\Models\CategoryStandard;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $category->load(['standards.size', 'types', 'services', 'pets.user']);
        $sizes = Size::all();

        return view('admin.categories.show', compact('category', 'sizes'));
    }

    public function create()
    {
        $sizes = Size::all();
        return view('admin.categories.create', compact('sizes'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:150',
            'description' => 'nullable',
            'minutes' => 'nullable|numeric|min:0',
            'pricing_type' => 'required|in:fixable,sizeable',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'icon' => 'nullable|image|max:1024',
            'title' => 'required|string|max:255',
        ];

        if ($request->pricing_type === 'fixable') {
            $rules['price'] = 'required|numeric|min:0';
        } else {
            $rules['size_prices'] = 'required|array';
            $rules['size_prices.*'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        $data = $request->only(['name', 'description', 'pricing_type']);
        $data['minutes'] = $request->input('minutes', 0);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->getClientOriginalName();
            $data['image'] = $request->file('image')->storeAs('categories', $imageName, 'public');
        }

        if ($request->hasFile('icon')) {
            $imageName = $request->file('icon')->getClientOriginalName();
            $data['icon'] = $request->file('icon')->storeAs('categories/icons', $imageName, 'public');
        }

        $category = Category::create($data);
        $this->syncStandards($category, $request);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $sizes = Size::all();
        $category->load('standards');
        return view('admin.categories.edit', compact('category', 'sizes'));
    }

    public function update(Request $request, Category $category)
    {
        $rules = [
            'name' => 'required|max:150',
            'description' => 'nullable',
            'minutes' => 'nullable|numeric|min:0',
            'pricing_type' => 'required|in:fixable,sizeable',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'icon' => 'nullable|image|max:1024',
            'title' => 'required|string|max:255',
        ];

        if ($request->pricing_type === 'fixable') {
            $rules['price'] = 'required|numeric|min:0';
        } else {
            $rules['size_prices'] = 'required|array';
            $rules['size_prices.*'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        $data = $request->only(['name', 'description', 'pricing_type']);
        if ($request->filled('minutes')) {
            $data['minutes'] = $request->minutes;
        }
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->getClientOriginalName();
            $data['image'] = $request->file('image')->storeAs('categories', $imageName, 'public');
        }

        if ($request->hasFile('icon')) {
            $imageName = $request->file('icon')->getClientOriginalName();
            $data['icon'] = $request->file('icon')->storeAs('categories/icons', $imageName, 'public');
        }

        $category->update($data);
        $this->syncStandards($category, $request);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function updateStandards(Request $request, Category $category)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'pricing_type' => 'required|in:fixable,sizeable',
        ];

        if ($request->pricing_type === 'fixable') {
            $rules['price'] = 'required|numeric|min:0';
        } else {
            $rules['size_prices'] = 'required|array';
            $rules['size_prices.*'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        $category->update([
            'pricing_type' => $request->pricing_type,
        ]);

        $this->syncStandards($category, $request);

        return redirect()
            ->route('admin.categories.show', $category->id)
            ->with('success', 'Standards & base pricing updated successfully.');
    }

    /**
     * Replace category standards. Price must be present and >= 0 (never null).
     */
    private function syncStandards(Category $category, Request $request): void
    {
        $category->standards()->delete();

        if ($request->pricing_type === 'fixable') {
            CategoryStandard::create([
                'category_id' => $category->id,
                'title' => $request->title,
                'size_id' => null,
                'price' => (float) $request->price,
            ]);

            return;
        }

        foreach ($request->size_prices as $size_id => $price) {
            CategoryStandard::create([
                'category_id' => $category->id,
                'title' => $request->title,
                'size_id' => $size_id,
                'price' => (float) $price,
            ]);
        }
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
    public function getServices(Category $category)
    {
        return response()->json($category->services()->where('is_active', true)->get());
    }

    public function getDetails(Category $category)
    {
        $category->load('standards.size');
        return response()->json($category);
    }
}
