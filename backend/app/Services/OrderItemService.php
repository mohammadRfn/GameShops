<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Invoice;
use App\Models\Item;

class OrderItemService implements OrderItemInterface
{
    public function getAllOrderItems()
    {
        return OrderItem::with('item', 'category')->get();
    }

    public function createOrderItem(array $data, $invoiceId): OrderItem
    {
        if (empty($data['item_id']) || empty($data['quantity']) || empty($data['category_id'])) {
            throw new \Exception("Missing required fields.");
        }
        $item = Item::findOrFail($data['item_id']);

        $totalPrice = $data['quantity'] * $item->price;
        $orderItem = OrderItem::create([
            'item_id' => $item->id,
            'quantity' => $data['quantity'],
            'total_price' => $totalPrice,
            'invoice_id' => $invoiceId,
            'category_id' => $data['category_id'],
            'product_name' => $item->name,
            'price' => $item->price,
        ]);
        if (isset($data['image'])) {
            $orderItem = $this->storeImage($orderItem, $data['image']);
        }

        return $orderItem;
    }

    public function updateOrderItem(int $id, array $data): OrderItem
    {
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->update($data);
        $orderItem->total_price = $orderItem->quantity * $orderItem->price;
        $orderItem->save();

        if (isset($data['image'])) {
            $orderItem = $this->storeImage($orderItem, $data['image']);
        }

        return $orderItem;
    }

    public function deleteOrderItem(int $id): void
    {
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->delete();
    }

    public function findOrderItem(int $id): OrderItem
    {
        return OrderItem::findOrFail($id);
    }

    private function storeImage(OrderItem $orderItem, $image): OrderItem
    {
        $imagePath = $image->store('images/order_items', 'public');

        $orderItem->image_path = $imagePath;
        $orderItem->save();

        return $orderItem;
    }
    public function updateInvoiceTotalAmount(int $invoiceId): void
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $totalAmount = OrderItem::where('invoice_id', $invoiceId)->sum('total_price');

        $invoice->total_amount = $totalAmount;
        $invoice->save();
    }
}
