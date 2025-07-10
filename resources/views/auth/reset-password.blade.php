@extends('layouts.app')

@section('title', 'Restablecer Contraseña - BeltSpot')
@section('description', 'Establece tu nueva contraseña')

@section('content')
<div class="bg-dark min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Nueva Contraseña</h1>
                <p class="text-gray-400">Establece tu nueva contraseña segura</p>
            </div>

            <!-- Formulario de Restablecimiento -->
            <div class="bg-secondary rounded-lg p-8 border border-gray-700">
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf

                    <!-- Token de Restablecimiento -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-gray-300 text-sm font-medium mb-2">
                            Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $request->email) }}"
                               required 
                               autofocus
                               class="w-full bg-dark border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary @error('email') border-red-500 @enderror"
                               placeholder="tu@email.com">
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nueva Contraseña -->
                    <div>
                        <label for="password" class="block text-gray-300 text-sm font-medium mb-2">
                            Nueva Contraseña
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               class="w-full bg-dark border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary @error('password') border-red-500 @enderror"
                               placeholder="Mínimo 8 caracteres">
                        @error('password')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Nueva Contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-gray-300 text-sm font-medium mb-2">
                            Confirmar Nueva Contraseña
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required
                               class="w-full bg-dark border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary"
                               placeholder="Repite tu nueva contraseña">
                    </div>

                    <!-- Requisitos de Contraseña -->
                    <div class="p-4 bg-dark rounded-lg border border-gray-700">
                        <h3 class="text-white font-semibold mb-2 text-sm">
                            <i class="fas fa-shield-alt text-primary mr-2"></i>
                            Requisitos de seguridad:
                        </h3>
                        <ul class="text-gray-400 text-xs space-y-1">
                            <li>• Mínimo 8 caracteres</li>
                            <li>• Al menos una letra mayúscula</li>
                            <li>• Al menos una letra minúscula</li>
                            <li>• Al menos un número</li>
                            <li>• Al menos un carácter especial</li>
                        </ul>
                    </div>

                    <!-- Botón de Restablecimiento -->
                    <button type="submit" 
                            class="w-full bg-primary hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-key mr-2"></i>
                        Restablecer Contraseña
                    </button>
                </form>

                <!-- Enlaces de Ayuda -->
                <div class="text-center mt-8 pt-6 border-t border-gray-700">
                    <p class="text-gray-400">
                        ¿Recordaste tu contraseña? 
                        <a href="{{ route('login') }}" class="text-primary hover:text-red-700 font-semibold transition-colors">
                            Inicia sesión aquí
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 