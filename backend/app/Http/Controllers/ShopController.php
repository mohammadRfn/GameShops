<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopRequest;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use App\Services\ShopServiceInterface;

class ShopController extends Controller
{
    public function __construct(
        protected ShopServiceInterface $shopService
    ) {}

    public function index()
    {
        return ShopResource::collection($this->shopService->getAllShops());
    }

    public function store(ShopRequest $request)
    {
        $shop = $this->shopService->createShop($request->validated());

        return new ShopResource($shop);
    }

    public function show(int $id)
    {
        $shop = $this->shopService->getShopById($id);

        return new ShopResource($shop);
    }

    public function update(int $id, ShopRequest $request)
    {
        $shop = $this->shopService->updateShop($id, $request->validated());

        return new ShopResource($shop);
    }

    public function destroy(int $id)
    {
        $this->shopService->deleteShop($id);

        return response()->json(['message' => 'Shop deleted successfully']);
    }
}
