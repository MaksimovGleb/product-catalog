<?php

namespace App\DTOs;

use Illuminate\Http\Request;

/** DTO для категории. */
readonly class CategoryDTO
{
    public function __construct(
        public string $name,
        public ?string $description = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->validated('name'),
            description: $request->validated('description'),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
