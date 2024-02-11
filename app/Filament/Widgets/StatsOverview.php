<?php

namespace App\Filament\Widgets;

use Akaunting\Money\Money;
use App\Models\Deal;
use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;

class StatsOverview extends BaseWidget
{
    protected static bool $isLazy = false;

    protected function getCards(): array
    {
        return [
            Stat::make('Leads created', 
                Lead::all()->count()
            )
            ->description('This month')
            ->chart([2, 4, 5, 7, 9, 90, 4, 5, 7]),
            Stat::make('Deals Won', 
                Deal::where('status', 2)
                    ->count()
            )
            ->description('This month'),
            Stat::make('Total revenue', Money::USD(Deal::query()->sum('estimated_revenue'), true)),
        ];
    }
}
