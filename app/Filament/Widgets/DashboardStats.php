<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $lowStockProducts = Product::where('stock', '<', 10)->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        return [
            Stat::make('Ingresos Totales', '$' . number_format($totalRevenue / 100, 2))
                ->description('Total de ventas pagadas')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Órdenes Totales', $totalOrders)
                ->description('Todas las órdenes del sistema')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),

            Stat::make('Productos', $totalProducts)
                ->description('Productos en el catálogo')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),

            Stat::make('Usuarios Registrados', $totalUsers)
                ->description('Clientes registrados')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),

            Stat::make('Productos con Stock Bajo', $lowStockProducts)
                ->description('Necesitan reposición')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Órdenes Pendientes', $pendingOrders)
                ->description('Requieren atención')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
} 