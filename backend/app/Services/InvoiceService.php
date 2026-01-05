<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class InvoiceService implements InvoiceServiceInterface
{
    protected $orderItemService;
    public function __construct(OrderItemService $orderItemService)
    {
        $this->orderItemService = $orderItemService;
    }
    public function getAllInvoices()
    {
        return Invoice::all();
    }
    public function calculateTotalAmount(int $invoiceId): float
    {
        $totalAmount = OrderItem::where('invoice_id', $invoiceId)->sum('total_price');

        return $totalAmount;
    }
    public function createInvoice($requestId, array $data): Invoice
    {
        $customer_id = isset($data['customer_id']) ? $data['customer_id'] : null;
        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . uniqid(),
            'request_id' => $requestId,
            'customer_id' => $customer_id,
            'total_amount' => 0,
            'is_confirmed' => null,
        ]);

        return $invoice;
    }
    public function addOrderItemsToInvoice(int $invoiceId, array $orderItemsData)
    {
        foreach ($orderItemsData as $itemData) {
            $this->orderItemService->createOrderItem($itemData, $invoiceId);
        }

        $totalAmount = OrderItem::where('invoice_id', $invoiceId)->sum('total_price');

        $invoice = Invoice::findOrFail($invoiceId);
        $invoice->total_amount = $totalAmount;
        $invoice->save();

        return $invoice;
    }
    public function updateInvoice(int $invoiceId, array $data): Invoice
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $invoice->update([
            'invoice_number' => isset($data['invoice_number']) ? $data['invoice_number'] : $invoice->invoice_number,
            'customer_id' => isset($data['customer_id']) ? $data['customer_id'] : $invoice->customer_id,
            'total_amount' => isset($data['total_amount']) ? $data['total_amount'] : $invoice->total_amount,
            'is_confirmed' => isset($data['is_confirmed']) ? $data['is_confirmed'] : $invoice->is_confirmed,
        ]);

        return $invoice;
    }

    public function deleteInvoice(int $invoiceId): bool
    {
        $invoice = Invoice::findOrFail($invoiceId);
        return $invoice->delete();
    }
    public function confirmInvoice(int $invoiceId): Invoice
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $invoice->is_confirmed = Invoice::STATUS_CONFIRMED;
        $invoice->save();
        $this->updateRequestStatus($invoice, 'completed');

        return $invoice;
    }
    public function setNotConfirmed(int $invoiceId): Invoice
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $invoice->is_confirmed = Invoice::STATUS_NOT_CONFIRMED;
        $invoice->save();
        $this->updateRequestStatus($invoice, 'canceled');
        return $invoice;
    }
    protected function updateRequestStatus(Invoice $invoice, string $status)
    {
        $request = $invoice->request;
        if ($request) {
            $request->update(['status' => $status]);
        }
    }

    public function getInvoice(int $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        if ($invoice->orderItems->count() > 0) {
            $totalAmount = $this->calculateTotalAmount($invoiceId);
            $invoice->total_amount = $totalAmount;
            $invoice->save();
        } else {
            $orderItemsData = $invoice->orderItems->toArray();
            $invoice = $this->addOrderItemsToInvoice($invoiceId, $orderItemsData);
        }

        return $invoice;
    }
}
