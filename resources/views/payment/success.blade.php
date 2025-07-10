@extends('layouts.app')

@section('title', 'Pago Exitoso - Beltspot')
@section('description', 'Tu pago se procesó correctamente')

@section('content')
<div class="bg-black min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-green-500 text-5xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">¡Pago Exitoso!</h1>
                <p class="text-gray-400">Tu orden ha sido procesada correctamente</p>
            </div>

            <!-- Contenido Principal -->
            <div class="bg-neutral-900 rounded-lg p-8 border border-gray-700">
                <!-- Información del Pago -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-300">Estado del Pago:</span>
                        <span class="text-green-400 font-semibold">Aprobado</span>
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-300">Número de Orden:</span>
                        <span class="text-white font-mono">#{{ request()->get('external_reference', 'N/A') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Método de Pago:</span>
                        <span class="text-white">MercadoPago</span>
                    </div>
                </div>

                <!-- Mensaje de Confirmación -->
                <div class="p-4 bg-green-900 border border-green-700 rounded-lg mb-6">
                    <p class="text-green-300 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        Recibirás un email de confirmación con los detalles de tu pedido.
                    </p>
                </div>

                <!-- Botones de Acción -->
                <div class="space-y-3">
                    <a href="{{ route('orders') }}" 
                       class="block w-full bg-primary hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition-colors text-center">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Ver Mis Pedidos
                    </a>
                    
                    <a href="{{ route('home') }}" 
                       class="block w-full bg-black border border-gray-700 hover:border-primary text-gray-300 hover:text-primary py-3 rounded-lg font-semibold transition-colors text-center">
                        <i class="fas fa-home mr-2"></i>
                        Volver al Inicio
                    </a>
                </div>

                <!-- Información Adicional -->
                <div class="mt-8 p-4 bg-black rounded-lg border border-gray-700">
                    <h3 class="text-white font-semibold mb-2 text-sm">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        Próximos pasos:
                    </h3>
                    <ul class="text-gray-400 text-xs space-y-1">
                        <li>• Recibirás un email de confirmación</li>
                        <li>• Tu pedido será procesado en 24-48 horas</li>
                        <li>• Te notificaremos cuando se envíe</li>
                        <li>• Puedes rastrear tu pedido en tu cuenta</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 