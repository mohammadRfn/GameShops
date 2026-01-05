<?php

namespace App\Services;

use App\Models\Request;
use Illuminate\Support\Collection;

class RequestService implements RequestServiceInterface
{
    public function createRequest(array $data)
    {
        $customer_id = isset($data['customer_id']) ? $data['customer_id'] : null;
        if (!is_array($data['category_id'])) {
            $data['category_id'] = [$data['category_id']];
        }
        return Request::create([
            'customer_name' => $data['customer_name'],
            'category_id' => json_encode($data['category_id']),
            'description' => $data['description'],
            'status' => $data['status'],
            'customer_id' => $customer_id
        ]);
    }
    public function getAllItems(): Collection
    {
        $requests = Request::all();

        foreach ($requests as $request) {
            $request->category_id = json_decode($request->category_id, true);
        }

        return $requests;
    }
    public function updateRequest(int $requestId, array $data)
    {
        $request = Request::findOrFail($requestId);
        if (!is_array($data['category_id'])) {
            $data['category_id'] = [$data['category_id']];
        }

        $data['category_id'] = json_encode($data['category_id']);
        $request->update($data);
        return $request;
    }

    public function deleteRequest(int $requestId)
    {
        $request = Request::findOrFail($requestId);
        $request->delete();
    }

    public function showRequest(int $requestId)
    {
        $request = Request::findOrFail($requestId);

        $request->category_id = json_decode($request->category_id, true);

        return $request;
    }
}
