<?php

namespace App\Filament\Resources\LeadResource\Widgets;

use Akaunting\Money\Money;
use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeadStats extends BaseWidget
{
    protected static bool $isLazy = false;
    
    protected function getCards(): array
    {
        return [
            Stat::make('Open Leads', Lead::where('status', 1)->count()),
            Stat::make('Qualified leads', Lead::where('status', 2)->count()),
            Stat::make('Disqualified leads', Lead::where('status', 3)->count()),
            Stat::make('Avg Estimated Revenue', 
                    Money::USD(
                        Lead::whereIn('status', [2, 3])
                            ->avg('estimated_revenue') ?? 0, 
                        true)
            ),
        ];
    }
}
