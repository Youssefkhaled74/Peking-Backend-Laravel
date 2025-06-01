<?php

namespace App\Http\Controllers\Frontend;


use App\Http\Resources\NormalItemResource;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Services\ItemService;

class ItemController extends Controller
{

    public ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $brandId = $request->get('brand_id');
            return NormalItemResource::collection($this->itemService->listByBrand($request, $brandId));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function featuredItems(PaginateRequest $request)
    {
        try {
            $brandId = $request->get('brand_id');
            $branchId = $request->get('branch_id');
            return NormalItemResource::collection($this->itemService->featuredItemsByBrand($brandId , $branchId));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function mostPopularItems(PaginateRequest $request)
    {
        try {
            $brandId = $request->get('brand_id');
            $branchId = $request->get('branch_id');
            return NormalItemResource::collection($this->itemService->mostPopularItemsByBrand($brandId , $branchId ));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
