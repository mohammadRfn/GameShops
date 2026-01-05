<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\OrderItem;
use App\Models\ServiceJob;
use App\Models\ServiceJobItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StockMovementService implements StockMovementServiceInterface
{
    public function getCurrentStock(int $shopId, int $itemId): int
    {
        $in = StockMovement::where('shop_id', $shopId)
            ->where('item_id', $itemId)
            ->whereIn('movement_type', [
                StockMovement::TYPE_IN,
                StockMovement::TYPE_ADJUST_IN,
            ])
            ->sum('quantity');

        $out = StockMovement::where('shop_id', $shopId)
            ->where('item_id', $itemId)
            ->whereIn('movement_type', [
                StockMovement::TYPE_OUT,
                StockMovement::TYPE_ADJUST_OUT,
            ])
            ->sum('quantity');

        return $in - $out;
    }

    public function createManualMovement(array $data): StockMovement
    {
        $this->validateMovementType($data['movement_type'] ?? '');

        if (($data['quantity'] ?? 0) <= 0) {
            throw new RuntimeException('Quantity must be positive.');
        }

        return StockMovement::create([
            'shop_id'       => $data['shop_id'],
            'item_id'       => $data['item_id'],
            'movement_type' => $data['movement_type'],
            'quantity'      => $data['quantity'],
            'unit_cost'     => $data['unit_cost'] ?? null,
            'reason'        => $data['reason'] ?? 'manual_adjustment',
            'note'          => $data['note'] ?? null,
        ]);
    }

    public function recordSaleMovementsForInvoice(Invoice $invoice): void
    {
        if (! $invoice->shop_id) {
            throw new RuntimeException('Invoice must belong to a shop to record stock movements.');
        }

        $invoice->loadMissing('orderItems');

        if ($invoice->orderItems->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($invoice) {
            foreach ($invoice->orderItems as $orderItem) {
                /** @var OrderItem $orderItem */
                if (! $orderItem->item_id || ! $orderItem->quantity) {
                    continue;
                }

                $currentStock = $this->getCurrentStock($invoice->shop_id, $orderItem->item_id);
                if ($currentStock < $orderItem->quantity) {
                    throw new RuntimeException("Not enough stock for item ID {$orderItem->item_id}.");
                }

                StockMovement::create([
                    'shop_id'       => $invoice->shop_id,
                    'item_id'       => $orderItem->item_id,
                    'invoice_id'    => $invoice->id,
                    'movement_type' => StockMovement::TYPE_OUT,
                    'quantity'      => $orderItem->quantity,
                    'unit_cost'     => null,
                    'reason'        => 'sale',
                    'note'          => "Invoice #{$invoice->invoice_number}",
                ]);
            }
        });
    }

    public function recordConsumptionForServiceJob(ServiceJob $serviceJob): void
    {
        if (! $serviceJob->shop_id) {
            throw new RuntimeException('Service job must belong to a shop to record stock movements.');
        }

        $serviceJob->loadMissing('items', 'invoice');

        if ($serviceJob->items->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($serviceJob) {
            foreach ($serviceJob->items as $jobItem) {
                /** @var ServiceJobItem $jobItem */
                if (! $jobItem->item_id || ! $jobItem->quantity) {
                    continue;
                }

                $currentStock = $this->getCurrentStock($serviceJob->shop_id, $jobItem->item_id);
                if ($currentStock < $jobItem->quantity) {
                    throw new RuntimeException("Not enough stock for item ID {$jobItem->item_id} for service job {$serviceJob->id}.");
                }

                StockMovement::create([
                    'shop_id'        => $serviceJob->shop_id,
                    'item_id'        => $jobItem->item_id,
                    'service_job_id' => $serviceJob->id,
                    'invoice_id'     => $serviceJob->invoice_id,
                    'movement_type'  => StockMovement::TYPE_OUT,
                    'quantity'       => $jobItem->quantity,
                    'unit_cost'      => null,
                    'reason'         => 'service_consumption',
                    'note'           => "Service job #{$serviceJob->id}",
                ]);
            }
        });
    }

    protected function validateMovementType(string $movementType): void
    {
        $allowed = [
            StockMovement::TYPE_IN,
            StockMovement::TYPE_OUT,
            StockMovement::TYPE_ADJUST_IN,
            StockMovement::TYPE_ADJUST_OUT,
        ];

        if (! in_array($movementType, $allowed, true)) {
            throw new RuntimeException("Invalid stock movement type: {$movementType}");
        }
    }
}
