<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'owner_user_id',
        'plan_id',
        'is_active',
        'subscription_ends_at',
        'settings',
    ];

    protected $casts = [
        'is_active'           => 'boolean',
        'subscription_ends_at'=> 'date',
        'settings'            => 'array',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function serviceJobs(): HasMany
    {
        return $this->hasMany(ServiceJob::class);
    }

    public function serviceTypes(): HasMany
    {
        return $this->hasMany(ServiceType::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function dailyItemStats(): HasMany
    {
        return $this->hasMany(DailyItemStat::class);
    }

    public function monthlySales(): HasMany
    {
        return $this->hasMany(MonthlySale::class);
    }
}
