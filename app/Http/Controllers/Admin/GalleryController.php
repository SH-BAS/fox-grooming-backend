<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('category')->latest('id')->paginate(8);
        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.galleries.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'before_image' => 'nullable|image|max:4096',
            'after_image' => 'nullable|image|max:4096',
        ]);

        $data = ['category_id' => $request->category_id];

        if ($request->hasFile('before_image')) {
            $data['before_image'] = $request->file('before_image')->store('galleries', 'public');
        }
        if ($request->hasFile('after_image')) {
            $data['after_image'] = $request->file('after_image')->store('galleries', 'public');
        }

        Gallery::create($data);

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item created successfully.');
    }

    public function edit(Gallery $gallery)
    {
        $categories = Category::all();
        return view('admin.galleries.edit', compact('gallery', 'categories'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'before_image' => 'nullable|image|max:4096',
            'after_image' => 'nullable|image|max:4096',
        ]);

        $data = ['category_id' => $request->category_id];

        if ($request->hasFile('before_image')) {
            $raw = $gallery->getAttributes()['before_image'] ?? null;
            if ($raw) {
                Storage::disk('public')->delete($raw);
            }
            $data['before_image'] = $request->file('before_image')->store('galleries', 'public');
        }
        if ($request->hasFile('after_image')) {
            $raw = $gallery->getAttributes()['after_image'] ?? null;
            if ($raw) {
                Storage::disk('public')->delete($raw);
            }
            $data['after_image'] = $request->file('after_image')->store('galleries', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item deleted successfully.');
    }
}
