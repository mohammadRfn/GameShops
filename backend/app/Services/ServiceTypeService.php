<?php

namespace App\Services;

use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Collection;

class ServiceTypeService implements ServiceTypeServiceInterface
{
    /**
     */
    public function getAllServiceTypes(): Collection
    {
        return ServiceType::all();
    }

    /**
     */
    public function createServiceType(array $data): ServiceType
    {
        return ServiceType::create($data);
    }

    /**
     */
    public function updateServiceType(int $id, array $data): ServiceType
    {
        $serviceType = ServiceType::findOrFail($id);
        $serviceType->update($data);
        return $serviceType;
    }

    /**
     */
    public function deleteServiceType(int $id): void
    {
        $serviceType = ServiceType::findOrFail($id);
        $serviceType->delete();
    }

    /**
     */
    public function getServiceTypeById(int $id): ServiceType
    {
        return ServiceType::findOrFail($id);
    }
}
