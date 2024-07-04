<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/


use App\Services\JwtService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;

uses(
    Tests\TestCase::class,
    RefreshDatabase::class
)->in('Feature');

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function actingAs(Authenticatable $user, ?string $token = null)
{
    return test()->withHeader(
        'Authorization', sprintf('Bearer %s', $token ?? tokenFor($user)->toString())
    );
}

function tokenFor($user)
{
    $jwtService = App::make(JwtService::class);
    $token = $jwtService->createAccessToken([
        'grant_type' => 'access_token',
        'user_uuid' => $user->uuid,
        'uid' => $user->id
    ]);
    $jwtService->persistToken($token);
    return $token;
}
