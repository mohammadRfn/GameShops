<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemResource;
use App\Services\ItemServiceInterface;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $itemService;

    public function __construct(ItemServiceInterface $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index()
    {
        $items = $this->itemService->getAllItems();
        return ItemResource::collection($items);
    }

    public function store(ItemRequest $request)
    {
        $validated = $request->validated();  

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image');
        }

        $item = $this->itemService->createItem($validated);
        return new ItemResource($item);
    }

    public function show($id)
    {
        $item = $this->itemService->findItem($id);
        return new ItemResource($item);
    }

    public function update(ItemRequest $request, $id)
    {
        $validated = $request->validated();  

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image');
        }

        $item = $this->itemService->updateItem($id, $validated);
        return new ItemResource($item);
    }

    public function destroy($id)
    {
        $this->itemService->deleteItem($id);
        return response()->json(['message' => 'Item deleted successfully']);
    }
}
