<?php

namespace Tests;

use App\Models\User;

class AuthorizationHelper
{
    public static function authorization($role='CUSTOMER')
    {
        $user = User::factory()->create(['role' => $role]);
        $data = [
            'email' => $user->email,
            'password' => 'password'
        ];
        $response = $this->post('/api/v1/auth/login', $data)
            ->assertStatus(200);
        $token = $response['data']['token'];

        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
    }
}
