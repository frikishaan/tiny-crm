<?php

namespace App\Filament\Resources\DealResource\Pages;

use Filament\Schemas\Components\Tabs\Tab;
use App\Enums\DealStatus;
use App\Filament\Resources\DealResource;
use App\Filament\Resources\DealResource\Widgets\DealStats;
use App\Models\Deal;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDeals extends ListRecords
{
    protected static string $resource = DealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DealStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'open' => Tab::make('Open')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', DealStatus::Open->value))
                ->badge(Deal::query()->where('status', DealStatus::Open->value)->count()),
            'won' => Tab::make('Won')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', DealStatus::Won->value)),
            'lost' => Tab::make('Lost')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', DealStatus::Lost->value)),
        ];
    }
}
