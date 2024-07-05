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

class BrandController extends Controller
{
    protected BrandService $brandService;

    public function __construct()
    {
        $this->brandService = App::make(BrandService::class);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json($this->brandService->listBrands($request->all()));
    }


    /**
     * @param Request $request
     * @param Brand $brand
     * @return JsonResponse
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
