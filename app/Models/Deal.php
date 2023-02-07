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
}
