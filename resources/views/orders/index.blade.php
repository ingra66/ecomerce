@extends('layouts.app')

@section('title', 'Mis Pedidos - BeltSpot')
@section('description', 'Revisa el historial de tus compras')

@section('content')
<div class="bg-dark min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Mis Pedidos</h1>
                <p class="text-gray-400">Revisa el historial de tus compras</p>
            </div>

            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-secondary rounded-lg border border-gray-700 overflow-hidden">
                            <!-- Header de la Orden -->
                            <div class="p-6 border-b border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-white font-semibold">
                                            Orden #{{ $order->order_number }}
                                        </h3>
                                        <p class="text-gray-400 text-sm">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            @if($order->status == 'paid') bg-green-900 text-green-300
                                            @elseif($order->status == 'pending') bg-yellow-900 text-yellow-300
                                            @elseif($order->status == 'cancelled') bg-red-900 text-red-300
                                            @else bg-gray-900 text-gray-300 @endif">
                                            @switch($order->status)
                                                @case('paid')
                                                    Pagado
                                                    @break
                                                @case('pending')
                                                    Pendiente
                                                    @break
                                                @case('cancelled')
                                                    Cancelado
                                                    @break
                                                @case('processing')
                                                    Procesando
                                                    @break
                                                @default
                                                    {{ ucfirst($order->status) }}
                                            @endswitch
                                        </span>
                                        <p class="text-white font-semibold mt-1">
                                            ${{ number_format($order->total, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Productos de la Orden -->
                            <div class="p-6">
                                <div class="space-y-3">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center space-x-4">
                                            <img src="{{ $item->product->images->first()?->url ?? '/images/placeholder.jpg' }}" 
                                                 alt="{{ $item->product->name }}"
                                                 class="w-12 h-12 object-cover rounded">
                                            <div class="flex-1">
                                                <h4 class="text-white font-medium">
                                                    {{ $item->product->name }}
                                                    @if($item->variant)
                                                        <span class="text-gray-400">- {{ $item->variant->name }}</span>
                                                    @endif
                                                </h4>
                                                <p class="text-gray-400 text-sm">
                                                    Cantidad: {{ $item->quantity }} × ${{ number_format($item->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <span class="text-white font-semibold">
                                                ${{ number_format($item->subtotal, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Información de Envío -->
                                <div class="mt-6 p-4 bg-dark rounded-lg border border-gray-700">
                                    <h4 class="text-white font-semibold mb-2">
                                        <i class="fas fa-shipping-fast text-primary mr-2"></i>
                                        Dirección de Envío
                                    </h4>
                                    <div class="text-gray-300 text-sm">
                                        <p>{{ $order->shipping_address }}</p>
                                        <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                                        <p>Tel: {{ $order->shipping_phone }}</p>
                                    </div>
                                </div>
                                
                                <!-- Acciones -->
                                <div class="mt-6 flex items-center justify-between">
                                    <a href="{{ route('orders.show', $order->id) }}" 
                                       class="bg-primary hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                                        <i class="fas fa-eye mr-2"></i>
                                        Ver Detalles
                                    </a>
                                    
                                    @if($order->status == 'paid')
                                        <span class="text-green-400 text-sm">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Pago confirmado
                                        </span>
                                    @elseif($order->status == 'pending')
                                        <span class="text-yellow-400 text-sm">
                                            <i class="fas fa-clock mr-1"></i>
                                            Esperando pago
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Paginación -->
                @if($orders->hasPages())
                    <div class="mt-8">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <!-- Sin Órdenes -->
                <div class="text-center py-12">
                    <div class="mb-6">
                        <i class="fas fa-shopping-bag text-gray-600 text-6xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-4">No tienes pedidos aún</h2>
                    <p class="text-gray-400 mb-8">Comienza a comprar para ver tu historial de pedidos</p>
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
@endsection 