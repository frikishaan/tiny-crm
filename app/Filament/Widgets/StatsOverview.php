<?php

namespace App\Filament\Widgets;

use Akaunting\Money\Money;
use App\Models\Deal;
use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Flowframe\Trend\Trend;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Leads created', 
                Lead::all()->count()
            )
            ->description('This month')
            ->chart([2, 4, 5, 7, 9, 90, 4, 5, 7]),
            Card::make('Deals Won', 
                Deal::where('status', 2)
                    ->count()
            )
            ->description('This month'),
            Card::make('Total revenue', Money::USD(Deal::query()->sum('estimated_revenue'), true)),
        ];
    }
}
