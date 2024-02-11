<?php

namespace App\Filament\Resources\DealResource\Widgets;

use Akaunting\Money\Money;
use App\Models\Deal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DealStats extends BaseWidget
{
    protected static bool $isLazy = false;

    protected function getCards(): array
    {
        return [
            Stat::make('Open deals', Deal::where('status', 1)->count()),
            Stat::make('Deals won', Deal::where('status', 2)->count()),
            Stat::make('Avg Revenue (per deal)', 
                Money::USD(Deal::all()->avg('actual_revenue') ?? 0, true)),
            Stat::make('Total revenue', 
                Money::USD(Deal::where('status', 2)->sum('actual_revenue'), true)    
            ),
        ];
    }
}
