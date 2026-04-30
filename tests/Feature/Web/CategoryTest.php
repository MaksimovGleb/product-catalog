<?php

namespace Tests\Feature\Web;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_admin_categories()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Category::factory()->count(3)->create();

        $response = $this->get('/admin/categories');

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Categories/Index')
                ->has('categories.data', 3)
            );
    }

    public function test_authenticated_user_can_create_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'name' => 'New Category',
            'description' => 'Description'
        ];

        $response = $this->post('/admin/categories', $data);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'New Category']);
    }

    public function test_authenticated_user_can_update_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create();
        $data = [
            'name' => 'Updated Category',
            'description' => 'Updated Description'
        ];

        $response = $this->put("/admin/categories/{$category->id}", $data);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated Category']);
    }

    public function test_authenticated_user_can_delete_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create();

        $response = $this->delete("/admin/categories/{$category->id}");

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_validation_errors_when_creating_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/admin/categories', [
            'name' => ''
        ]);

        $response->assertSessionHasErrors(['name']);
    }
}
