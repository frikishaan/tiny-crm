<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'account_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
