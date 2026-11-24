<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Franchise;
use App\Models\Zone;
use App\Models\Tax;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ApiFranchiseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Accepts latitude & longitude query parameters.
     * - If the user location falls inside a zone polygon, returns all franchises for that zone.
     * - Otherwise, returns franchises within 10 km ordered by nearest distance.
     */
    public function index(Request $request)
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
            ]);

            $latitude = (float) $request->query('latitude');
            $longitude = (float) $request->query('longitude');

            $result = Franchise::getByLocation($latitude, $longitude);

            if (isset($result['message']) && $result['franchises']->isEmpty()) {
                return response()->json([
                    'message' => $result['message'],
                    'franchises' => $result['franchises'],
                ]);
            }

            return response()->json($result);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Latitude and longitude are required', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $franchise = Franchise::find($id);
            if (!$franchise) {
                return $this->error('Franchise not found', 404);
            }
            return $this->success($franchise, 'Franchise fetched successfully', 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
