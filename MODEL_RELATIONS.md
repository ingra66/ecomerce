# Relaciones entre Modelos del Ecommerce

## Resumen de Relaciones

### User (Usuario)
- **hasMany** → `Cart` (carritos del usuario)
- **hasMany** → `Order` (órdenes del usuario)
- **hasMany** → `Review` (reseñas del usuario)
- **hasMany** → `Wishlist` (lista de deseos del usuario)

### Cart (Carrito)
- **belongsTo** → `User` (usuario propietario)
- **hasMany** → `CartItem` (items del carrito)

### CartItem (Item del Carrito)
- **belongsTo** → `Cart` (carrito al que pertenece)
- **belongsTo** → `Product` (producto del item)

### Category (Categoría)
- **hasMany** → `Product` (productos de la categoría)

### Product (Producto)
- **belongsTo** → `Category` (categoría del producto)
- **hasMany** → `ProductImage` (imágenes del producto)
- **hasMany** → `ProductVariant` (variantes del producto)
- **hasMany** → `Review` (reseñas del producto)
- **hasMany** → `Wishlist` (usuarios que lo tienen en lista de deseos)
- **hasOne** → `ProductImage` (imagen principal)

### ProductImage (Imagen de Producto)
- **belongsTo** → `Product` (producto al que pertenece)

### ProductVariant (Variante de Producto)
- **belongsTo** → `Product` (producto al que pertenece)

### Order (Orden)
- **belongsTo** → `User` (usuario que realizó la orden)
- **hasMany** → `OrderItem` (items de la orden)
- **hasMany** → `Review` (reseñas de la orden)

### OrderItem (Item de Orden)
- **belongsTo** → `Order` (orden a la que pertenece)
- **belongsTo** → `Product` (producto del item)

### Review (Reseña)
- **belongsTo** → `User` (usuario que escribió la reseña)
- **belongsTo** → `Product` (producto reseñado)
- **belongsTo** → `Order` (orden relacionada, opcional)

### Wishlist (Lista de Deseos)
- **belongsTo** → `User` (usuario propietario)
- **belongsTo** → `Product` (producto en la lista)

### Coupon (Cupón)
- Modelo independiente, no tiene relaciones directas
- Se usa a través de métodos en otros modelos (Cart, Order)

## Métodos Útiles por Modelo

### User
- `getActiveCartAttribute()` - Obtener carrito activo
- `getRecentOrdersAttribute()` - Obtener órdenes recientes

### Cart
- `getOrCreateForUser()` - Obtener o crear carrito para usuario
- `getCurrent()` - Obtener carrito actual
- `addItem()` - Agregar producto al carrito
- `removeItem()` - Remover item del carrito
- `updateItemQuantity()` - Actualizar cantidad
- `clear()` - Limpiar carrito
- `convertToOrder()` - Convertir carrito a orden

### Product
- `getPrimaryImageUrlAttribute()` - URL de imagen principal
- `getAllImagesUrlsAttribute()` - Todas las URLs de imágenes
- `getVariantsGroupedAttribute()` - Variantes agrupadas
- `getAverageRatingAttribute()` - Rating promedio
- `getReviewsCountAttribute()` - Cantidad de reseñas
- `isInWishlist()` - Verificar si está en lista de deseos
- `reduceStock()` - Reducir stock
- `increaseStock()` - Aumentar stock

### Order
- `generateOrderNumber()` - Generar número de orden único
- `calculateTotals()` - Calcular totales
- `markAsPaid()` - Marcar como pagada
- `markAsShipped()` - Marcar como enviada
- `markAsDelivered()` - Marcar como entregada
- `cancel()` - Cancelar orden

### Coupon
- `findByCode()` - Buscar cupón por código
- `isValid()` - Verificar si es válido
- `isValidForAmount()` - Verificar si es válido para monto
- `calculateDiscount()` - Calcular descuento
- `incrementUsage()` - Incrementar uso

### Wishlist
- `isInWishlist()` - Verificar si producto está en lista
- `addToWishlist()` - Agregar a lista de deseos
- `removeFromWishlist()` - Remover de lista de deseos

## Scopes Útiles

### Product
- `scopeActive()` - Productos activos
- `scopeFeatured()` - Productos destacados
- `scopeInStock()` - Productos en stock
- `scopeOrdered()` - Ordenar por sort_order

### Category
- `scopeActive()` - Categorías activas
- `scopeOrdered()` - Ordenar por sort_order

### Order
- `scopePending()` - Órdenes pendientes
- `scopeProcessing()` - Órdenes procesando
- `scopeShipped()` - Órdenes enviadas
- `scopeDelivered()` - Órdenes entregadas
- `scopeCancelled()` - Órdenes canceladas
- `scopePaymentPending()` - Pagos pendientes
- `scopePaymentPaid()` - Pagos completados

### Coupon
- `scopeActive()` - Cupones activos
- `scopeValidByDate()` - Cupones válidos por fecha
- `scopeWithUsesAvailable()` - Cupones con usos disponibles

### Review
- `scopeApproved()` - Reseñas aprobadas
- `scopeVerified()` - Reseñas verificadas

### ProductImage
- `scopePrimary()` - Imágenes principales
- `scopeOrdered()` - Ordenar por sort_order

### ProductVariant
- `scopeActive()` - Variantes activas 