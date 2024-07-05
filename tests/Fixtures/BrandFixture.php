<?php

namespace Tests\Fixtures;

use App\Models\Brand;

trait BrandFixture
{
    public function brand(): Brand
    {
        return Brand::factory()->create();
    }
}
