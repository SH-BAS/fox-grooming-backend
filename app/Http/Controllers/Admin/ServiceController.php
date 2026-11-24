<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Category;
use App\Models\Size;
use App\Models\ServiceSize;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $services = Service::with('category')
            ->withCount('bookedServices')
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(8)
            ->appends($request->only('category_id'));

        return view('admin.services.index', compact('services', 'categories'));
    }

    public function toggleStatus(Service $service)
    {
        $service->is_active = !$service->is_active;
        $service->save();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => (bool) $service->is_active,
                'message' => 'Service status updated successfully.',
            ]);
        }

        return back()->with('success', 'Service status updated successfully.');
    }

    public function show(Service $service)
    {
        $service->load(['category', 'sizes.size', 'bookedServices.booking.user', 'bookedServices.booking.pet', 'bookedServices.booking.staff']);
        return view('admin.services.show', compact('service'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $sizes = Size::all();
        return view('admin.services.create', compact('categories', 'sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:150',
            'pricing_type' => 'required|in:fixable,sizeable',
            'minutes' => 'required|numeric',
            'price' => 'nullable|required_if:pricing_type,fixable|numeric',
            'size_prices' => 'nullable|required_if:pricing_type,sizeable|array',
            'image' => 'nullable|image',
        ]);

        $data = $request->only(['category_id', 'name', 'description', 'pricing_type', 'minutes']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }
        $data['is_active'] = $request->has('is_active');

        $service = Service::create($data);

        if ($request->pricing_type === 'fixable') {
            ServiceSize::create([
                'service_id' => $service->id,
                'size_id' => null,
                'price' => $request->price,
            ]);
        } elseif ($request->pricing_type === 'sizeable' && $request->has('size_prices')) {
            foreach ($request->size_prices as $size_id => $price) {
                if ($price !== null && $price !== '') {
                    ServiceSize::create([
                        'service_id' => $service->id,
                        'size_id' => $size_id,
                        'price' => $price,
                    ]);
                }
            }
        }

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $categories = Category::where('is_active', true)->get();
        $sizes = Size::all();
        $service->load('sizes');
        return view('admin.services.edit', compact('service', 'categories', 'sizes'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:150',
            'pricing_type' => 'required|in:fixable,sizeable',
            'price' => 'nullable|required_if:pricing_type,fixable|numeric',
            'minutes' => 'required|numeric',
            'size_prices' => 'nullable|required_if:pricing_type,sizeable|array',
            'image' => 'nullable|image',
        ]);

        $data = $request->only(['category_id', 'name', 'description', 'pricing_type', 'minutes']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }
        $data['is_active'] = $request->has('is_active');

        $service->update($data);

        // Clear existing prices to ensure consistency
        $service->sizes()->delete();

        if ($request->pricing_type === 'fixable') {
            ServiceSize::create([
                'service_id' => $service->id,
                'size_id' => null,
                'price' => $request->price,
            ]);
        } elseif ($request->pricing_type === 'sizeable' && $request->has('size_prices')) {
            foreach ($request->size_prices as $size_id => $price) {
                if ($price !== null && $price !== '') {
                    ServiceSize::create([
                        'service_id' => $service->id,
                        'size_id' => $size_id,
                        'price' => $price,
                    ]);
                }
            }
        }

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
    public function getDetails(Service $service)
    {
        $service->load('sizes.size');
        return response()->json($service);
    }
}
