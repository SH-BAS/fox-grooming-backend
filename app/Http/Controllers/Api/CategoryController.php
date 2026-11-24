<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\CategoryType;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return $this->success($categories, 'Categories retrieved successfully');
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->error('Category not found', 404);
        }
        return $this->success($category, 'Category retrieved successfully');
    }
    public function breedList($categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            return $this->error('Category not found', 404);
        }
        $breeds = CategoryType::where('category_id', $categoryId)->get();
        if ($breeds->isEmpty()) {
            return $this->error('Breeds not found', 404);
        }
        return $this->success($breeds, 'Breeds retrieved successfully');
    }
}
