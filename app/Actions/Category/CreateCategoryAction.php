<?php

namespace App\Actions\Category;

use App\DTOs\CategoryDTO;
use App\Models\Category;

/** Экшен для создания категории. */
class CreateCategoryAction
{
    public function execute(CategoryDTO $dto): Category
    {
        return Category::create($dto->toArray());
    }
}
