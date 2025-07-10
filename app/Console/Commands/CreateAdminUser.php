<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-user {--email=admin@ecommerce.com} {--password=admin123} {--name=Administrador}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear un usuario administrador para el panel de Filament';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        // Verificar si el usuario ya existe
        if (User::where('email', $email)->exists()) {
            $this->error("El usuario con email {$email} ya existe!");
            return 1;
        }

        // Crear el usuario administrador
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $this->info("Usuario administrador creado exitosamente!");
        $this->info("Email: {$email}");
        $this->info("Contraseña: {$password}");
        $this->info("Puedes acceder al panel en: http://tu-dominio.com/admin");

        return 0;
    }
} 