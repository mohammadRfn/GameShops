<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerServiceInterface;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['name', 'email', 'request_status', 'invoice_status', 'search']);

        $customers = $this->customerService->getAllCustomers($filters);

        return CustomerResource::collection($customers);
    }

    public function show($id, Request $request)
    {
        $filters = $request->only(['request_status', 'invoice_status']);

        $customer = $this->customerService->getCustomerById($id, $filters);

        return new CustomerResource($customer);
    }

    public function store(CustomerRequest $request)
    {
        $customer = $this->customerService->createCustomer($request->validated());
        return new CustomerResource($customer);
    }

    public function update(CustomerRequest $request, $id)
    {
        $customer = $this->customerService->updateCustomer($id, $request->validated());
        return new CustomerResource($customer);
    }

    public function destroy($id)
    {
        $customer = $this->customerService->deleteCustomer($id);
        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
