<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;

class ZoneController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'active');

        $activeCount = Zone::count();
        $deletedCount = Zone::onlyTrashed()->count();

        $query = Zone::withCount('franchises');

        if ($status === 'deleted') {
            $query->onlyTrashed();
        } else {
            $status = 'active';
        }

        $zones = $query->latest()->paginate(8)->appends(['status' => $status]);

        return view('admin.zone.index', compact(
            'zones',
            'status',
            'activeCount',
            'deletedCount'
        ));
    }

    public function create()
    {
        return view('admin.zone.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:zones',
            'coordinates' => 'required|string',
        ]);

        try {
            $coordinates = json_decode($request->coordinates, true);
            if (!is_array($coordinates) || count($coordinates) < 3) {
                return back()->withInput()->withErrors(['coordinates' => 'Invalid polygon coordinates. At least 3 points are required.']);
            }

            if ($coordinates[0] !== end($coordinates)) {
                $coordinates[] = $coordinates[0];
            }

            $points = array_map(function ($coord) {
                return new Point($coord['lat'], $coord['lng']);
            }, $coordinates);

            $lineString = new LineString($points);
            $polygon = new Polygon([$lineString]);

            Zone::create([
                'name' => $request->name,
                'location' => $points[0],
                'area' => $polygon,
            ]);

            return redirect()->route('admin.zones.index')->with('success', 'Zone created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['coordinates' => 'Error processing coordinates: ' . $e->getMessage()]);
        }
    }

    public function show(Zone $zone)
    {
        $zone->loadCount('franchises');
        return view('admin.zone.show', compact('zone'));
    }

    public function edit(Zone $zone)
    {
        return view('admin.zone.edit', compact('zone'));
    }

    public function update(Request $request, Zone $zone)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:zones,name,' . $zone->id,
            'coordinates' => 'required|string',
        ]);

        try {
            $coordinates = json_decode($request->coordinates, true);
            if (!is_array($coordinates) || count($coordinates) < 3) {
                return back()->withInput()->withErrors(['coordinates' => 'Invalid polygon coordinates.']);
            }

            if ($coordinates[0] !== end($coordinates)) {
                $coordinates[] = $coordinates[0];
            }

            $points = array_map(function ($coord) {
                return new Point($coord['lat'], $coord['lng']);
            }, $coordinates);

            $lineString = new LineString($points);
            $polygon = new Polygon([$lineString]);

            $zone->update([
                'name' => $request->name,
                'location' => $points[0],
                'area' => $polygon,
            ]);

            return redirect()->route('admin.zones.index')->with('success', 'Zone updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['coordinates' => 'Error updating zone: ' . $e->getMessage()]);
        }
    }

    public function destroy(Zone $zone)
    {
        $zone->delete();

        return redirect()
            ->route('admin.zones.index', ['status' => 'active'])
            ->with('success', 'Zone deleted successfully.');
    }

    public function restore($id)
    {
        $zone = Zone::onlyTrashed()->findOrFail($id);
        $zone->restore();

        return redirect()
            ->route('admin.zones.index', ['status' => 'active'])
            ->with('success', 'Zone restored successfully.');
    }
}
