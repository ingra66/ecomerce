@extends('layouts.app')

@section('title', 'Verificar Email - BeltSpot')
@section('description', 'Verifica tu dirección de email')

@section('content')
<div class="bg-dark min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mb-4">
                    <i class="fas fa-envelope text-primary text-5xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Verifica tu Email</h1>
                <p class="text-gray-400">Gracias por registrarte. Antes de comenzar, ¿podrías verificar tu dirección de email?</p>
            </div>

            <!-- Contenido Principal -->
            <div class="bg-secondary rounded-lg p-8 border border-gray-700">
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-900 border border-green-700 rounded-lg">
                        <p class="text-green-300">{{ session('status') }}</p>
                    </div>
                @endif

                <!-- Información -->
                <div class="mb-6">
                    <p class="text-gray-300 mb-4">
                        Te hemos enviado un enlace de verificación a <strong class="text-white">{{ Auth::user()->email }}</strong>
                    </p>
                    <p class="text-gray-400 text-sm">
                        Si no recibiste el email, revisa tu carpeta de spam o solicita un nuevo enlace.
                    </p>
                </div>

                <!-- Formulario de Reenvío -->
                <form method="POST" action="{{ route('verification.send') }}" class="mb-6">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-primary hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Reenviar Email de Verificación
                    </button>
                </form>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="mb-6">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-dark border border-gray-700 hover:border-primary text-gray-300 hover:text-primary py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Cerrar Sesión
                    </button>
                </form>

                <!-- Información Adicional -->
                <div class="p-4 bg-dark rounded-lg border border-gray-700">
                    <h3 class="text-white font-semibold mb-2 text-sm">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        ¿Por qué necesito verificar mi email?
                    </h3>
                    <ul class="text-gray-400 text-xs space-y-1">
                        <li>• Confirma que eres el propietario de la cuenta</li>
                        <li>• Te permite recibir notificaciones importantes</li>
                        <li>• Mejora la seguridad de tu cuenta</li>
                        <li>• Te permite recuperar tu contraseña</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 