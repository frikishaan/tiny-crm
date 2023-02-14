<?php
 
namespace App\Filament\Pages;

use App\Filament\Resources\DealResource\Widgets\DealStats;
use App\Filament\Resources\LeadResource\Widgets\LeadStats;
use Filament\Pages\Dashboard as BasePage;
 
class Dashboard extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected function getWidgets(): array
    {
        return [
            LeadStats::class,
            DealStats::class,
        ];
    }
}