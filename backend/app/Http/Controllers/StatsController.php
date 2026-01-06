<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailyItemStatResource;
use App\Http\Resources\MonthlySaleResource;
use App\Models\DailyItemStat;
use App\Models\OrderItem;
use App\Services\StatsServiceInterface;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function __construct(
        protected StatsServiceInterface $statsService
    ) {}

    public function dailyStats(Request $request, int $shopId)
    {
        $statDate = $request->input('date', now()->format('Y-m-d'));

        $stats = $this->statsService->getDailyItemStats($shopId, $statDate);

        $debug = [
            'shop_id' => $shopId,
            'stat_date' => $statDate,
            'table_count' => DailyItemStat::where('shop_id', $shopId)->where('stat_date', $statDate)->count(),
            'order_items_today' => OrderItem::whereHas('invoice', fn($q) => $q->where('shop_id', $shopId)->whereDate('created_at', $statDate))->count()
        ];

        return response()->json([
            'data' => DailyItemStatResource::collection($stats),
            'debug' => $debug
        ]);
    }


    public function monthlyStats(Request $request, int $shopId)  // ← $shopId اضافه!
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $sales = $this->statsService->getMonthlySales($shopId, $year, $month);
        return MonthlySaleResource::collection($sales);
    }

    public function dailyItemStats(Request $request, int $shopId)  // ← $shopId اضافه!
    {
        $statDate = $request->input('date', now()->format('Y-m-d'));
        $stats = $this->statsService->getDailyItemStats($shopId, $statDate);
        return DailyItemStatResource::collection($stats);
    }
}
