<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Services\InvoiceServiceInterface;
use Illuminate\Http\Request;


class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceServiceInterface $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index()
    {
        $invoices = $this->invoiceService->getAllInvoices();

        return InvoiceResource::collection($invoices);
    }
    public function store(InvoiceRequest $request)
    {
        $requestId = $request->input('request_id');
        $data = $request->validated();

        if (isset($data['customer_id']) && !is_numeric($data['customer_id'])) {
            return response()->json(['error' => 'Invalid customer ID'], 400);
        }

        $invoice = $this->invoiceService->createInvoice($requestId, $data);

        return new InvoiceResource($invoice);
    }

    public function addOrderItemsToInvoice($invoiceId, Request $request)
    {
        $orderItemsData = $request->input('order_items');

        $invoice = $this->invoiceService->addOrderItemsToInvoice($invoiceId, $orderItemsData);
        return new InvoiceResource($invoice);
    }

    public function show($invoiceId)
    {
        $invoice = $this->invoiceService->getInvoice($invoiceId);

        return new InvoiceResource($invoice);
    }
    public function update($invoiceId, InvoiceRequest $request)
    {
        $data = $request->validated();

        if (isset($data['customer_id']) && !is_numeric($data['customer_id'])) {
            return response()->json(['error' => 'Invalid customer ID'], 400);
        }

        $invoice = $this->invoiceService->updateInvoice($invoiceId, $data);

        return new InvoiceResource($invoice);
    }

    public function destroy($invoiceId)
    {
        $this->invoiceService->deleteInvoice($invoiceId);

        return response()->json(['message' => 'Invoice deleted successfully'], 200);
    }

    public function confirmInvoice($invoiceId)
    {
        $invoice = $this->invoiceService->confirmInvoice($invoiceId);

        return new InvoiceResource($invoice);
    }
    public function setNotConfirmed($invoiceId)
    {
        $invoice = $this->invoiceService->setNotConfirmed($invoiceId);

        return new InvoiceResource($invoice);
    }
}
