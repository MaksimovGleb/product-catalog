<?php

namespace App\Actions\Category;

use App\DTOs\CategoryDTO;
use App\Models\Category;

/** Экшен для обновления категории. */
class UpdateCategoryAction
{
    public function execute(Category $category, CategoryDTO $dto): bool
    {
        return $category->update($dto->toArray());
    }
}
