<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardStats;
use App\Filament\Widgets\SalesChart;
use App\Filament\Widgets\ProductsWithSupplierChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            DashboardStats::class,
            SalesChart::class,
            ProductsWithSupplierChart::class,
        ];
    }
} 