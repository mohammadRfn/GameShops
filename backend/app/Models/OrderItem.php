<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;
    protected $fillable = ['invoice_id', 'product_name', 'quantity', 'price', 'total_price', 'image_path', 'category_id', 'item_id', 'shop_id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class); 
    }
     public function item()
    {
        return $this->belongsTo(Item::class);  
    }
}
