<?php

namespace App\DTOs;

use Illuminate\Http\Request;

/** DTO для товара. */
readonly class ProductDTO
{
    public function __construct(
        public string $name,
        public string $category_id,
        public float $price,
        public ?string $description = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->validated('name'),
            category_id: $request->validated('category_id'),
            price: (float) $request->validated('price'),
            description: $request->validated('description'),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'description' => $this->description,
        ];
    }
}
