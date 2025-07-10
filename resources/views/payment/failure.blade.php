@extends('layouts.app')

@section('title', 'Pago Fallido - Beltspot')
@section('description', 'Hubo un problema con tu pago')

@section('content')
<div class="bg-black min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mb-4">
                    <i class="fas fa-times-circle text-red-500 text-5xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Pago Fallido</h1>
                <p class="text-gray-400">Hubo un problema procesando tu pago</p>
            </div>

            <!-- Contenido Principal -->
            <div class="bg-neutral-900 rounded-lg p-8 border border-gray-700">
                <!-- Información del Error -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-300">Estado del Pago:</span>
                        <span class="text-red-400 font-semibold">Rechazado</span>
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

                <!-- Mensaje de Error -->
                <div class="p-4 bg-red-900 border border-red-700 rounded-lg mb-6">
                    <p class="text-red-300 text-sm">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Tu pago no pudo ser procesado. Esto puede deberse a fondos insuficientes, tarjeta bloqueada o datos incorrectos.
                    </p>
                </div>

                <!-- Posibles Causas -->
                <div class="mb-6 p-4 bg-black rounded-lg border border-gray-700">
                    <h3 class="text-white font-semibold mb-2 text-sm">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        Posibles causas:
                    </h3>
                    <ul class="text-gray-400 text-xs space-y-1">
                        <li>• Fondos insuficientes en la cuenta</li>
                        <li>• Tarjeta bloqueada o vencida</li>
                        <li>• Datos de pago incorrectos</li>
                        <li>• Problemas temporales del sistema</li>
                    </ul>
                </div>

                <!-- Botones de Acción -->
                <div class="space-y-3">
                    <a href="{{ route('cart') }}" 
                       class="block w-full bg-primary hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition-colors text-center">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Intentar Nuevamente
                    </a>
                    
                    <a href="{{ route('home') }}" 
                       class="block w-full bg-black border border-gray-700 hover:border-primary text-gray-300 hover:text-primary py-3 rounded-lg font-semibold transition-colors text-center">
                        <i class="fas fa-home mr-2"></i>
                        Volver al Inicio
                    </a>
                </div>

                <!-- Ayuda -->
                <div class="mt-8 p-4 bg-black rounded-lg border border-gray-700">
                    <h3 class="text-white font-semibold mb-2 text-sm">
                        <i class="fas fa-question-circle text-primary mr-2"></i>
                        ¿Necesitas ayuda?
                    </h3>
                    <p class="text-gray-400 text-xs mb-2">
                        Si continúas teniendo problemas, contacta con nuestro soporte:
                    </p>
                    <div class="text-xs text-gray-400">
                        <p>📧 soporte@beltspot.com</p>
                        <p>📞 +54 11 1234-5678</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 