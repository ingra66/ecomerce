@extends('layouts.app')

@section('title', 'Mi Carrito - Beltspot')
@section('description', 'Revisa y gestiona los productos en tu carrito')

@section('content')
<div class="bg-black min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Mi Carrito</h1>
                <p class="text-gray-400">Revisa y gestiona los productos en tu carrito</p>
            </div>

            @if($itemCount > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Lista de Productos -->
                    <div class="lg:col-span-2">
                        <div class="bg-neutral-900 rounded-lg border border-gray-700">
                            <div class="p-6 border-b border-gray-700">
                                <h2 class="text-xl font-semibold text-white">Productos ({{ $itemCount }})</h2>
                            </div>
                            
                            <div class="divide-y divide-gray-700">
                                @foreach($cartItems as $item)
                                    <div class="p-6" data-item-id="{{ $item->id }}">
                                        <div class="flex items-center space-x-4">
                                            <!-- Imagen del Producto -->
                                            <div class="flex-shrink-0">
                                                <img src="{{ $item->product->images->first()?->url ?? '/images/placeholder.jpg' }}" 
                                                     alt="{{ $item->product->name }}"
                                                     class="w-20 h-20 object-cover rounded-lg">
                                            </div>
                                            
                                            <!-- Información del Producto -->
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-white font-semibold truncate">
                                                    {{ $item->product->name }}
                                                </h3>
                                                @if($item->variant)
                                                    <p class="text-gray-400 text-sm">
                                                        Variante: {{ $item->variant->name }}
                                                    </p>
                                                @endif
                                                <p class="text-primary font-semibold">
                                                    ${{ number_format($item->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            
                                            <!-- Cantidad -->
                                            <div class="flex items-center space-x-2">
                                                <button onclick="updateQuantity('{{ $item->id }}', -1)" 
                                                        class="w-8 h-8 bg-black border border-gray-700 rounded-lg text-gray-300 hover:text-primary hover:border-primary transition-colors">
                                                    <i class="fas fa-minus text-xs"></i>
                                                </button>
                                                <span class="text-white font-semibold w-12 text-center" id="quantity-{{ $item->id }}">
                                                    {{ $item->quantity }}
                                                </span>
                                                <button onclick="updateQuantity('{{ $item->id }}', 1)" 
                                                        class="w-8 h-8 bg-black border border-gray-700 rounded-lg text-gray-300 hover:text-primary hover:border-primary transition-colors">
                                                    <i class="fas fa-plus text-xs"></i>
                                                </button>
                                            </div>
                                            
                                            <!-- Subtotal -->
                                            <div class="text-right">
                                                <p class="text-white font-semibold">
                                                    ${{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            
                                            <!-- Botón Eliminar -->
                                            <button onclick="removeItem('{{ $item->id }}')" 
                                                    class="text-red-400 hover:text-red-300 transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Resumen del Pedido -->
                    <div class="lg:col-span-1">
                        <div class="bg-neutral-900 rounded-lg border border-gray-700 p-6 sticky top-4">
                            <h2 class="text-xl font-semibold text-white mb-6">Resumen del Pedido</h2>
                            
                            <!-- Detalles del Precio -->
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-300">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-300">
                                    <span>Envío</span>
                                    <span class="text-green-400">Gratis</span>
                                </div>
                                <div class="border-t border-gray-700 pt-3">
                                    <div class="flex justify-between text-white font-semibold text-lg">
                                        <span>Total</span>
                                        <span>${{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botones de Acción -->
                            <div class="space-y-3">
                                <a href="{{ route('orders.checkout') }}" 
                                   class="w-full bg-primary hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition-colors text-center block">
                                    <i class="fas fa-credit-card mr-2"></i>
                                    Proceder al Pago
                                </a>
                                
                                <button onclick="clearCart()" 
                                        class="w-full bg-black border border-gray-700 hover:border-primary text-gray-300 hover:text-primary py-3 rounded-lg font-semibold transition-colors">
                                    <i class="fas fa-trash mr-2"></i>
                                    Vaciar Carrito
                                </button>
                                
                                <a href="{{ route('products') }}" 
                                   class="w-full bg-black border border-gray-700 hover:border-primary text-gray-300 hover:text-primary py-3 rounded-lg font-semibold transition-colors text-center block">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Seguir Comprando
                                </a>
                            </div>
                            
                            <!-- Información Adicional -->
                            <div class="mt-6 p-4 bg-black rounded-lg border border-gray-700">
                                <h3 class="text-white font-semibold mb-2 text-sm">
                                    <i class="fas fa-shield-alt text-primary mr-2"></i>
                                    Compra Segura
                                </h3>
                                <ul class="text-gray-400 text-xs space-y-1">
                                    <li>• Pago seguro con MercadoPago</li>
                                    <li>• Envío gratuito en pedidos superiores a $10.000</li>
                                    <li>• Devolución gratuita hasta 30 días</li>
                                    <li>• Soporte 24/7 disponible</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Carrito Vacío -->
                <div class="text-center py-12">
                    <div class="mb-6">
                        <i class="fas fa-shopping-cart text-gray-600 text-6xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-4">Tu carrito está vacío</h2>
                    <p class="text-gray-400 mb-8">Agrega algunos productos para comenzar a comprar</p>
                    <a href="{{ route('products') }}" 
                       class="inline-block bg-primary hover:bg-red-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Explorar Productos
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function updateQuantity(itemId, change) {
    const quantityElement = document.getElementById(`quantity-${itemId}`);
    const currentQuantity = parseInt(quantityElement.textContent);
    const newQuantity = currentQuantity + change;
    
    if (newQuantity < 1) return;
    
    fetch(`/cart/${itemId}/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            quantityElement.textContent = newQuantity;
            updateCartCount(data.cart_count);
            location.reload(); // Recargar para actualizar totales
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function removeItem(itemId) {
    if (!confirm('¿Estás seguro de que quieres eliminar este producto?')) return;
    
    fetch(`/cart/${itemId}/remove`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`[data-item-id="${itemId}"]`).remove();
            updateCartCount(data.cart_count);
            if (data.cart_count == 0) {
                location.reload();
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function clearCart() {
    if (!confirm('¿Estás seguro de que quieres vaciar el carrito?')) return;
    
    fetch('/cart/clear', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function updateCartCount(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}
</script>
@endsection 