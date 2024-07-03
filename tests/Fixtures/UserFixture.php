<?php

namespace Tests\Fixtures;

use App\Models\User;

trait UserFixture
{
    public function user(): User
    {
        return User::factory()->create();
    }

    public function userCreatePayload(array $mutations = []): array
    {
        return array_merge([
            'first_name' => 'john',
            'last_name' => 'doe',
            'email' => 'mail@provider.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => 'password',
            'phone_number' => 'password',
            'avatar' => 'password',
            'is_marketing'
        ], $mutations);
    }

    public function userAuthenticatePayload(array $mutations = []): array
    {
        return array_merge([
            'email' => 'mail.provider.com',
            'password' => 'password'
        ], $mutations);
    }
}
