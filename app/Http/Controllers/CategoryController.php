<?php

namespace App\Http\Controllers;

use App\Actions\Category\CreateCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\DTOs\CategoryDTO;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /** Список категорий для админки. */
    public function index()
    {
        $categories = Category::all();

        return Inertia::render('Admin/Categories/Index', [
            'categories' => CategoryResource::collection($categories),
        ]);
    }

    /** Сохранение категории. */
    public function store(StoreCategoryRequest $request, CreateCategoryAction $action)
    {
        $action->execute(CategoryDTO::fromRequest($request));

        return redirect()->route('admin.categories.index')->with('success', 'Категория создана');
    }

    /** Обновление категории. */
    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategoryAction $action)
    {
        $action->execute($category, CategoryDTO::fromRequest($request));

        return redirect()->route('admin.categories.index')->with('success', 'Категория обновлена');
    }

    /** Удаление категории. */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Категория удалена');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
