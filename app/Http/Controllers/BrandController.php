<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBrandRequest;
use App\Http\Requests\DeleteBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *      name="Brand",
 *      description="Brand related endpoints"
 *  ),
 */
class BrandController extends Controller
{
    protected BrandService $brandService;

    public function __construct()
    {
        $this->brandService = App::make(BrandService::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @OA\Get(
     *      path="/api/v1/brands",
     *      summary="List brands",
     *      @OA\Response(response="200", description="Succses"),
     *      security={{"bearerAuth":{}}},
     *     tags={"Brand"},
     *      @OA\Parameter(
     *           name="per_page",
     *           in="query",
     *           description="Number of results in page. default is 10.",
     *           required=false,
     *           @OA\Schema(type="int")
     *      ),
     *      @OA\Parameter(
     *            name="orderBy",
     *            in="query",
     *            description="The field to which the results are ordered by. default is Id.",
     *            required=false,
     *            @OA\Schema(type="string")
     *       ),
     *        @OA\Parameter(
     *             name="limit",
     *             in="query",
     *             description="The limit of results returned. returns all matching resutls if no limit is provided.",
     *             required=false,
     *             @OA\Schema(type="int")
     *        ),
     *        @OA\Parameter(
     *              name="desc",
     *              in="query",
     *              description="Specifies whether to order the results descendingly or ascendingly. default is false. (asc)",
     *              required=false,
     *              @OA\Schema(type="boolean")
     *         ),
     * ),
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json($this->brandService->listBrands($request->all()));
    }


    /**
     * @param Request $request
     * @param Brand $brand
     * @return JsonResponse
     * @OA\Get(
     *       path="/api/v1/brand/{uuid}",
     *       summary="Fetch a single brand",
     *       @OA\Response(response="200", description="Succses"),
     *       @OA\Response(response="401", description="Unauthenticated"),
     *       security={{"bearerAuth":{}}},
     *      tags={"Brand"},
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          description="UUID of the brand"
     *      ),
     *  ),
     */
    public function show(Request $request, Brand $brand): JsonResponse
    {
        try {
            $brand = $this->success((new BrandResource($brand))->toArray($request));
            return response()->json($brand);
        } catch (\Exception $e) {
            return response()->json($this->error(
                error: 'Failed to get item.',
                errors: [$e->getMessage()],
                trace: $e->getTrace()
            ), 400);
        }
    }

    /**
     * @param CreateBrandRequest $request
     * @return JsonResponse
     * @OA\Post(
     *        path="/api/v1/brand/create",
     *        summary="Create a new brand",
     *        @OA\Response(response="200", description="Succses"),
     *        @OA\Response(response="401", description="Unauthenticated"),
     *        @OA\Response(response="422", description="Validation errors"),
     *        security={{"bearerAuth":{}}},
     *       tags={"Brand"},
     *       @OA\Parameter(
     *           name="title",
     *           required=true,
     *           in="query",
     *           @OA\Schema(type="string"),
     *           description="The title of the brand"
     *       ),
     *   ),
     */
    public function create(CreateBrandRequest $request): JsonResponse
    {
        try {
            $brand = Brand::create($request->validated());
            $brand = $this->success(['uuid' => $brand->uuid]);
            return response()->json($brand, 201);
        } catch (\Exception $e) {
            return response()->json($this->error(
                error: 'Failed to get item.',
                errors: [$e->getMessage()],
                trace: $e->getTrace()
            ), 400);
        }
    }

    /**
     * @param UpdateBrandRequest $request
     * @param Brand $brand
     * @return JsonResponse
     * @OA\Put(
     *         path="/api/v1/brand/{uuid}",
     *         summary="Update a brand",
     *         @OA\Response(response="200", description="Succses"),
     *         @OA\Response(response="401", description="Unauthenticated"),
     *         @OA\Response(response="422", description="Validation errors"),
     *         security={{"bearerAuth":{}}},
     *        tags={"Brand"},
     *         @OA\Parameter(
     *             name="uuid",
     *             in="path",
     *             required=true,
     *             @OA\Schema(type="string"),
     *             description="The unique UUID of the brand"
     *         ),
     *        @OA\Parameter(
     *            name="title",
     *            required=false,
     *            in="query",
     *            @OA\Schema(type="string"),
     *            description="The title of the brand"
     *        ),
     *    ),
     */
    public function edit(UpdateBrandRequest $request, Brand $brand): JsonResponse
    {
        try {
            $brand = $brand->fill($request->validated());
            $brand->save();
            $brand = $this->success((new BrandResource($brand))->toArray($request));
            return response()->json($brand);
        } catch (\Exception $e) {
            return response()->json($this->error(
                error: 'Failed to update item.',
                errors: [$e->getMessage()],
                trace: $e->getTrace()
            ), 400);
        }
    }

    /**
     * @param DeleteBrandRequest $request
     * @param Brand $brand
     * @return JsonResponse
     * @OA\Delete(
     *          path="/api/v1/brand/{uuid}",
     *          summary="Update a brand",
     *          @OA\Response(response="200", description="Succses"),
     *          @OA\Response(response="401", description="Unauthenticated"),
     *          @OA\Response(response="422", description="Validation errors"),
     *          security={{"bearerAuth":{}}},
     *          tags={"Brand"},
     *          @OA\Parameter(
     *             name="uuid",
     *             in="path",
     *             required=true,
     *             @OA\Schema(type="string"),
     *             description="The unique UUID of the brand"
     *         ),
     *     ),
     */
    public function delete(DeleteBrandRequest $request, Brand $brand): JsonResponse
    {
        try {
            $brand->delete();
            return response()->json($this->success());
        } catch (\Exception $e) {
            return response()->json($this->error(
                error: $e->getMessage(),
                errors: [$e->getMessage()],
                trace: $e->getTrace()
            ), 400);
        }
    }
}
