<?php

namespace Tests\Fixtures;

use App\Models\User;

trait UserFixture
{
    public static function user()
    {
        return User::factory()->create();
    }
}
