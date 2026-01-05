<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockMovementRequest;
use App\Http\Resources\StockMovementResource;
use App\Models\Item;
use App\Models\StockMovement;
use App\Services\StockMovementServiceInterface;
use Illuminate\Http\Request as HttpRequest;

class StockMovementController extends Controller
{
    public function __construct(
        protected StockMovementServiceInterface $stockMovementService
    ) {}

    /**
     */
    public function getItemStock(HttpRequest $request, int $itemId)
    {
        $shopId = (int) $request->input('shop_id'); 

        $item = Item::where('shop_id', $shopId)->findOrFail($itemId);

        $stock = $this->stockMovementService->getCurrentStock($shopId, $item->id);

        return response()->json([
            'item_id'       => $item->id,
            'item_name'     => $item->name,
            'current_stock' => $stock,
        ]);
    }

    /**
     */
    public function storeManualMovement(StockMovementRequest $request)
    {
        $movement = $this->stockMovementService->createManualMovement($request->validated());

        return new StockMovementResource($movement);
    }

    /**
     */
    public function index(HttpRequest $request)
    {
        $shopId = (int) $request->input('shop_id');

        $query = StockMovement::with('item')
            ->where('shop_id', $shopId)
            ->orderByDesc('created_at');

        if ($itemId = $request->input('item_id')) {
            $query->where('item_id', $itemId);
        }

        $movements = $query->paginate(30);

        return StockMovementResource::collection($movements);
    }
}
