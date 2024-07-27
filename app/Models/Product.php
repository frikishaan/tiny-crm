<?php

namespace App\Models;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'name', 'type', 'price', 'is_available'
    ];

    protected $casts = [
        'type' => ProductType::class
    ];
}
