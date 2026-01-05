<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanRequest;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Services\PlanServiceInterface;

class PlanController extends Controller
{
    public function __construct(
        protected PlanServiceInterface $planService
    ) {}

    public function index()
    {
        return PlanResource::collection($this->planService->getAllPlans());
    }

    public function store(PlanRequest $request)
    {
        $plan = $this->planService->createPlan($request->validated());

        return new PlanResource($plan);
    }

    public function show(int $id)
    {
        $plan = $this->planService->getPlanById($id);

        return new PlanResource($plan);
    }

    public function update(int $id, PlanRequest $request)
    {
        $plan = $this->planService->updatePlan($id, $request->validated());

        return new PlanResource($plan);
    }

    public function destroy(int $id)
    {
        $this->planService->deletePlan($id);

        return response()->json(['message' => 'Plan deleted successfully']);
    }
}
