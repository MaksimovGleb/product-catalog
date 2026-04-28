<?php

namespace App\Actions\Product;

use App\DTOs\ProductDTO;
use App\Models\Product;

/** Экшен для создания товара. */
class CreateProductAction
{
    public function execute(ProductDTO $dto): Product
    {
        return Product::create($dto->toArray());
    }
}
