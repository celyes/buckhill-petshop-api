<?php

use Illuminate\Testing\Fluent\AssertableJson;

describe('Login tests', function () {
    it('should reject login attempts with invalid credentials', function () {

        $user = $this->user();

        $response = $this->postJson('/api/v1/user/login', [
            'email' => $user->email,
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(400);
        $response->assertJson(fn(AssertableJson $json) => $json->where('message', 'Invalid credentials')
            ->etc()
        );
    });
    it('should login using email and password', function () {
        $user = $this->user();

        $response = $this->postJson(
            '/api/v1/user/login',
            $this->userAuthenticatePayload(['email' => $user->email])
        );

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


describe('Account creation tests', function () {
    it('should create account', function () {
        $user = $this->userCreatePayload([
            'password' => '$3cr3t#Pa$$w0rd',
            'password_confirmation' => '$3cr3t#Pa$$w0rd'
        ]);
        $response = $this->postJson('/api/v1/user/create', $user);

        $response->assertStatus(201);
        $response->assertJson(fn(AssertableJson $json) => $json->where('user.id', 1)
            ->where('user.first_name', $user['first_name'])
            ->where('user.last_name', $user['last_name'])
            ->where('user.email', $user['email'])
            ->missing('user.password')
            ->has('refresh_token')
            ->has('access_token')
            ->etc()
        );
    });
    it('should reject creation attempts with invalid data', function () {

        // Make the validation fail...
        $user = $this->userCreatePayload([
            'password' => null,
        ]);
        $response = $this->postJson('/api/v1/user/create', $user);

        $response->assertStatus(422);
        $response->assertJson(fn(AssertableJson $json) => $json->has('message')
            ->has('errors')
            ->etc()
        );
    });
});

describe('Account edit tests', function () {
    it('should update account', function () {

        $user = $this->user();
        $response = actingAs($user)
            ->putJson('/api/v1/user/edit', [
                'first_name' => 'jane'
            ]);

        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->where('data.id', 1)
            ->where('data.first_name', 'jane')
            ->etc()
        );
    });
    it('should reject update attempts with invalid data', function () {

        // Make the validation fail...
        $user = $this->user();
        $response = actingAs($user)
            ->putJson('/api/v1/user/edit', [
                'password' => null
            ]);

        $response->assertStatus(422);
        $response->assertJson(fn(AssertableJson $json) => $json->has('message')
            ->has('errors')
            ->etc()
        );
    });
});
