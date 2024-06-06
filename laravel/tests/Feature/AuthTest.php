<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{    
    use DatabaseTransactions;

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'name' => 'login user',
            'email' => 'login@example.com',
            'password' => bcrypt('password'),
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
            'refresh_token'
        ]);
    }

    public function test_user_cannot_login_with_invalid_password()
    {
    
        $user = User::factory()->create([
            'name' => 'invalid login user',
            'email' => 'invalid@example.com',
            'password' => bcrypt('correct_password'),
        ]);
        
        $response = $this->postJson('/api/auth/login', [
            'email' => 'invalid@example.com',
            'password' => 'incorrect_password',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'error_description' => 'The user credentials were incorrect.',
        ]);
    }

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'register name',
            'email' => 'register@example.com',
            'password' => 'pass',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User registered successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'register name',
            'email' => 'register@example.com',
        ]);
    }

    public function test_invalid_registration()
    {
        $response = $this->postJson('/api/auth/register', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['name', 'email', 'password'],
            ]);
    }
}
