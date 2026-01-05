<?php

namespace App\Services;

use App\Models\DailyItemStat;
use App\Models\MonthlySale;
use Illuminate\Database\Eloquent\Collection;

class StatsService implements StatsServiceInterface
{
    /**
     */
    public function getDailyItemStats(int $shopId, string $statDate): Collection
    {
        return DailyItemStat::where('shop_id', $shopId)
            ->where('stat_date', $statDate)
            ->get();
    }

    /**
     */
    public function getMonthlySales(int $shopId, int $year, int $month): Collection
    {
        return MonthlySale::where('shop_id', $shopId)
            ->where('year', $year)
            ->where('month', $month)
            ->get();
    }
}
