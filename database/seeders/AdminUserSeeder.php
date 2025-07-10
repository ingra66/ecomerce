<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@ecommerce.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'is_active' => true,
            'role' => 'administrador',
        ]);

        // Crear usuario administrador alternativo
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@ecommerce.com',
            'password' => Hash::make('superadmin123'),
            'email_verified_at' => now(),
            'is_active' => true,
            'role' => 'administrador',
        ]);

        // Crear usuario vendedor de ejemplo
        User::create([
            'name' => 'Vendedor Demo',
            'email' => 'vendedor@ecommerce.com',
            'password' => Hash::make('vendedor123'),
            'email_verified_at' => now(),
            'is_active' => true,
            'role' => 'vendedor',
        ]);

        $this->command->info('Usuarios administradores creados exitosamente!');
        $this->command->info('Credenciales:');
        $this->command->info('- admin@ecommerce.com / admin123');
        $this->command->info('- superadmin@ecommerce.com / superadmin123');
        $this->command->info('- vendedor@ecommerce.com / vendedor123 (vendedor)');
    }
} 