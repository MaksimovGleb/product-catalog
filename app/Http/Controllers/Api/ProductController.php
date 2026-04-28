<?php

namespace App\Http\Controllers\Api;

use App\Actions\Product\CreateProductAction;
use App\Actions\Product\UpdateProductAction;
use App\DTOs\ProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /** GET /api/products — список всех товаров с пагинацией. */
    public function index(Request $request)
    {
        $products = Product::when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->paginate(15);

        return ProductResource::collection($products);
    }

    /** GET /api/products/{id} — просмотр одного товара. */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /** POST /api/products — создание товара. */
    public function store(StoreProductRequest $request, CreateProductAction $action)
    {
        $product = $action->execute(ProductDTO::fromRequest($request));

        return new ProductResource($product);
    }

    /** PUT/PATCH /api/products/{id} — обновление товара. */
    public function update(UpdateProductRequest $request, Product $product, UpdateProductAction $action)
    {
        $action->execute($product, ProductDTO::fromRequest($request));

        return new ProductResource($product->fresh());
    }

    /** DELETE /api/products/{id} — удаление товара. */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
