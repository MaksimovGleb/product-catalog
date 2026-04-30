<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_get_token()
    {
        $user = User::factory()->create([
            'email' => 'Admin@mail.ru',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'Admin@mail.ru',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_login_fails_with_wrong_credentials()
    {
        User::factory()->create([
            'email' => 'Admin@mail.ru',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'Admin@mail.ru',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }
}
