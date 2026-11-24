<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;

class ApiGalleryController extends Controller
{

    public function index()
    {
        try {
            $galleries = Gallery::orderBy('created_at', 'desc')->get();
            return $this->success($galleries, 'Category retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    public function categoryWise($id)
    {
        try {
            $galleries = Gallery::where('category_id', $id)->get();
            return $this->success($galleries, 'Category retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
