<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    /** GET /api/categories — список всех категорий. */
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }
}
