<?php

use Illuminate\Testing\Fluent\AssertableJson;

describe('Login tests', function () {
    test('rejects login attempts with invalid credentials', function () {
        $user = $this->user();
        $response = $this->postJson('/api/v1/user/login', [
            'email' => $user->email,
            'password' => 'wrong-password'
        ]);
        $response->assertStatus(400);
        $response->assertJson(fn(AssertableJson $json) => $json->where('message', 'Invalid credentials')
            ->etc());
    });

    test('can login using email and password', function () {
        $user = $this->user();
        $response = $this->postJson('/api/v1/user/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->where('user.id', 1)
            ->where('user.first_name', $user->first_name)
            ->where('user.last_name', $user->last_name)
            ->where('user.email', $user->email)
            ->missing('user.password')
            ->has('refresh_token')
            ->has('access_token')
            ->etc());
    });
});
