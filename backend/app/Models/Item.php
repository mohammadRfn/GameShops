<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'price', 'description', 'image_path', 'shop_id'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
