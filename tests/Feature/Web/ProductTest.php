<?php

namespace Tests\Feature\Web;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_products_index()
    {
        Product::factory()->count(12)->create();

        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Products/Index')
                ->has('products.data', 10) // Pagination is 10 in Web controller index
                ->has('categories')
            );
    }

    public function test_can_filter_products_by_category_on_index()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        Product::factory()->count(3)->create(['category_id' => $category1->id]);
        Product::factory()->count(2)->create(['category_id' => $category2->id]);

        $response = $this->get("/?category_id={$category1->id}");

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Products/Index')
                ->has('products.data', 3)
                ->where('filters.category_id', $category1->id)
            );
    }

    public function test_validation_errors_when_creating_product_on_web()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->from('/admin/products/create')
            ->post('/admin/products', [
                'name' => '',
                'price' => -5,
                'category_id' => 'invalid'
            ]);

        $response->assertRedirect('/admin/products/create');
        $response->assertSessionHasErrors(['name', 'price', 'category_id']);
    }

    public function test_soft_deleted_products_are_not_visible_in_web_list()
    {
        $product = Product::factory()->create();
        $product->delete();

        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Products/Index')
                ->has('products.data', 0)
            );
    }

    public function test_can_view_product_show()
    {
        $product = Product::factory()->create();

        $response = $this->get("/product/{$product->id}");

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Products/Show')
                ->has('product.data', fn (Assert $page) => $page
                    ->where('id', $product->id)
                    ->etc()
                )
            );
    }

    public function test_unauthenticated_user_cannot_view_admin_products()
    {
        $response = $this->get('/admin/products');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_admin_products()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Product::factory()->count(5)->create();

        $response = $this->get('/admin/products');

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Products/Index')
                ->has('products.data', 5)
            );
    }

    public function test_authenticated_user_can_create_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create();
        $data = [
            'name' => 'Web Product',
            'price' => 150,
            'category_id' => $category->id,
        ];

        $response = $this->post('/admin/products', $data);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Web Product']);
    }

    public function test_authenticated_user_can_update_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();
        $data = [
            'name' => 'Updated Web Product',
            'price' => 250,
            'category_id' => $product->category_id,
        ];

        $response = $this->put("/admin/products/{$product->id}", $data);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Web Product']);
    }

    public function test_authenticated_user_can_delete_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $response = $this->delete("/admin/products/{$product->id}");

        $response->assertRedirect(route('admin.products.index'));
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
