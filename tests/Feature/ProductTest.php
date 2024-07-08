<?php

use Illuminate\Testing\Fluent\AssertableJson;

describe('product create tests', function () {
    it('should create a product', function () {
        $user = $this->user();
        $response = actingAs($user)->postJson('api/v1/product/create', createProductPayload());
        $response->assertStatus(201);
        $response->assertJson(fn (AssertableJson $json) => $json->has('data.title')
            ->where('data.price', (string) 100.23)  # number_format returns a string
            ->has('data.description')
            ->whereType('data.metadata', 'array')
            ->has('data.metadata.image')
            ->has('data.metadata.brand')
            ->etc()
        );
    });
});
