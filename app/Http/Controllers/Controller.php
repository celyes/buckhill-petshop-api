<?php

namespace App\Http\Controllers;

use App\Traits\Controllers\HasCustomResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Pet Shop API - OpenAPI documentation",
 *     version="1.0.0",
 *  )
 *
 * @OA\SecurityScheme(
 *      type="http",
 *      securityScheme="bearerAuth",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 *  )
 */
abstract class Controller
{
    use HasCustomResponse;
}
