<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupFilament extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configurar Filament Admin con tema oscuro';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎨 Configurando Filament Admin con tema oscuro...');
        
        // Paso 1: Publicar assets
        $this->info('📦 Publicando assets de Filament...');
        Artisan::call('filament:install', ['--panels' => true]);
        Artisan::call('vendor:publish', ['--tag' => 'filament-config']);
        Artisan::call('vendor:publish', ['--tag' => 'filament-translations']);
        
        // Paso 2: Limpiar cache
        $this->info('🧹 Limpiando cache...');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        
        // Paso 3: Optimizar
        $this->info('⚡ Optimizando aplicación...');
        Artisan::call('optimize');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        
        // Paso 4: Verificar rutas
        $this->info('🔍 Verificando rutas...');
        $routes = Artisan::call('route:list');
        $this->line($routes);
        
        $this->info('✅ Configuración completada!');
        $this->info('');
        $this->info('🌐 URL del panel: http://localhost:8000/admin');
        $this->info('📧 Email: admin@ecommerce.com');
        $this->info('🔑 Password: password');
        $this->info('');
        $this->info('💡 Recuerda ejecutar: npm run build');
        
        return Command::SUCCESS;
    }
} 