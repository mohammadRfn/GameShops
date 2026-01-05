<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailyItemStatResource;
use App\Http\Resources\MonthlySaleResource;
use App\Services\StatsServiceInterface;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function __construct(
        protected StatsServiceInterface $statsService
    ) {}

    public function dailyItemStats(Request $request)
    {
        $shopId   = (int) $request->input('shop_id');
        $statDate = $request->input('date');

        $stats = $this->statsService->getDailyItemStats($shopId, $statDate);

        return DailyItemStatResource::collection($stats);
    }

    public function monthlySales(Request $request)
    {
        $shopId = (int) $request->input('shop_id');
        $year   = (int) $request->input('year');
        $month  = (int) $request->input('month');

        $sales = $this->statsService->getMonthlySales($shopId, $year, $month);

        return MonthlySaleResource::collection($sales);
    }
}
