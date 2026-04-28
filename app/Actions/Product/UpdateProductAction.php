<?php

namespace App\Actions\Product;

use App\DTOs\ProductDTO;
use App\Models\Product;

/** Экшен для обновления товара. */
class UpdateProductAction
{
    public function execute(Product $product, ProductDTO $dto): bool
    {
        return $product->update($dto->toArray());
    }
}
