<?php

namespace App\Models;

use App\Events\DealQualified;
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

    public function closeAsWon()
    {
        $this->status = 2;
        $this->date_won = now();
        $this->save();

        DealQualified::dispatch($this);
    }
    
    public function closeAsLost()
    {
        $this->status = 3;
        $this->date_lost = now();
        $this->save();
    }
}
