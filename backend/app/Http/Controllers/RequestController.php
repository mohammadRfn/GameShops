<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestRequest;
use App\Http\Resources\RequestResource;
use App\Services\RequestServiceInterface;
use Illuminate\Http\Request as HttpRequest;

class RequestController extends Controller
{
    protected $requestService;

    public function __construct(RequestServiceInterface $requestService)
    {
        $this->requestService = $requestService;
    }

    public function index()
    {
        $requests = $this->requestService->getAllItems();
        return RequestResource::collection($requests);
    }
    public function store(RequestRequest $request)
    {
        $data = $request->validated();
        if (isset($data['customer_id']) && !is_numeric($data['customer_id'])) {
            return response()->json(['error' => 'Invalid customer ID'], 400);
        }
        $createdRequest = $this->requestService->createRequest($data);

        return new RequestResource($createdRequest);
    }

    public function update($id, RequestRequest $request)
    {
        $data = $request->validated();
        $updatedRequest = $this->requestService->updateRequest($id, $data);

        return new RequestResource($updatedRequest);
    }

    public function destroy($id)
    {
        $this->requestService->deleteRequest($id);

        return response()->json(['message' => 'Request deleted successfully']);
    }

    public function show($id)
    {
        $request = $this->requestService->showRequest($id);

        return new RequestResource($request);
    }
}
