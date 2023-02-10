<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    public $fillable = [
        'title', 'customer_id', 'estimated_revenue', 'lead_id',
        'actual_revenue', 'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Account::class, 'customer_id');
    }

    /**
     * Get the products added for the deal
     */
    public function products()
    {
        return $this->hasMany(DealProduct::class, 'deal_id');
    }
}
