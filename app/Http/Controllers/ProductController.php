<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *      name="Product",
 *      description="Product related endpoints"
 *  ),
 */
class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $products = Product::filter($request->all())->paginate($request->input('perPage', 25));
        return ProductResource::collection($products);
    }

    /**
     * @param Product $product
     * @return JsonResource
     * @OA\Get(
     *        path="/api/v1/prodct/{uuid}",
     *        summary="Show a single product",
     *        tags={"Product"},
     *
     *   @OA\Parameter(
     *             name="uuid",
     *             in="path",
     *             description="User's first name",
     *             required=false,
     *             @OA\Schema(type="string")
     *         ),
     *
     *   @OA\Response(response="200", description="Product fetched successfully"),
     *   @OA\Response(response="404", description="Not found")
     *    )
     */
    public function show(Product $product): JsonResource
    {
        return new ProductResource($product);
    }

    /**
     * @param CreateProductRequest $request
     * @return JsonResource
     * @OA\Put(
     *        path="/api/v1/product/create",
     *        summary="Create a product",
     *        tags={"User"},
     *
     *   @OA\Parameter(
     *             name="title",
     *             in="query",
     *             description="Product title",
     *             required=false,
     *
     *             @OA\Schema(type="string")
     *         ),
     *
     *   @OA\Parameter(
     *              name="price",
     *              in="query",
     *              description="Product price",
     *              required=false,
     *
     *              @OA\Schema(type="string")
     *          ),
     *
     *   @OA\Parameter(
     *            name="description",
     *            in="query",
     *            description="Product description",
     *            required=false,
     *
     *            @OA\Schema(type="string")
     *        ),
     *
     *   @OA\Parameter(
     *            name="image",
     *            in="query",
     *            description="UUID of the product image",
     *            required=false,
     *
     *            @OA\Schema(type="string")
     *        ),
     *
     *   @OA\Parameter(
     *             name="brand",
     *             in="query",
     *             description="UUID of the product brand",
     *             required=false,
     *
     *             @OA\Schema(type="string")
     *         ),
     *
     *   @OA\Response(response="201", description="Product created successfully"),
     *   @OA\Response(response="422", description="Validation errors")
     *    )
     */
    public function create(CreateProductRequest $request): JsonResource
    {
        $product = Product::create($request->all());
        return new ProductResource($product);
    }
}
