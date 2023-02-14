<?php

namespace App\Filament\Resources\DealResource\Widgets;

use Akaunting\Money\Money;
use App\Models\Deal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class DealStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Open deals', Deal::where('status', 1)->count()),
            Card::make('Deals won', Deal::where('status', 2)->count()),
            Card::make('Avg Revenue (per deal)', 
                Money::USD(Deal::all()->avg('actual_revenue'), true)),
            Card::make('Total revenue', 
                Money::USD(Deal::where('status', 2)->sum('actual_revenue'), true)    
            ),
        ];
    }
}
