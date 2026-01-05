<?php

namespace App\Services;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;

class PlanService implements PlanServiceInterface
{
    /**
     */
    public function getAllPlans(): Collection
    {
        return Plan::all();
    }

    /**
     */
    public function createPlan(array $data): Plan
    {
        return Plan::create($data);
    }

    /**
     */
    public function updatePlan(int $id, array $data): Plan
    {
        $plan = Plan::findOrFail($id);
        $plan->update($data);
        return $plan;
    }

    /**
     */
    public function getPlanById(int $id): Plan
    {
        return Plan::findOrFail($id);
    }

    /**
     */
    public function deletePlan(int $id): void
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();
    }
}
