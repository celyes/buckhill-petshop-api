<?php

use Illuminate\Testing\Fluent\AssertableJson;

describe('product create tests', function () {
    it('should create a product', function () {
        $user = $this->user();
        $response = actingAs($user)->postJson('api/v1/product/create', createProductPayload());
        $response->assertStatus(201);
        $response->assertJson(fn(AssertableJson $json) => $json->has('data.title')
            ->where('data.price', (string)100.23)  # number_format returns a string
            ->has('data.description')
            ->whereType('data.metadata', 'array')
            ->has('data.metadata.image')
            ->has('data.metadata.brand')
            ->etc()
        );
    });
    it('should reject creating a product when request is invalid', function () {
        $user = $this->user();
        $response = actingAs($user)->postJson('api/v1/product/create', []);
        $response->assertStatus(422);

        $response->assertJson(fn(AssertableJson $json) => $json->has('message')
            ->has('errors')
            ->has('errors.title')
            ->etc()
        );
    });
    it('should reject creating a product when unauthenticated', function () {
        $response = $this->postJson('api/v1/product/create', []);
        $response->assertStatus(401);
    });
});

describe('product show tests', function () {
    it('should fetch a product', function () {
        $product = $this->product();

        $response = $this->getJson('/api/v1/product/' . $product->uuid);
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->has('data.title')
            ->has('data.price')
            ->has('data.description')
            ->whereType('data.metadata', 'array')
            ->has('data.metadata.image')
            ->has('data.metadata.brand')
            ->etc()
        );
    });
});

describe('product list tests', function () {
    it('should list products', function () {
        $product = $this->product();

        $response = $this->getJson('/api/v1/products');
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->has('data.0.title')
            ->has('data.0.price')
            ->has('data.0.description')
            ->whereType('data.0.metadata', 'array')
            ->has('data.0.metadata.image')
            ->has('data.0.metadata.brand')
            ->etc()
        );
    });
    it('should filter products', function () {
        $product = $this->product();
        $response = $this->getJson(sprintf(
            '/api/v1/products?title=%s',
            $product->title
        ));

        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->has('data.0.title')
            ->has('data.0.price')
            ->has('data.0.description')
            ->whereType('data.0.metadata', 'array')
            ->has('data.0.metadata.image')
            ->has('data.0.metadata.brand')
            ->etc()
        );
    });
});
