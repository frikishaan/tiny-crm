<?php

namespace App\Filament\Resources\LeadResource\Widgets;

use Akaunting\Money\Money;
use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class LeadStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Open Leads', Lead::where('status', 1)->count()),
            Card::make('Qualified leads', Lead::where('status', 2)->count()),
            Card::make('Disqualified leads', Lead::where('status', 3)->count()),
            Card::make('Avg Estimated Revenue', 
                    Money::USD(
                        Lead::whereIn('status', [2, 3])
                            ->avg('estimated_revenue'), 
                        true)
            ),
        ];
    }
}
