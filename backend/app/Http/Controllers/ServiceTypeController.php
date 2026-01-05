<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceTypeRequest;
use App\Http\Resources\ServiceTypeResource;
use App\Services\ServiceTypeServiceInterface;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function __construct(
        protected ServiceTypeServiceInterface $serviceTypeService
    ) {}

    /**
     */
    public function index()
    {
        $serviceTypes = $this->serviceTypeService->getAllServiceTypes();

        return ServiceTypeResource::collection($serviceTypes);
    }

    /**
     */
    public function store(ServiceTypeRequest $request)
    {
        $data = $request->validated();

        $serviceType = $this->serviceTypeService->createServiceType($data);

        return new ServiceTypeResource($serviceType);
    }

    /**
     */
    public function update(ServiceTypeRequest $request, int $id)
    {
        $data = $request->validated();

        $serviceType = $this->serviceTypeService->updateServiceType($id, $data);

        return new ServiceTypeResource($serviceType);
    }

    /**
     */
    public function destroy(int $id)
    {
        $this->serviceTypeService->deleteServiceType($id);

        return response()->json(['message' => 'Service type deleted successfully']);
    }

    /**
     */
    public function show(int $id)
    {
        $serviceType = $this->serviceTypeService->getServiceTypeById($id);

        return new ServiceTypeResource($serviceType);
    }
}
