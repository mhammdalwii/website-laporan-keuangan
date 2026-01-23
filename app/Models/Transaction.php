<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Transaction extends Model
{
    protected $fillable = [
        'name',
        'order_id',
        'product_id',
        'quantity',
        'total_price',
        'total_hpp',
        'status',
        'payment_type',
        'payment_response',
        'image',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

