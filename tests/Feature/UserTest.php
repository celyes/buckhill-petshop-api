<?php

describe('User tests', function () {
    test('can create user account', function () {
        $response = $this->postJson('/api/v1/user/create', [
            'first_name' => 'john',
            'last_name' => 'doe',
            'email' => 'mail@provider.com',
            'password' => 'S3cr3t#P@s$w0rd',
            'password_confirmation' => 'S3cr3t#P@s$w0rd',
            'address' => 'avenue 1, picadelli street. London',
            'phone_number' => '+14086764635',
            'is_marketing' => false
        ]);
        $this->assertJson($response->getBody());
        $response->assertStatus(201);
    });
    test('can log user account in', function () {
        $response = $this->postJson('/api/v1/user/login', [
            'email' => 'mail@provider.com',
            'password' => 'S3cr3t#P@s$w0rd'
        ]);

        $response->assertStatus(200);
    });
});
