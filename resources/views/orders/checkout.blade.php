@extends('layouts.app')

@section('title', 'Checkout - Beltspot')
@section('description', 'Completa tu compra de forma segura')

@section('content')
<div class="bg-black min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Checkout</h1>
                <p class="text-gray-400">Completa tu compra de forma segura</p>
            </div>

            <form id="checkout-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                
                <!-- Información de Envío -->
                <div class="lg:col-span-2">
                    <div class="bg-neutral-900 rounded-lg border border-gray-700 p-6 mb-6">
                        <h2 class="text-xl font-semibold text-white mb-6">
                            <i class="fas fa-shipping-fast text-primary mr-2"></i>
                            Información de Envío
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="shipping_address" class="block text-gray-300 text-sm font-medium mb-2">
                                    Dirección
                                </label>
                                <input type="text" 
                                       id="shipping_address" 
                                       name="shipping_address" 
                                       required
                                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary"
                                       placeholder="Calle y número">
                            </div>
                            
                            <div>
                                <label for="shipping_city" class="block text-gray-300 text-sm font-medium mb-2">
                                    Ciudad
                                </label>
                                <input type="text" 
                                       id="shipping_city" 
                                       name="shipping_city" 
                                       required
                                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary"
                                       placeholder="Ciudad">
                            </div>
                            
                            <div>
                                <label for="shipping_state" class="block text-gray-300 text-sm font-medium mb-2">
                                    Provincia
                                </label>
                                <input type="text" 
                                       id="shipping_state" 
                                       name="shipping_state" 
                                       required
                                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary"
                                       placeholder="Provincia">
                            </div>
                            
                            <div>
                                <label for="shipping_zip" class="block text-gray-300 text-sm font-medium mb-2">
                                    Código Postal
                                </label>
                                <input type="text" 
                                       id="shipping_zip" 
                                       name="shipping_zip" 
                                       required
                                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary"
                                       placeholder="Código postal">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="shipping_phone" class="block text-gray-300 text-sm font-medium mb-2">
                                    Teléfono
                                </label>
                                <input type="tel" 
                                       id="shipping_phone" 
                                       name="shipping_phone" 
                                       required
                                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary"
                                       placeholder="+54 11 1234-5678">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Método de Pago -->
                    <div class="bg-neutral-900 rounded-lg border border-gray-700 p-6">
                        <h2 class="text-xl font-semibold text-white mb-6">
                            <i class="fas fa-credit-card text-primary mr-2"></i>
                            Método de Pago
                        </h2>
                        
                        <div class="space-y-4">
                            <label class="flex items-center p-4 bg-black border border-gray-700 rounded-lg cursor-pointer hover:border-primary transition-colors">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="mercadopago" 
                                       checked
                                       class="text-primary focus:ring-primary bg-black border-gray-700">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <img src="https://http2.mlstatic.com/frontend-assets/mp-brand/rebrand/MP_white_logo.png" 
                                             alt="MercadoPago" 
                                             class="h-6 mr-2">
                                        <span class="text-white font-semibold">MercadoPago</span>
                                    </div>
                                    <p class="text-gray-400 text-sm">Paga con tarjeta, efectivo o transferencia</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Resumen del Pedido -->
                <div class="lg:col-span-1">
                    <div class="bg-neutral-900 rounded-lg border border-gray-700 p-6 sticky top-4">
                        <h2 class="text-xl font-semibold text-white mb-6">Resumen del Pedido</h2>
                        
                        <!-- Productos -->
                        <div class="space-y-3 mb-6">
                            @foreach($cartItems as $item)
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $item->product->images->first()?->url ?? '/images/placeholder.jpg' }}" 
                                         alt="{{ $item->product->name }}"
                                         class="w-12 h-12 object-cover rounded">
                                    <div class="flex-1">
                                        <h4 class="text-white text-sm font-medium">
                                            {{ $item->product->name }}
                                            @if($item->variant)
                                                <span class="text-gray-400">- {{ $item->variant->name }}</span>
                                            @endif
                                        </h4>
                                        <p class="text-gray-400 text-xs">Cantidad: {{ $item->quantity }}</p>
                                    </div>
                                    <span class="text-white font-semibold text-sm">
                                        ${{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Totales -->
                        <div class="border-t border-gray-700 pt-4 space-y-2">
                            <div class="flex justify-between text-gray-300">
                                <span>Subtotal</span>
                                <span>${{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-300">
                                <span>Envío</span>
                                <span class="text-green-400">Gratis</span>
                            </div>
                            <div class="border-t border-gray-700 pt-2">
                                <div class="flex justify-between text-white font-semibold text-lg">
                                    <span>Total</span>
                                    <span>${{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botón de Pago -->
                        <button type="submit" 
                                id="pay-button"
                                class="w-full bg-primary hover:bg-red-700 text-white py-4 rounded-lg font-semibold transition-colors mt-6">
                            <i class="fas fa-lock mr-2"></i>
                            Pagar ${{ number_format($total, 0, ',', '.') }}
                        </button>
                        
                        <!-- Información de Seguridad -->
                        <div class="mt-6 p-4 bg-black rounded-lg border border-gray-700">
                            <h3 class="text-white font-semibold mb-2 text-sm">
                                <i class="fas fa-shield-alt text-primary mr-2"></i>
                                Compra Segura
                            </h3>
                            <ul class="text-gray-400 text-xs space-y-1">
                                <li>• Pago procesado por MercadoPago</li>
                                <li>• Datos encriptados SSL</li>
                                <li>• Sin almacenamiento de datos bancarios</li>
                                <li>• Garantía de devolución</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SDK de MercadoPago -->
<script src="https://sdk.mercadopago.com/js/v2"></script>

<script>
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const button = document.getElementById('pay-button');
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';
    
    // Obtener datos del formulario
    const formData = new FormData(this);
    
    fetch('/orders', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Inicializar MercadoPago
            const mp = new MercadoPago('{{ config("mercadopago.public_key") }}');
            
            mp.checkout({
                preference: {
                    id: data.preference_id
                },
                render: {
                    container: '.cho-container',
                    label: 'Pagar ahora'
                }
            });
            
            // Redirigir a MercadoPago
            window.location.href = data.init_point;
        } else {
            alert('Error: ' + data.error);
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-lock mr-2"></i>Pagar ${{ number_format($total, 0, ",", ".") }}';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la orden');
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-lock mr-2"></i>Pagar ${{ number_format($total, 0, ",", ".") }}';
    });
});
</script>
@endsection 