<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'deal_id', 'price_per_unit', 'quantity', 'total_amount'
    ];

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the deal
     */
    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deal_id');
    }
}
