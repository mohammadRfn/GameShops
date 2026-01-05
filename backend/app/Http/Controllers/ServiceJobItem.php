<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceJobItemRequest;
use App\Http\Resources\ServiceJobItemResource;
use App\Services\ServiceJobItemServiceInterface;
use Illuminate\Http\Request;

class ServiceJobItemController extends Controller
{
    public function __construct(
        protected ServiceJobItemServiceInterface $serviceJobItemService
    ) {}

    /**
     */
    public function index(int $serviceJobId)
    {
        $items = $this->serviceJobItemService->getItemsByServiceJob($serviceJobId);

        return ServiceJobItemResource::collection($items);
    }

    /**
     */
    public function store(ServiceJobItemRequest $request, int $serviceJobId)
    {
        $data = $request->validated();
        $item = $this->serviceJobItemService->createItemForServiceJob($data, $serviceJobId);

        return new ServiceJobItemResource($item);
    }

    /**
     */
    public function update(ServiceJobItemRequest $request, int $id)
    {
        $data = $request->validated();
        $item = $this->serviceJobItemService->updateItemForServiceJob($id, $data);

        return new ServiceJobItemResource($item);
    }

    /**
     */
    public function destroy(int $id)
    {
        $this->serviceJobItemService->deleteItem($id);

        return response()->json(['message' => 'Item deleted successfully']);
    }

    /**
     */
    public function show(int $id)
    {
        $item = $this->serviceJobItemService->getItemById($id);

        return new ServiceJobItemResource($item);
    }
}
