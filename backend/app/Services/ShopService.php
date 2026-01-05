<?php

namespace App\Services;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;

class ShopService implements ShopServiceInterface
{
    /**
     */
    public function getAllShops(): Collection
    {
        return Shop::all();
    }

    /**
     */
    public function createShop(array $data): Shop
    {
        return Shop::create($data);
    }

    /**
     */
    public function updateShop(int $id, array $data): Shop
    {
        $shop = Shop::findOrFail($id);
        $shop->update($data);
        return $shop;
    }

    /**
     * دریافت اطلاعات یک فروشگاه.
     */
    public function getShopById(int $id): Shop
    {
        return Shop::findOrFail($id);
    }

    /**
     */
    public function deleteShop(int $id): void
    {
        $shop = Shop::findOrFail($id);
        $shop->delete();
    }
}
