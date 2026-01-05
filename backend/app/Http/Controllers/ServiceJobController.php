<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceJobRequest;
use App\Http\Resources\ServiceJobResource;
use App\Models\ServiceJob;
use App\Services\ServiceJobServiceInterface;
use Illuminate\Http\Request as HttpRequest;

class ServiceJobController extends Controller
{
    public function __construct(
        protected ServiceJobServiceInterface $serviceJobService
    ) {}

    public function index(HttpRequest $request)
    {
        $query = ServiceJob::with('serviceType', 'customer')
            ->orderByDesc('created_at');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $jobs = $query->paginate(20);

        return ServiceJobResource::collection($jobs);
    }

    public function store(ServiceJobRequest $request)
    {
        $serviceJob = $this->serviceJobService->createServiceJob($request->validated());

        return new ServiceJobResource($serviceJob);
    }

    public function show(int $id)
    {
        $serviceJob = $this->serviceJobService->findServiceJob($id);

        return new ServiceJobResource($serviceJob);
    }

    public function update(int $id, ServiceJobRequest $request)
    {
        $serviceJob = $this->serviceJobService->updateServiceJob($id, $request->validated());

        return new ServiceJobResource($serviceJob);
    }

    public function destroy(int $id)
    {
        $this->serviceJobService->deleteServiceJob($id);
        return response()->json(['message' => 'Service job archived successfully']);
    }
}
