# Configuración del Ecommerce con Mercado Pago

## Migraciones Creadas

He creado las siguientes migraciones para un ecommerce básico similar a Tienda Nube:

### Tablas Principales:
1. **categories** - Categorías de productos
2. **products** - Productos principales
3. **product_images** - Imágenes de productos
4. **product_variants** - Variantes de productos (colores, talles, etc.)
5. **addresses** - Direcciones de envío y facturación
6. **orders** - Órdenes de compra
7. **order_items** - Items de cada orden
8. **carts** - Carrito de compras
9. **cart_items** - Items del carrito
10. **coupons** - Cupones de descuento
11. **reviews** - Reseñas de productos
12. **wishlists** - Listas de deseos
13. **settings** - Configuraciones del ecommerce

### Características Incluidas:

#### Integración con Mercado Pago:
- Campos para `mercadopago_payment_id` y `mercadopago_preference_id`
- Configuraciones para claves públicas y tokens de acceso
- Soporte para modo sandbox y producción

#### Gestión de Productos:
- Categorías con ordenamiento
- Productos con precios, stock, SKU
- Variantes de productos (colores, talles)
- Múltiples imágenes por producto
- Productos destacados

#### Sistema de Órdenes:
- Estados de orden (pendiente, procesando, enviado, etc.)
- Cálculo de subtotales, impuestos, envío
- Direcciones de envío y facturación
- Historial de pagos

#### Carrito de Compras:
- Soporte para usuarios autenticados y no autenticados
- Gestión de items con variantes
- Integración con sesiones

#### Cupones y Descuentos:
- Cupones de porcentaje y monto fijo
- Límites de uso y fechas de expiración
- Montos mínimos de compra

#### Reseñas y Lista de Deseos:
- Sistema de reseñas con verificación de compra
- Lista de deseos por usuario

## Instrucciones de Instalación

### 1. Ejecutar las Migraciones:
```bash
php artisan migrate
```

### 2. Ejecutar los Seeders:
```bash
php artisan db:seed
```

Esto creará:
- Configuraciones básicas del ecommerce
- 5 categorías de ejemplo
- 7 productos de ejemplo con variantes
- 2 cupones de descuento

### 3. Configurar Mercado Pago:

Editar las configuraciones en la base de datos:
```sql
UPDATE settings SET value = 'TU_CLAVE_PUBLICA' WHERE `key` = 'mercadopago_public_key';
UPDATE settings SET value = 'TU_ACCESS_TOKEN' WHERE `key` = 'mercadopago_access_token';
```

### 4. Configuraciones Importantes:

#### Configuraciones Generales:
- `store_name` - Nombre de la tienda
- `store_email` - Email de contacto
- `currency` - Moneda (ARS por defecto)

#### Configuraciones de Envío:
- `free_shipping_threshold` - Monto mínimo para envío gratis
- `default_shipping_cost` - Costo de envío por defecto

#### Configuraciones de Impuestos:
- `tax_rate` - Porcentaje de IVA

## Próximos Pasos

1. **Crear los Modelos Eloquent** para cada tabla
2. **Implementar los Controladores** para gestionar productos, carrito, órdenes
3. **Crear las Vistas** con Blade o Livewire
4. **Integrar Mercado Pago** con el SDK oficial
5. **Implementar el Sistema de Autenticación** para usuarios
6. **Crear el Panel de Administración** para gestionar productos y órdenes

## Estructura de Base de Datos

### Relaciones Principales:
- `products` → `categories` (muchos a uno)
- `products` → `product_images` (uno a muchos)
- `products` → `product_variants` (uno a muchos)
- `orders` → `users` (muchos a uno)
- `orders` → `order_items` (uno a muchos)
- `carts` → `users` (muchos a uno)
- `carts` → `cart_items` (uno a muchos)

### Campos Importantes:
- **Precios**: Todos en centavos (ej: 450000 = $4.500,00)
- **Estados**: Usando enums para consistencia
- **JSON**: Para datos flexibles como direcciones y variantes
- **Timestamps**: Automáticos en todas las tablas

## Notas de Seguridad

- Las claves de Mercado Pago deben estar en variables de entorno
- Validar todos los inputs de usuario
- Implementar CSRF protection
- Usar HTTPS en producción
- Validar permisos de usuario para operaciones sensibles 