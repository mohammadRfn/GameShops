<?php

namespace App\Services;

use App\Models\MonthlySale;
use Illuminate\Database\Eloquent\Collection;

class MonthlySaleService implements MonthlySaleServiceInterface
{
    /**
     */
    public function getMonthlySales(int $shopId, int $year, int $month): Collection
    {
        return MonthlySale::where('shop_id', $shopId)
            ->where('year', $year)
            ->where('month', $month)
            ->get();
    }

    /**
     */
    public function createOrUpdateMonthlySales(int $shopId, int $year, int $month, array $data): MonthlySale
    {
        return MonthlySale::updateOrCreate(
            [
                'shop_id' => $shopId,
                'year'    => $year,
                'month'   => $month,
            ],
            $data
        );
    }
}
