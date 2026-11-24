<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'active');

        $activeCount = Banner::where('is_active', true)->count();
        $inactiveCount = Banner::where('is_active', false)->count();

        $query = Banner::with('category');

        if ($status === 'inactive') {
            $query->where('is_active', false);
        } else {
            $status = 'active';
            $query->where('is_active', true);
        }

        $banners = $query->latest('id')->paginate(8)->appends(['status' => $status]);

        return view('admin.banners.index', compact(
            'banners',
            'status',
            'activeCount',
            'inactiveCount'
        ));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.banners.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|max:200',
            'image' => 'nullable|image|max:4096',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'title' => $request->title,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        $categories = Category::all();
        return view('admin.banners.edit', compact('banner', 'categories'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|max:200',
            'image' => 'nullable|image|max:4096',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'title' => $request->title,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $rawImage = $banner->getAttributes()['image'] ?? null;
            if ($rawImage) {
                Storage::disk('public')->delete($rawImage);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()
            ->route('admin.banners.index', ['status' => 'active'])
            ->with('success', 'Banner deleted successfully.');
    }

    public function toggleStatus(Request $request, Banner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();

        $label = $banner->is_active ? 'active' : 'inactive';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => (bool) $banner->is_active,
                'message' => "Banner marked as {$label}.",
            ]);
        }

        return redirect()
            ->route('admin.banners.index', ['status' => $banner->is_active ? 'active' : 'inactive'])
            ->with('success', "Banner marked as {$label}.");
    }
}
