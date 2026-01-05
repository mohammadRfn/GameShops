<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderItemRequest;
use App\Http\Resources\OrderItemResource;
use App\Services\OrderItemService;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    protected $orderItemService;

    public function __construct(OrderItemService $orderItemService)
    {
        $this->orderItemService = $orderItemService;
    }

    public function index()
    {
        $orderItems = $this->orderItemService->getAllOrderItems();

        return OrderItemResource::collection($orderItems);
    }

    public function store(OrderItemRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image');
        }

        $invoiceId = $request->input('invoice_id');
        $orderItem = $this->orderItemService->createOrderItem($validated, $invoiceId);
        $orderItem->load('item', 'category');

        return new OrderItemResource($orderItem);
    }

    public function show($id)
    {
        $orderItem = $this->orderItemService->findOrderItem($id);
        $orderItem->load('item', 'category');

        return new OrderItemResource($orderItem);
    }

    public function update(OrderItemRequest $request, $id)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image');
        }

        $orderItem = $this->orderItemService->updateOrderItem($id, $validated);
        $orderItem->load('item', 'category');

        return new OrderItemResource($orderItem);
    }

    public function destroy($id)
    {
        $orderItem = $this->orderItemService->findOrderItem($id); 
        $invoiceId = $orderItem->invoice_id;

        $this->orderItemService->deleteOrderItem($id);

        $this->orderItemService->updateInvoiceTotalAmount($invoiceId);

        return response()->json(['message' => 'Order item deleted successfully']);
    }
}
