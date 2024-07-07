<?php

use App\Models\Brand;
use Illuminate\Testing\Fluent\AssertableJson;

describe('brand listing tests', function () {
    it('should list all brands', function () {
        $this->seed();
        $response = $this->getJson('/api/v1/brands');
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json->where('current_page', 1)
            ->has('data.0.uuid')
            ->has('data.0.title')
            ->has('data.0.slug')
            ->has('data.0.created_at')
            ->has('data.0.updated_at')
            ->etc()
        );
    });

    it('should fetch a single brand', function () {
        $brand = $this->brand();

        $response = $this->getJson('/api/v1/brand/'.$brand->uuid);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json->where('success', true)
            ->has('data.uuid')
            ->has('data.title')
            ->has('data.slug')
            ->has('data.created_at')
            ->has('data.updated_at')
            ->etc()
        );
    });
});

describe('brand creation tests', function () {
    it('should create a brand', function () {
        $user = $this->user();
        $response = actingAs($user)->postJson('api/v1/brand/create', ['title' => 'Test Brand']);
        $response->assertStatus(201);
        $response->assertJson(fn (AssertableJson $json) => $json->where('success', true)
            ->has('data.uuid')
            ->etc()
        );
    });
    it('should reject creating a brand when request is invalid', function () {
        $user = $this->user();
        $response = actingAs($user)->postJson('api/v1/brand/create', []);
        $response->assertStatus(422);

        $response->assertJson(fn (AssertableJson $json) => $json->has('message')
            ->has('errors')
            ->has('errors.title')
            ->etc()
        );
    });
    it('should reject creating a brand when unauthenticated', function () {
        $response = $this->postJson('api/v1/brand/create', []);
        $response->assertStatus(401);
    });
});

describe('brand update tests', function () {
    it('should update brand', function () {
        $user = $this->user();
        $brand = $this->brand();
        $response = actingAs($user)->putJson('api/v1/brand/'.$brand->uuid, ['title' => 'Updated brand']);
        $response->assertStatus(200);

        $brand = Brand::where('uuid', $brand->uuid)->first();

        $this->assertEquals($brand->title, 'Updated brand');
    });
    it('should reject updating a brand when request is invalid', function () {
        $user = $this->user();
        $brand = $this->brand();
        $response = actingAs($user)->putJson('api/v1/brand/'.$brand->uuid, []);
        $response->assertStatus(422);

        $response->assertJson(fn (AssertableJson $json) => $json->has('message')
            ->has('errors')
            ->has('errors.title')
            ->etc()
        );
    });
    it('should reject updating a brand when unauthenticated', function () {
        $brand = $this->brand();
        $response = $this->putJson('api/v1/brand/'.$brand->uuid, []);
        $response->assertStatus(401);
    });
});

describe('brand delete tests', function () {
    it('should delete brand', function () {
        $user = $this->user();
        $brand = $this->brand();
        $response = actingAs($user)->deleteJson('api/v1/brand/'.$brand->uuid);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json->where('success', true)
            ->etc()
        );
    });
    it('should reject deleting a brand when unauthenticated', function () {
        $brand = $this->brand();
        $response = $this->deleteJson('api/v1/brand/'.$brand->uuid);
        $response->assertStatus(401);
    });
});
