<?php

namespace App\Filament\Resources\DealResource\Widgets;

use App\Models\Deal;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DealsWon extends ChartWidget
{
    protected static bool $isLazy = false;
    
    protected ?string $heading = 'Deals won per month';

    protected ?string $pollingInterval = '30s';

    protected ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
        ],
    ];

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $data = Trend::query(
                Deal::where('status', 2)
            )
            ->between(
                start: now()->subYear(1),
                end: now()
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Deals per month',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
}
