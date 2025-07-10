<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Category;
use Filament\Widgets\ChartWidget;

class ProductsWithSupplierChart extends ChartWidget
{
    protected static ?string $heading = 'Productos con Enlace de Proveedor';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $categories = Category::orderBy('name')->get();
        $labels = $categories->pluck('name');
        $data = $categories->map(function ($category) {
            return Product::where('category_id', $category->id)
                ->whereHas('suppliers', function ($q) {
                    $q->whereNotNull('product_supplier.enlace')->where('product_supplier.enlace', '!=', '');
                })
                ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Productos con enlace',
                    'data' => $data,
                    'backgroundColor' => '#0ea5e9',
                ],
            ],
            'labels' => $labels,
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
                    'title' => ['display' => true, 'text' => 'Cantidad'],
                ],
            ],
        ];
    }
} 