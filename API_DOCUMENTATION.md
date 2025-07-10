# API REST Documentation - BeltSpot Ecommerce

## Base URL
```
http://localhost:8000/api/v1
```

## Autenticación
La API utiliza Laravel Sanctum para autenticación. Los tokens se envían en el header:
```
Authorization: Bearer {token}
```

## Endpoints

### 🔐 Autenticación

#### Registrar Usuario
```http
POST /register
```
**Body:**
```json
{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Iniciar Sesión
```http
POST /login
```
**Body:**
```json
{
    "email": "juan@example.com",
    "password": "password123"
}
```

#### Cerrar Sesión
```http
POST /logout
```
**Headers:** `Authorization: Bearer {token}`

#### Obtener Usuario Actual
```http
GET /user
```
**Headers:** `Authorization: Bearer {token}`

#### Actualizar Perfil
```http
PUT /user/profile
```
**Headers:** `Authorization: Bearer {token}`
**Body:**
```json
{
    "name": "Juan Pérez Actualizado",
    "email": "juan.nuevo@example.com"
}
```

#### Cambiar Contraseña
```http
PUT /user/password
```
**Headers:** `Authorization: Bearer {token}`
**Body:**
```json
{
    "current_password": "password123",
    "password": "nuevaPassword123",
    "password_confirmation": "nuevaPassword123"
}
```

### 🛍️ Productos (Públicos)

#### Listar Productos
```http
GET /products
```
**Query Parameters:**
- `category_id` - Filtrar por categoría
- `search` - Buscar por nombre, descripción o SKU
- `featured` - Solo productos destacados
- `in_stock` - Solo productos en stock
- `sort_by` - Campo para ordenar (default: sort_order)
- `sort_order` - asc o desc (default: asc)
- `per_page` - Productos por página (default: 12)

#### Productos Destacados
```http
GET /products/featured
```

#### Buscar Productos
```http
GET /products/search?q=zapatillas
```

#### Obtener Categorías
```http
GET /products/categories
```

#### Productos por Categoría
```http
GET /products/category/{categoryId}
```

#### Detalle de Producto
```http
GET /products/{id}
```

### 🛒 Carrito (Protegido)

#### Obtener Carrito
```http
GET /cart
```
**Headers:** `Authorization: Bearer {token}`

#### Agregar Producto al Carrito
```http
POST /cart/add
```
**Headers:** `Authorization: Bearer {token}`
**Body:**
```json
{
    "product_id": 1,
    "quantity": 2,
    "variants": {
        "color": "rojo",
        "talla": "M"
    }
}
```

#### Actualizar Cantidad
```http
PUT /cart/items/{itemId}
```
**Headers:** `Authorization: Bearer {token}`
**Body:**
```json
{
    "quantity": 3
}
```

#### Remover Item
```http
DELETE /cart/items/{itemId}
```
**Headers:** `Authorization: Bearer {token}`

#### Limpiar Carrito
```http
DELETE /cart/clear
```
**Headers:** `Authorization: Bearer {token}`

### 📦 Órdenes (Protegido)

#### Listar Órdenes
```http
GET /orders
```
**Headers:** `Authorization: Bearer {token}`

#### Detalle de Orden
```http
GET /orders/{id}
```
**Headers:** `Authorization: Bearer {token}`

#### Crear Orden (Checkout)
```http
POST /orders/checkout
```
**Headers:** `Authorization: Bearer {token}`
**Body:**
```json
{
    "shipping_address": {
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "phone": "+54 11 1234-5678",
        "address": "Av. Corrientes 1234",
        "city": "Buenos Aires",
        "state": "Buenos Aires",
        "postal_code": "1043"
    },
    "billing_address": {
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "phone": "+54 11 1234-5678",
        "address": "Av. Corrientes 1234",
        "city": "Buenos Aires",
        "state": "Buenos Aires",
        "postal_code": "1043"
    },
    "coupon_code": "DESCUENTO10",
    "notes": "Entregar por la tarde"
}
```

#### Validar Cupón
```http
POST /orders/validate-coupon
```
**Headers:** `Authorization: Bearer {token}`
**Body:**
```json
{
    "coupon_code": "DESCUENTO10"
}
```

#### Estado de Pago
```http
GET /orders/{orderId}/payment-status
```
**Headers:** `Authorization: Bearer {token}`

### ❤️ Lista de Deseos (Protegido)

#### Obtener Lista de Deseos
```http
GET /wishlist
```
**Headers:** `Authorization: Bearer {token}`

#### Agregar a Lista de Deseos
```http
POST /wishlist/add
```
**Headers:** `Authorization: Bearer {token}`
**Body:**
```json
{
    "product_id": 1
}
```

#### Remover de Lista de Deseos
```http
DELETE /wishlist/remove/{productId}
```
**Headers:** `Authorization: Bearer {token}`

### ⭐ Reseñas (Protegido)

#### Mis Reseñas
```http
GET /reviews/my
```
**Headers:** `Authorization: Bearer {token}`

#### Crear Reseña
```http
POST /reviews
```
**Headers:** `Authorization: Bearer {token}`
**Body:**
```json
{
    "product_id": 1,
    "rating": 5,
    "title": "Excelente producto",
    "comment": "Muy buena calidad, lo recomiendo",
    "order_id": 123
}
```

#### Actualizar Reseña
```http
PUT /reviews/{id}
```
**Headers:** `Authorization: Bearer {token}`
**Body:**
```json
{
    "rating": 4,
    "title": "Buen producto",
    "comment": "Actualizado: muy buena calidad"
}
```

#### Eliminar Reseña
```http
DELETE /reviews/{id}
```
**Headers:** `Authorization: Bearer {token}`

### 🔗 Webhooks (Públicos)

#### Webhook MercadoPago
```http
POST /webhooks/mercadopago
```
**Body:** (Enviado por MercadoPago)

#### Verificar Estado de Pago
```http
POST /webhooks/check-payment
```
**Body:**
```json
{
    "payment_id": "123456789"
}
```

## Respuestas

### Formato de Respuesta Exitosa
```json
{
    "success": true,
    "message": "Operación exitosa",
    "data": {
        // Datos de la respuesta
    }
}
```

### Formato de Respuesta con Paginación
```json
{
    "success": true,
    "data": [
        // Array de elementos
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 12,
        "total": 60
    }
}
```

### Formato de Error
```json
{
    "success": false,
    "message": "Descripción del error"
}
```

## Códigos de Estado HTTP

- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Configuración de MercadoPago

### Variables de Entorno
```env
MERCADOPAGO_PUBLIC_KEY=TEST-9d789008-52a6-4517-8271-7c735b3806c6
MERCADOPAGO_ACCESS_TOKEN=TEST-1209269489778646-010517-6ba0c3913960821f7b3b4f4b94973f92-1188129936
MERCADOPAGO_SANDBOX=true
MERCADOPAGO_NOTIFICATION_URL=https://tu-dominio.com/api/v1/webhooks/mercadopago
MERCADOPAGO_SUCCESS_URL=https://tu-dominio.com/payment/success
MERCADOPAGO_FAILURE_URL=https://tu-dominio.com/payment/failure
MERCADOPAGO_PENDING_URL=https://tu-dominio.com/payment/pending
```

### Integración Frontend

#### 1. Obtener Preferencia de Pago
```javascript
const response = await fetch('/api/v1/orders/checkout', {
    method: 'POST',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(checkoutData)
});

