<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class TenantScopedModel extends Model
{
    protected static function booted()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            $shopId = request()?->shop_id ?? app('current_shop')?->id;
            $builder->where('shop_id', $shopId);
        });
    }
}
