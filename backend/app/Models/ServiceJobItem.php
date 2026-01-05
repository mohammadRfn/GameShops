<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceJobItem extends TenantScopedModel
{
    use SoftDeletes;
    protected $fillable = [
        'service_job_id',
        'item_id',
        'quantity',
        'unit_price',
        'total_price',
        'shop_id',
    ];

    protected $casts = [
        'quantity'   => 'integer',
        'unit_price' => 'decimal:2',
        'total_price'=> 'decimal:2',
    ];
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    public function serviceJob(): BelongsTo
    {
        return $this->belongsTo(ServiceJob::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
