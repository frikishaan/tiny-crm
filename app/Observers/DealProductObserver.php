<?php

namespace App\Observers;

use App\Models\Deal;
use App\Models\DealProduct;

class DealProductObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;
    
    /**
     * Handle the DealProduct "updated" event.
     *
     * @param  \App\Models\DealProduct  $dealProduct
     * @return void
     */
    public function saved(DealProduct $dealProduct)
    {
        $this->calculateRevenue($dealProduct);
    }
    
    /**
     * Handle the DealProduct "deleted" event.
     *
     * @param  \App\Models\DealProduct  $dealProduct
     * @return void
     */
    public function deleted(DealProduct $dealProduct)
    {
        $this->calculateRevenue($dealProduct);
    }

    private function calculateRevenue(DealProduct $dealProduct)
    {
        $amount = DealProduct::where('deal_id', $dealProduct->deal_id)
            ->sum('total_amount');

        $deal = Deal::find($dealProduct->deal_id);
        $deal->actual_revenue = $amount;
        $deal->update();
    }
}
