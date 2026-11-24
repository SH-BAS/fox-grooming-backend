<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Resources\ServiceResource;

class ApiServiceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $services = Service::orderBy('created_at', 'desc')->with('sizes')->get();
            return $this->success(ServiceResource::collection($services), 'Services retrieved');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    // categoryWiseServices
    public function categoryWiseServices($categoryId)
    {
        try {
            $services = Service::where('category_id', $categoryId)->orderBy('created_at', 'desc')->with('sizes')->get();

            return $this->success(ServiceResource::collection($services), 'Category wise services retrieved');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function serviceDetail($serviceId)
    {
        try {
            $service = Service::where('id', $serviceId)->with('sizes')->first();
            if (!$service) {
                return $this->error('Service not found', 404);
            }
            return $this->success(new ServiceResource($service), 'Service detail retrieved');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
