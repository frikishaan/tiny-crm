<?php

namespace App\Filament\Resources\LeadResource\Pages;

use Filament\Schemas\Components\Tabs\Tab;
use App\Enums\LeadStatus;
use App\Filament\Resources\LeadResource;
use App\Filament\Resources\LeadResource\Widgets\LeadStats;
use App\Models\Lead;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LeadStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'prospect' => Tab::make('Prospect')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', LeadStatus::Prospect->value))
                ->badge(Lead::query()->where('status', LeadStatus::Prospect->value)->count()),
            'open' => Tab::make('Open')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', LeadStatus::Open->value))
                ->badge(Lead::query()->where('status', LeadStatus::Open->value)->count()),
            'qualified' => Tab::make('Qualified')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', LeadStatus::Qualified->value)),
            'disqualified' => Tab::make('Disqualified')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', LeadStatus::Disqualified->value)),
        ];
    }
}
