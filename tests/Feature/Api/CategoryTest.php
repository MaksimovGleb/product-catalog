<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories()
    {
        Category::factory()->count(5)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }
}
