<?php

namespace App\Filament\Widgets;

use App\Models\Entry;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\TrendValue;

class EntryMonthChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Chart Incidents of 2024';

    protected static ?string $maxHeight = '300px';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 4;

    protected static string $color = 'danger';

    protected static ?string $pollingInterval = '3s';

    protected function getData(): array
    {
        $data = \Flowframe\Trend\Trend::model(Entry::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )

            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'based on Investigator-On-Duty created',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'colors' => ['#6366f1'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
