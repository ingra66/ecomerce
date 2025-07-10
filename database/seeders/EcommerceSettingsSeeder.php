<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EcommerceSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Configuraciones generales
            [
                'key' => 'store_name',
                'value' => 'Mi Tienda Online',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nombre de la tienda'
            ],
            [
                'key' => 'store_description',
                'value' => 'Tu tienda online de confianza',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Descripción de la tienda'
            ],
            [
                'key' => 'store_email',
                'value' => 'info@mitienda.com',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Email de contacto'
            ],
            [
                'key' => 'store_phone',
                'value' => '+54 11 1234-5678',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Teléfono de contacto'
            ],
            [
                'key' => 'currency',
                'value' => 'ARS',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Moneda principal'
            ],
            [
                'key' => 'currency_symbol',
                'value' => '$',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Símbolo de la moneda'
            ],
            
            // Configuraciones de Mercado Pago
            [
                'key' => 'mercadopago_public_key',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'description' => 'Clave pública de Mercado Pago'
            ],
            [
                'key' => 'mercadopago_access_token',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'description' => 'Token de acceso de Mercado Pago'
            ],
            [
                'key' => 'mercadopago_sandbox',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'payment',
                'description' => 'Modo sandbox de Mercado Pago'
            ],
            
            // Configuraciones de envío
            [
                'key' => 'free_shipping_threshold',
                'value' => '50000',
                'type' => 'number',
                'group' => 'shipping',
                'description' => 'Monto mínimo para envío gratuito'
            ],
            [
                'key' => 'default_shipping_cost',
                'value' => '2000',
                'type' => 'number',
                'group' => 'shipping',
                'description' => 'Costo de envío por defecto'
            ],
            
            // Configuraciones de impuestos
            [
                'key' => 'tax_rate',
                'value' => '21',
                'type' => 'number',
                'group' => 'tax',
                'description' => 'Porcentaje de IVA'
            ],
            
            // Configuraciones de productos
            [
                'key' => 'products_per_page',
                'value' => '12',
                'type' => 'number',
                'group' => 'products',
                'description' => 'Productos por página'
            ],
            [
                'key' => 'low_stock_threshold',
                'value' => '5',
                'type' => 'number',
                'group' => 'products',
                'description' => 'Umbral de stock bajo'
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert($setting);
        }
    }
} 