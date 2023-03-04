<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    /**
     * Test Customer register without required fields
     * Check validation
     * @return void
     */
    public function test_customer_not_register_without_required_fields() : void
    {

        $this->post('/api/v1/auth/register/customer', [])
            ->assertUnprocessable() // Status code 422
            ->assertJson([
                'success' => false,
                'message' => [
                    "name" => [
                        "The name field is required."
                    ],
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
     * Test Customer register with required fields
     * Create Customer
     * @return void
     */
    public function test_customer_register_with_required_fields() : void
    {
        $password = Str::random(8);
        $data = [
            'name' => fake()->name,
            'email' => fake()->safeEmail,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->post('/api/v1/auth/register/customer', $data)
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
                    'role',
                    'created_at',
                    'updated_at'
                ]

            ]);
    }

    /**
     * Test Admin register without required fields
     * Check validation
     * @return void
     */
    public function test_admin_not_register_without_required_fields() : void
    {

        $this->post('/api/v1/auth/register/admin', [])
            ->assertUnprocessable() // Status code 422
            ->assertJson([
                'success' => false,
                'message' => [
                    "name" => [
                        "The name field is required."
                    ],
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
     * Test Admin register with required fields
     * Create Admin
     * @return void
     */
    public function test_admin_register_with_required_fields() : void
    {
        $password = Str::random(8);
        $data = [
            'name' => fake()->name,
            'email' => fake()->safeEmail,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->post('/api/v1/auth/register/admin', $data)
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
                    'role',
                    'created_at',
                    'updated_at'
                ]

            ]);
    }
}
