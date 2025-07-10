<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear categorías de ejemplo
        $categories = [
            [
                'name' => 'Electrónicos',
                'slug' => 'electronicos',
                'description' => 'Productos electrónicos y tecnología',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Ropa',
                'slug' => 'ropa',
                'description' => 'Ropa para hombres, mujeres y niños',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Hogar',
                'slug' => 'hogar',
                'description' => 'Productos para el hogar y decoración',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Deportes',
                'slug' => 'deportes',
                'description' => 'Artículos deportivos y fitness',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Libros',
                'slug' => 'libros',
                'description' => 'Libros y material educativo',
                'is_active' => true,
                'sort_order' => 5
            ]
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert($category);
        }

        // Crear productos de ejemplo
        $products = [
            [
                'category_id' => 1, // Electrónicos
                'name' => 'Smartphone Samsung Galaxy A54',
                'slug' => 'smartphone-samsung-galaxy-a54',
                'description' => 'Smartphone Samsung Galaxy A54 con 128GB de almacenamiento, pantalla de 6.4 pulgadas y cámara de 50MP.',
                'short_description' => 'Smartphone Samsung Galaxy A54 128GB',
                'price' => 450000,
                'compare_price' => 500000,
                'stock' => 25,
                'sku' => 'SAMS-A54-128',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'category_id' => 1, // Electrónicos
                'name' => 'Laptop HP Pavilion 15',
                'slug' => 'laptop-hp-pavilion-15',
                'description' => 'Laptop HP Pavilion 15 con procesador Intel Core i5, 8GB RAM, 512GB SSD y Windows 11.',
                'short_description' => 'Laptop HP Pavilion 15 Intel i5',
                'price' => 850000,
                'compare_price' => 950000,
                'stock' => 10,
                'sku' => 'HP-PAV-15-I5',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2
            ],
            [
                'category_id' => 2, // Ropa
                'name' => 'Camiseta Básica Algodón',
                'slug' => 'camiseta-basica-algodon',
                'description' => 'Camiseta básica de algodón 100%, disponible en varios colores y talles.',
                'short_description' => 'Camiseta básica de algodón',
                'price' => 2500,
                'compare_price' => 3500,
                'stock' => 100,
                'sku' => 'CAM-BAS-001',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1
            ],
            [
                'category_id' => 2, // Ropa
                'name' => 'Jeans Clásicos',
                'slug' => 'jeans-clasicos',
                'description' => 'Jeans clásicos de alta calidad, cómodos y duraderos.',
                'short_description' => 'Jeans clásicos de alta calidad',
                'price' => 8500,
                'compare_price' => 12000,
                'stock' => 50,
                'sku' => 'JEA-CLA-001',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 2
            ],
            [
                'category_id' => 3, // Hogar
                'name' => 'Set de Sábanas 100% Algodón',
                'slug' => 'set-sabanas-algodon',
                'description' => 'Set de sábanas 100% algodón egipcio, suaves y transpirables.',
                'short_description' => 'Set de sábanas 100% algodón',
                'price' => 15000,
                'compare_price' => 20000,
                'stock' => 30,
                'sku' => 'SAB-ALG-001',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1
            ],
            [
                'category_id' => 4, // Deportes
                'name' => 'Pelota de Fútbol Profesional',
                'slug' => 'pelota-futbol-profesional',
                'description' => 'Pelota de fútbol profesional, tamaño 5, ideal para entrenamiento y partidos.',
                'short_description' => 'Pelota de fútbol profesional',
                'price' => 12000,
                'compare_price' => 15000,
                'stock' => 40,
                'sku' => 'PEL-FUT-001',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1
            ],
            [
                'category_id' => 5, // Libros
                'name' => 'El Señor de los Anillos - Trilogía',
                'slug' => 'senor-anillos-trilogia',
                'description' => 'Trilogía completa de El Señor de los Anillos de J.R.R. Tolkien en tapa dura.',
                'short_description' => 'Trilogía El Señor de los Anillos',
                'price' => 25000,
                'compare_price' => 30000,
                'stock' => 15,
                'sku' => 'LIB-TOL-001',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1
            ]
        ];

        foreach ($products as $product) {
            DB::table('products')->insert($product);
        }

        // Crear variantes de productos de ejemplo
        $variants = [
            // Variantes para camiseta
            [
                'product_id' => 3,
                'name' => 'Color',
                'value' => 'Blanco',
                'price_adjustment' => 0,
                'stock' => 25,
                'is_active' => true
            ],
            [
                'product_id' => 3,
                'name' => 'Color',
                'value' => 'Negro',
                'price_adjustment' => 0,
                'stock' => 25,
                'is_active' => true
            ],
            [
                'product_id' => 3,
                'name' => 'Talle',
                'value' => 'S',
                'price_adjustment' => 0,
                'stock' => 20,
                'is_active' => true
            ],
            [
                'product_id' => 3,
                'name' => 'Talle',
                'value' => 'M',
                'price_adjustment' => 0,
                'stock' => 30,
                'is_active' => true
            ],
            [
                'product_id' => 3,
                'name' => 'Talle',
                'value' => 'L',
                'price_adjustment' => 0,
                'stock' => 25,
                'is_active' => true
            ],
            [
                'product_id' => 3,
                'name' => 'Talle',
                'value' => 'XL',
                'price_adjustment' => 500,
                'stock' => 15,
                'is_active' => true
            ],
            // Variantes para jeans
            [
                'product_id' => 4,
                'name' => 'Color',
                'value' => 'Azul Clásico',
                'price_adjustment' => 0,
                'stock' => 20,
                'is_active' => true
            ],
            [
                'product_id' => 4,
                'name' => 'Color',
                'value' => 'Negro',
                'price_adjustment' => 1000,
                'stock' => 15,
                'is_active' => true
            ],
            [
                'product_id' => 4,
                'name' => 'Talle',
                'value' => '30',
                'price_adjustment' => 0,
                'stock' => 10,
                'is_active' => true
            ],
            [
                'product_id' => 4,
                'name' => 'Talle',
                'value' => '32',
                'price_adjustment' => 0,
                'stock' => 15,
                'is_active' => true
            ],
            [
                'product_id' => 4,
                'name' => 'Talle',
                'value' => '34',
                'price_adjustment' => 0,
                'stock' => 15,
                'is_active' => true
            ]
        ];

        foreach ($variants as $variant) {
            DB::table('product_variants')->insert($variant);
        }

        // Crear cupones de ejemplo
        $coupons = [
            [
                'code' => 'BIENVENIDA',
                'name' => 'Cupón de Bienvenida',
                'description' => '10% de descuento en tu primera compra',
                'type' => 'percentage',
                'value' => 10,
                'minimum_amount' => 10000,
                'max_uses' => 100,
                'used_count' => 0,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
                'is_active' => true
            ],
            [
                'code' => 'ENVIOGRATIS',
                'name' => 'Envío Gratis',
                'description' => 'Envío gratis en compras superiores a $50.000',
                'type' => 'fixed',
                'value' => 2000,
                'minimum_amount' => 50000,
                'max_uses' => 50,
                'used_count' => 0,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(6),
                'is_active' => true
            ]
        ];

        foreach ($coupons as $coupon) {
            DB::table('coupons')->insert($coupon);
        }

        // Crear proveedores de ejemplo si no existen
        if (DB::table('suppliers')->count() == 0) {
            \App\Models\Supplier::factory()->count(10)->create();
        }
        $supplierIds = DB::table('suppliers')->pluck('id')->toArray();

        // Relacionar productos con proveedores y poblar 'enlace'
        $productIds = DB::table('products')->pluck('id')->toArray();
        foreach ($productIds as $productId) {
            $asignados = collect($supplierIds)->random(rand(1, 2));
            foreach ($asignados as $supplierId) {
                DB::table('product_supplier')->insert([
                    'product_id' => $productId,
                    'supplier_id' => $supplierId,
                    'enlace' => fake()->optional()->url(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 