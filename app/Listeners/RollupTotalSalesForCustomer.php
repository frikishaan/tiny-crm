<?php

namespace App\Listeners;

use App\Events\DealQualified;
use App\Models\Account;
use App\Models\Deal;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RollupTotalSalesForCustomer implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection = 'database';

    public $afterCommit = true;

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\DealQualified  $event
     * @return void
     */
    public function handle(DealQualified $event)
    {
        $customer = Account::find($event->deal->customer_id);
        $customer->total_sales = Deal::where('customer_id', $customer->id)
                                    ->sum('actual_revenue');

        $customer->save();
    }
}
