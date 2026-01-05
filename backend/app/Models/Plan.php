<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'code',
        'monthly_price',
        'yearly_price',
        'has_inventory_module',
        'has_services_module',
        'has_analytics_module',
        'max_users',
        'max_items',
        'is_active',
    ];

    protected $casts = [
        'monthly_price'        => 'decimal:2',
        'yearly_price'         => 'decimal:2',
        'has_inventory_module' => 'boolean',
        'has_services_module'  => 'boolean',
        'has_analytics_module' => 'boolean',
        'max_users'            => 'integer',
        'max_items'            => 'integer',
        'is_active'            => 'boolean',
    ];

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }
}
