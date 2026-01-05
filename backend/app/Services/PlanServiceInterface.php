<?php

namespace App\Services;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;

interface PlanServiceInterface
{
    /**
     */
    public function getAllPlans(): Collection;

    /**
     */
    public function createPlan(array $data): Plan;

    /**
     */
    public function updatePlan(int $id, array $data): Plan;

    /**
     */
    public function getPlanById(int $id): Plan;

    /**
     */
    public function deletePlan(int $id): void;
}
