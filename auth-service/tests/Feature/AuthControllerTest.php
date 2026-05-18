<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_login_and_access_me_and_logout()
    {
        $user = User::create([
            'name' => 'Admin Test',
            'username' => 'admin-test',
            'email' => 'admin@test.local',
            'password' => Hash::make('secret123'),
        ]);

        $login = $this->postJson('/api/login', [
            'username' => 'admin-test',
            'email' => 'admin@test.local',
            'password' => 'secret123',
        ]);

        $login->assertStatus(200)->assertJsonPath('status', 'success')->assertJsonStructure(['data' => ['id', 'username', 'email'], 'token']);

        $token = $login->json('token');

        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->getJson('/api/me')
             ->assertStatus(200)
             ->assertJsonPath('data.email', 'admin@test.local');

        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/logout')
             ->assertStatus(200)
             ->assertJsonPath('status', 'success');
    }

    public function test_it_returns_error_for_invalid_login_credentials()
    {
        User::create([
            'name' => 'Admin Test',
            'username' => 'admin-test',
            'email' => 'admin@test.local',
            'password' => Hash::make('secret123'),
        ]);

        $login = $this->postJson('/api/login', [
            'username' => 'admin-test',
            'email' => 'admin@test.local',
            'password' => 'wrongpass',
        ]);

        $login->assertStatus(401)
              ->assertJsonPath('status', 'error')
              ->assertJsonPath('message', 'Username, Email, atau Password salah!');
    }
}
