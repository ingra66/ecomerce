<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas públicas
Route::prefix('v1')->group(function () {
    
    // Autenticación
    Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
    
    // Productos (públicos)
    Route::get('/products', [App\Http\Controllers\Api\ProductController::class, 'index']);
    Route::get('/products/featured', [App\Http\Controllers\Api\ProductController::class, 'featured']);
    Route::get('/products/search', [App\Http\Controllers\Api\ProductController::class, 'search']);
    Route::get('/products/categories', [App\Http\Controllers\Api\ProductController::class, 'categories']);
    Route::get('/products/category/{categoryId}', [App\Http\Controllers\Api\ProductController::class, 'byCategory']);
    Route::get('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'show']);
    
    // Webhooks (públicos)
    Route::post('/webhooks/mercadopago', [App\Http\Controllers\Api\WebhookController::class, 'mercadopago']);
    Route::post('/webhooks/check-payment', [App\Http\Controllers\Api\WebhookController::class, 'checkPaymentStatus']);
    
    // Rutas protegidas
    Route::middleware('auth:sanctum')->group(function () {
        
        // Usuario
        Route::get('/user', [App\Http\Controllers\Api\AuthController::class, 'user']);
        Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
        Route::put('/user/profile', [App\Http\Controllers\Api\AuthController::class, 'updateProfile']);
        Route::put('/user/password', [App\Http\Controllers\Api\AuthController::class, 'changePassword']);
        
        // Carrito
        Route::get('/cart', [App\Http\Controllers\Api\CartController::class, 'index']);
        Route::post('/cart/add', [App\Http\Controllers\Api\CartController::class, 'addItem']);
        Route::put('/cart/items/{itemId}', [App\Http\Controllers\Api\CartController::class, 'updateQuantity']);
        Route::delete('/cart/items/{itemId}', [App\Http\Controllers\Api\CartController::class, 'removeItem']);
        Route::delete('/cart/clear', [App\Http\Controllers\Api\CartController::class, 'clear']);
        
        // Órdenes
        Route::get('/orders', [App\Http\Controllers\Api\OrderController::class, 'index']);
        Route::get('/orders/{id}', [App\Http\Controllers\Api\OrderController::class, 'show']);
        Route::post('/orders/checkout', [App\Http\Controllers\Api\OrderController::class, 'checkout']);
        Route::post('/orders/validate-coupon', [App\Http\Controllers\Api\OrderController::class, 'validateCoupon']);
        Route::get('/orders/{orderId}/payment-status', [App\Http\Controllers\Api\OrderController::class, 'paymentStatus']);
        
        // Lista de deseos
        Route::get('/wishlist', [App\Http\Controllers\Api\WishlistController::class, 'index']);
        Route::post('/wishlist/add', [App\Http\Controllers\Api\WishlistController::class, 'add']);
        Route::delete('/wishlist/remove/{productId}', [App\Http\Controllers\Api\WishlistController::class, 'remove']);
        
        // Reseñas
        Route::get('/reviews/my', [App\Http\Controllers\Api\ReviewController::class, 'myReviews']);
        Route::post('/reviews', [App\Http\Controllers\Api\ReviewController::class, 'store']);
        Route::put('/reviews/{id}', [App\Http\Controllers\Api\ReviewController::class, 'update']);
        Route::delete('/reviews/{id}', [App\Http\Controllers\Api\ReviewController::class, 'destroy']);
    });
});

// Ruta de prueba
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API funcionando correctamente',
        'timestamp' => now()
    ]);
});
