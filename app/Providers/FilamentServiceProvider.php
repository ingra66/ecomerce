<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Este provider se activará cuando Filament esté instalado
        // Por ahora, solo registramos el provider sin funcionalidad
    }
} 