<?php

namespace App\Services;

use App\Models\DailyItemStat;
use App\Models\MonthlySale;
use App\Models\OrderItem;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class StatsService implements StatsServiceInterface
{
    public function getDailyItemStats(int $shopId, string $statDate): Collection
    {
        $this->populateDailyItemStats($shopId, $statDate);

        return DailyItemStat::where('shop_id', $shopId)
            ->where('stat_date', $statDate)
            ->orderByDesc('revenue')
            ->get();
    }

    private function populateDailyItemStats(int $shopId, string $statDate)
    {
        DailyItemStat::where('shop_id', $shopId)
            ->where('stat_date', $statDate)
            ->delete();

        OrderItem::whereHas('invoice', function ($q) use ($shopId, $statDate) {
            $q->where('shop_id', $shopId)
                ->whereDate('created_at', $statDate);
        })
            ->select('product_name')
            ->groupBy('product_name')
            ->chunk(100, function ($items) use ($shopId, $statDate) {
                foreach ($items as $item) {
                    $totals = OrderItem::where('product_name', $item->product_name)
                        ->whereHas('invoice', fn($q) => $q->where('shop_id', $shopId)
                            ->whereDate('created_at', $statDate))
                        ->selectRaw('SUM(quantity) as sold_quantity, SUM(total_price) as revenue, MAX(item_id) as item_id')
                        ->first();

                    DailyItemStat::updateOrCreate(
                        [
                            'shop_id' => $shopId,
                            'stat_date' => $statDate,
                            'item_id' => $totals->item_id,
                        ],
                        [
                            'product_name' => $item->product_name,
                            'sold_quantity' => $totals->sold_quantity,
                            'revenue' => $totals->revenue,
                        ]
                    );
                }
            });
    }

    public function getMonthlySales(int $shopId, int $year, int $month): Collection
    {
        return MonthlySale::where('shop_id', $shopId)
            ->where('year', $year)
            ->where('month', $month)
            ->get();
    }
}
