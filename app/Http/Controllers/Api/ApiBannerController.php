<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class ApiBannerController extends Controller
{
    public function index()
    {
        try {
            $banners = Banner::where('is_active', 1)->get();
            return $this->success($banners, 'Banners fetched successfully', 200);
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500);
        }
    }

    public function categoryWise($categoryId)
    {
        try {
            $banners = Banner::where('category_id', $categoryId)->where('is_active', 1)->get();
            if ($banners->isEmpty()) {
                return $this->error('Banners not found', 404);
            }
            return $this->success($banners, 'Banners fetched successfully', 200);
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500);
        }
    }
}
