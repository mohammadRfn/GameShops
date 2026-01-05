<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;

class CustomerService implements CustomerServiceInterface
{
    public function getAllCustomers($filters = [])
    {
        $query = Customer::query();

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (isset($filters['request_status'])) {
            $query->whereHas('requests', function (Builder $q) use ($filters) {
                $q->where('status', 'like', '%' . $filters['request_status'] . '%');
            });
        }

        if (isset($filters['invoice_status'])) {
            $query->whereHas('invoices', function (Builder $q) use ($filters) {
                $q->where('is_confirmed', $filters['invoice_status']);
            });
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                    ->orWhereHas('requests', function (Builder $q) use ($filters) {
                        $q->where('status', 'like', '%' . $filters['search'] . '%');
                    })
                    ->orWhereHas('invoices', function (Builder $q) use ($filters) {
                        $q->where('invoice_number', 'like', '%' . $filters['search'] . '%');
                    });
            });
        }

        return $query->paginate(10);
    }

    public function createCustomer(array $data)
    {
        return Customer::create($data);
    }

    public function getCustomerById($id, $filters = [])
    {
        $customer = Customer::with(['requests', 'invoices'])->findOrFail($id);

        if (isset($filters['request_status'])) {
            $customer->requests = $customer->requests->filter(function ($request) use ($filters) {
                return $request->status === $filters['request_status'];
            });
        }

        if (isset($filters['invoice_status'])) {
            $customer->invoices = $customer->invoices->filter(function ($invoice) use ($filters) {
                return $invoice->is_confirmed === $filters['invoice_status'];
            });
        }

        return $customer;
    }

    public function updateCustomer($id, array $data)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($data);
        return $customer;
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return $customer;
    }
}
