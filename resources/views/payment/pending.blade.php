@extends('layouts.app')

@section('title', 'Pago Pendiente - BeltSpot')
@section('description', 'Tu pago está siendo procesado')

@section('content')
<div class="bg-dark min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mb-4">
                    <i class="fas fa-clock text-yellow-500 text-5xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Pago Pendiente</h1>
                <p class="text-gray-400">Tu pago está siendo procesado</p>
            </div>

            <!-- Contenido Principal -->
            <div class="bg-secondary rounded-lg p-8 border border-gray-700">
                <!-- Información del Pago -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-300">Estado del Pago:</span>
                        <span class="text-yellow-400 font-semibold">Pendiente</span>
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

                <!-- Mensaje de Estado -->
                <div class="p-4 bg-yellow-900 border border-yellow-700 rounded-lg mb-6">
                    <p class="text-yellow-300 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        Tu pago está siendo procesado. Esto puede tomar hasta 24 horas en algunos casos.
                    </p>
                </div>

                <!-- Progreso -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-300 text-sm">Progreso del Pago</span>
                        <span class="text-yellow-400 text-sm">En Proceso</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full animate-pulse" style="width: 60%"></div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="mb-6 p-4 bg-dark rounded-lg border border-gray-700">
                    <h3 class="text-white font-semibold mb-2 text-sm">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        ¿Qué significa "Pendiente"?
                    </h3>
                    <ul class="text-gray-400 text-xs space-y-1">
                        <li>• Tu pago está siendo verificado</li>
                        <li>• Puede tomar hasta 24 horas</li>
                        <li>• Recibirás una notificación cuando se confirme</li>
                        <li>• Tu orden no se procesará hasta la confirmación</li>
                    </ul>
                </div>

                <!-- Botones de Acción -->
                <div class="space-y-3">
                    <a href="{{ route('orders') }}" 
                       class="block w-full bg-primary hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition-colors text-center">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Ver Mis Pedidos
                    </a>
                    
                    <a href="{{ route('home') }}" 
                       class="block w-full bg-dark border border-gray-700 hover:border-primary text-gray-300 hover:text-primary py-3 rounded-lg font-semibold transition-colors text-center">
                        <i class="fas fa-home mr-2"></i>
                        Volver al Inicio
                    </a>
                </div>

                <!-- Notificaciones -->
                <div class="mt-8 p-4 bg-dark rounded-lg border border-gray-700">
                    <h3 class="text-white font-semibold mb-2 text-sm">
                        <i class="fas fa-bell text-primary mr-2"></i>
                        Notificaciones:
                    </h3>
                    <ul class="text-gray-400 text-xs space-y-1">
                        <li>• Recibirás un email cuando se confirme el pago</li>
                        <li>• También puedes verificar el estado en tu cuenta</li>
                        <li>• Si hay problemas, te contactaremos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 