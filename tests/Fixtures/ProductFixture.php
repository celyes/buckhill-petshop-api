<?php

namespace Tests\Fixtures;

use App\Models\Product;

trait ProductFixture
{
    public function product(): Product
    {
        return Product::factory()->create();
    }
}
