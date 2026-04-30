<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products()
    {
        Category::factory()->has(Product::factory()->count(20))->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(15, 'data') // Pagination is 15 in API controller
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 
                        'name', 
                        'description', 
                        'price', 
                        'category' => ['id', 'name']
                    ]
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total']
            ]);
    }

    public function test_can_filter_products_by_category()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        Product::factory()->count(3)->create(['category_id' => $category1->id]);
        Product::factory()->count(2)->create(['category_id' => $category2->id]);

        $response = $this->getJson("/api/products?category_id={$category1->id}");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_validation_errors_when_creating_product()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/products', [
            'name' => '',
            'price' => -10,
            'category_id' => 'non-existent-id'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'category_id']);
    }

    public function test_soft_deleted_products_are_not_visible_in_list()
    {
        $product = Product::factory()->create();
        $product->delete();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonMissing(['id' => $product->id]);
    }

    public function test_can_show_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $product->id);
    }

    public function test_unauthenticated_user_cannot_create_product()
    {
        $category = Category::factory()->create();
        $data = [
            'name' => 'New Product',
            'price' => 100,
            'category_id' => $category->id,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_product()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = Category::factory()->create();
        $data = [
            'name' => 'New Product',
            'price' => 100,
            'category_id' => $category->id,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', ['name' => 'New Product']);
    }

    public function test_can_update_product()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $product = Product::factory()->create();
        $data = ['name' => 'Updated Name', 'price' => 200, 'category_id' => $product->category_id];

        $response = $this->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name']);
    }

    public function test_can_delete_product()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
