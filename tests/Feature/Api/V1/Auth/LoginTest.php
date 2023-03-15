<?php

namespace Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test user can not log in without required fields
     * Check required validation
     * @return void
     */
    public function test_not_login_without_required_fields(): void
    {

        $this->post('/api/v1/auth/login', [])
            ->assertUnprocessable() // Status code 422
            ->assertJson([
                'success' => false,
                'message' => [
                    "email" => [
                        "The email field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ]
                ]

            ]);
    }

    /**
     * Test user can not log in with invalid login detail
     * @return void
     */
    public function test_not_login_with_invalid_credentials(): void
    {
        $data = [
            'email' => fake()->safeEmail,
            'password' => '123456d1',
        ];

        $this->post('/api/v1/auth/login', $data)
            ->assertUnauthorized() // Status code 401
            ->assertJson([
                'success' => false,
                'message' => "These credentials do not match our records."
            ]);

    }

    /**
     * Test user can log in with valid login detail
     * @return void
     */
    public function test_login_with_valid_credentials(): void
    {
        $user = User::factory()->create();
        $data = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $this->post('/api/v1/auth/login', $data)
            ->assertSuccessful() // Status code 200
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'role',
                    'created_at',
                    'updated_at',
                    'token'
                ]

            ]);

    }
}
