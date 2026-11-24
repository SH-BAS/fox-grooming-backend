<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FranchiseController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'active');

        $activeCount = Franchise::where('is_active', true)->count();
        $inactiveCount = Franchise::where('is_active', false)->count();
        $deletedCount = Franchise::onlyTrashed()->count();

        $query = Franchise::with('zone')->withCount('bookings');

        if ($status === 'deleted') {
            $query->onlyTrashed();
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        } else {
            $status = 'active';
            $query->where('is_active', true);
        }

        $franchises = $query->latest()->paginate(8)->appends(['status' => $status]);

        return view('admin.franchises.index', compact(
            'franchises',
            'status',
            'activeCount',
            'inactiveCount',
            'deletedCount'
        ));
    }

    public function create()
    {
        $zones = Zone::all();
        return view('admin.franchises.create', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $data['image'] = $request->file('image')->storeAs('franchises', $imageName, 'public');
        }

        Franchise::create($data);

        return redirect()->route('admin.franchises.index')->with('success', 'Franchise created successfully.');
    }

    public function show(Franchise $franchise)
    {
        $franchise->load(['zone']);
        $bookings = \App\Models\Booking::with(['user', 'pet', 'staff'])
            ->where('franchise_id', $franchise->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.franchises.show', compact('franchise', 'bookings'));
    }

    public function edit(Franchise $franchise)
    {
        $zones = Zone::all();
        return view('admin.franchises.edit', compact('franchise', 'zones'));
    }

    public function update(Request $request, Franchise $franchise)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $rawImage = $franchise->getAttributes()['image'] ?? null;
            if ($rawImage) {
                Storage::disk('public')->delete($rawImage);
            }
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $data['image'] = $request->file('image')->storeAs('franchises', $imageName, 'public');
        }

        $franchise->update($data);

        return redirect()->route('admin.franchises.index')->with('success', 'Franchise updated successfully.');
    }

    public function destroy(Franchise $franchise)
    {
        $franchise->delete();

        return redirect()
            ->route('admin.franchises.index', ['status' => 'active'])
            ->with('success', 'Franchise deleted successfully.');
    }

    public function toggleStatus(Franchise $franchise)
    {
        $franchise->is_active = !$franchise->is_active;
        $franchise->save();

        $label = $franchise->is_active ? 'active' : 'inactive';

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => (bool) $franchise->is_active,
                'message' => "Franchise marked as {$label}.",
            ]);
        }

        return redirect()
            ->route('admin.franchises.index', ['status' => $franchise->is_active ? 'active' : 'inactive'])
            ->with('success', "Franchise marked as {$label}.");
    }

    public function restore($id)
    {
        $franchise = Franchise::onlyTrashed()->findOrFail($id);
        $franchise->restore();

        if (!$franchise->is_active) {
            $franchise->is_active = true;
            $franchise->save();
        }

        return redirect()
            ->route('admin.franchises.index', ['status' => 'active'])
            ->with('success', 'Franchise restored successfully.');
    }
}
