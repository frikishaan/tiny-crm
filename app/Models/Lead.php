<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(Account::class, 'customer_id');
    }
}
