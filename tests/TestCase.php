<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Fixtures\BrandFixture;
use Tests\Fixtures\ProductFixture;
use Tests\Fixtures\UserFixture;

abstract class TestCase extends BaseTestCase
{
    use UserFixture;
    use BrandFixture;
    use ProductFixture;
}
