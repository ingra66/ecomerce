@extends('layouts.app')

@section('title', 'Recuperar Contraseña - BeltSpot')
@section('description', 'Recupera tu contraseña de BeltSpot')

@section('content')
<div class="bg-dark min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Recuperar Contraseña</h1>
                <p class="text-gray-400">Te enviaremos un enlace para restablecer tu contraseña</p>
            </div>

            <!-- Formulario de Recuperación -->
            <div class="bg-secondary rounded-lg p-8 border border-gray-700">
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-900 border border-green-700 rounded-lg">
                        <p class="text-green-300">{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-gray-300 text-sm font-medium mb-2">
                            Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               required 
                               autofocus
                               class="w-full bg-dark border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary @error('email') border-red-500 @enderror"
                               placeholder="tu@email.com">
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botón de Envío -->
                    <button type="submit" 
                            class="w-full bg-primary hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Enviar Enlace de Recuperación
                    </button>
                </form>

                <!-- Información Adicional -->
                <div class="mt-8 p-4 bg-dark rounded-lg border border-gray-700">
                    <h3 class="text-white font-semibold mb-2">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        ¿Qué pasa después?
                    </h3>
                    <ul class="text-gray-400 text-sm space-y-1">
                        <li>• Recibirás un email con un enlace seguro</li>
                        <li>• El enlace expira en 60 minutos</li>
                        <li>• Revisa tu carpeta de spam si no lo encuentras</li>
                    </ul>
                </div>

                <!-- Enlaces de Ayuda -->
                <div class="text-center mt-8 pt-6 border-t border-gray-700">
                    <p class="text-gray-400 mb-4">
                        ¿Recordaste tu contraseña? 
                        <a href="{{ route('login') }}" class="text-primary hover:text-red-700 font-semibold transition-colors">
                            Inicia sesión aquí
                        </a>
                    </p>
                    <p class="text-gray-400">
                        ¿Necesitas ayuda? 
                        <a href="{{ route('contact') }}" class="text-primary hover:text-red-700 font-semibold transition-colors">
                            Contacta con soporte
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 