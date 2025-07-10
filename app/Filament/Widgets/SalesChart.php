<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Ventas de los Últimos 7 Días';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $dates = collect(range(0, 6))->map(function ($i) {
            return Carbon::now()->subDays(6 - $i)->format('Y-m-d');
        });

        $sales = $dates->map(function ($date) {
            return Order::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total') / 100;
        });

        $orders = $dates->map(function ($date) {
            return Order::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Monto total ($)',
                    'data' => $sales,
                    'type' => 'bar',
                    'backgroundColor' => '#dc2626',
                ],
                [
                    'label' => 'Cantidad de ventas',
                    'data' => $orders,
                    'type' => 'line',
                    'borderColor' => '#2563eb',
                    'backgroundColor' => 'rgba(37,99,235,0.2)',
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $dates->map(function ($date) {
                return Carbon::parse($date)->isoFormat('ddd D');
            }),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => ['display' => true, 'text' => 'Monto ($)'],
                ],
                'y1' => [
                    'beginAtZero' => true,
                    'position' => 'right',
                    'grid' => ['drawOnChartArea' => false],
                    'title' => ['display' => true, 'text' => 'Cantidad'],
                ],
            ],
        ];
    }
} 