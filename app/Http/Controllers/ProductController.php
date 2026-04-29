<?php

namespace App\Http\Controllers;

use App\Actions\Product\CreateProductAction;
use App\Actions\Product\UpdateProductAction;
use App\DTOs\ProductDTO;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /** Публичный список товаров. */
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Products/Index', [
            'products' => ProductResource::collection($products),
            'categories' => CategoryResource::collection(Category::all()),
            'filters' => $request->only(['category_id', 'search']),
        ]);
    }

    /** Просмотр одного товара. */
    public function show(Product $product)
    {
        return Inertia::render('Products/Show', [
            'product' => new ProductResource($product),
        ]);
    }

    /** Список товаров для админки. */
    public function adminIndex(Request $request)
    {
        $products = Product::query()
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Products/Index', [
            'products' => ProductResource::collection($products),
            'categories' => CategoryResource::collection(Category::all()),
            'filters' => $request->only(['category_id', 'search']),
        ]);
    }

    /** Форма создания товара. */
    public function create()
    {
        return Inertia::render('Admin/Products/Create', [
            'categories' => CategoryResource::collection(Category::all()),
        ]);
    }

    /** Сохранение товара. */
    public function store(StoreProductRequest $request, CreateProductAction $action)
    {
        $action->execute(ProductDTO::fromRequest($request));

        return redirect()->route('admin.products.index')->with('success', 'Товар создан');
    }

    /** Форма редактирования товара. */
    public function edit(Product $product)
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => new ProductResource($product),
            'categories' => CategoryResource::collection(Category::all()),
        ]);
    }

    /** Обновление товара. */
    public function update(UpdateProductRequest $request, Product $product, UpdateProductAction $action)
    {
        $action->execute($product, ProductDTO::fromRequest($request));

        return redirect()->route('admin.products.index')->with('success', 'Товар обновлен');
    }

    /** Удаление товара. */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Товар удален');
    }
}
