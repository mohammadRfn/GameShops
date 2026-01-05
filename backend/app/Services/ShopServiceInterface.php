<?php

namespace App\Services;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;

interface ShopServiceInterface
{
    /**
     */
    public function getAllShops(): Collection;

    /**
     */
    public function createShop(array $data): Shop;

    /**
     */
    public function updateShop(int $id, array $data): Shop;

    /**
     */
    public function getShopById(int $id): Shop;

    /**
     */
    public function deleteShop(int $id): void;
}
