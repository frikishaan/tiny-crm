<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'total_sales'
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'account_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'customer_id');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class, 'customer_id');
    }
}
