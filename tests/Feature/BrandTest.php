<?php

use Illuminate\Testing\Fluent\AssertableJson;

describe('brand listing tests', function () {
    it('should list all brands', function () {
        $this->seed();
        $response = $this->getJson('/api/v1/brands');
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->where('current_page', 1)
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

        $response = $this->getJson('/api/v1/brand/' . $brand->uuid);
        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->where('success', true)
            ->has('data.uuid')
            ->has('data.title')
            ->has('data.slug')
            ->has('data.created_at')
            ->has('data.updated_at')
            ->etc()
        );
    });
});
