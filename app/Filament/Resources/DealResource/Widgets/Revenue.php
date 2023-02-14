<?php

namespace App\Filament\Resources\DealResource\Widgets;

use App\Models\Deal;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class Revenue extends LineChartWidget
{
    protected static ?string $heading = 'Revenue per month';

    protected static ?string $pollingInterval = '30s';

    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
        ],
    ];

    protected function getData(): array
    {
        $data = Trend::model(Deal::class)
            ->between(
                start: now()->subYear(1),
                end: now()
            )
            ->perMonth()
            ->sum('actual_revenue');

        return [
            'datasets' => [
                [
                    'label' => 'Revenue per month',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
}