const { data } = await response.json();
const { preference_id, init_point, public_key } = data.payment;
```

#### 2. Inicializar MercadoPago
```javascript
const mp = new MercadoPago(public_key);
const checkout = mp.checkout({
    preference: {
        id: preference_id
    },
    render: {
        container: '.cho-container',
        label: 'Pagar'
    }
});
```

## Ejemplos de Uso

### Frontend con JavaScript

#### Autenticación
```javascript
// Login
const loginResponse = await fetch('/api/v1/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        email: 'usuario@example.com',
        password: 'password123'
    })
});

const { data } = await loginResponse.json();
const token = data.token;

// Usar token en requests
const headers = {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
};
```

#### Obtener Productos
```javascript
const productsResponse = await fetch('/api/v1/products?featured=1&per_page=8');
const { data: products } = await productsResponse.json();
```

#### Agregar al Carrito
```javascript
const cartResponse = await fetch('/api/v1/cart/add', {
    method: 'POST',
    headers,
    body: JSON.stringify({
        product_id: 1,
        quantity: 2,
        variants: { color: 'rojo', talla: 'M' }
    })
});
```

#### Checkout
```javascript
const checkoutResponse = await fetch('/api/v1/orders/checkout', {
    method: 'POST',
    headers,
    body: JSON.stringify({
        shipping_address: {
            name: 'Juan Pérez',
            email: 'juan@example.com',
            phone: '+54 11 1234-5678',
            address: 'Av. Corrientes 1234',
            city: 'Buenos Aires',
            state: 'Buenos Aires',
            postal_code: '1043'
        },
        coupon_code: 'DESCUENTO10'
    })
});

const { data } = await checkoutResponse.json();
// Usar data.payment para integrar con MercadoPago
``` 