<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonthlySaleRequest;
use App\Http\Resources\MonthlySaleResource;
use App\Services\MonthlySaleServiceInterface;
use Illuminate\Http\Request;

class MonthlySaleController extends Controller
{
    public function __construct(
        protected MonthlySaleServiceInterface $monthlySaleService
    ) {}

    /**
     */
    public function index(Request $request)
    {
        $shopId = (int) $request->input('shop_id');
        $year   = (int) $request->input('year');
        $month  = (int) $request->input('month');

        $sales = $this->monthlySaleService->getMonthlySales($shopId, $year, $month);

        return MonthlySaleResource::collection($sales);
    }

    /**
     */
    public function store(MonthlySaleRequest $request)
    {
        $data = $request->validated();

        $sale = $this->monthlySaleService->createOrUpdateMonthlySales(
            $data['shop_id'],
            $data['year'],
            $data['month'],
            $data
        );

        return new MonthlySaleResource($sale);
    }

    /**
     */
    public function show(int $shopId, int $year, int $month)
    {
        $sales = $this->monthlySaleService->getMonthlySales($shopId, $year, $month);

        return MonthlySaleResource::collection($sales);
    }
}
