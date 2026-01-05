<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'email', 'phone', 'address', 'shop_id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class, Request::class, 'customer_id', 'request_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}
