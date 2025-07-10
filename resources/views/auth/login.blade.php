@extends('layouts.app')

@section('title', 'Iniciar Sesión - Beltspot')
@section('description', 'Accede a tu cuenta de Beltspot')

@section('content')
<div class="bg-black min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Iniciar Sesión</h1>
                <p class="text-gray-400">Accede a tu cuenta para continuar</p>
            </div>

            <!-- Formulario de Login -->
            <div class="bg-neutral-900 rounded-lg p-8 border border-gray-700">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
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
                               class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary @error('email') border-red-500 @enderror"
                               placeholder="tu@email.com">
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-gray-300 text-sm font-medium mb-2">
                            Contraseña
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary @error('password') border-red-500 @enderror"
                               placeholder="Tu contraseña">
                        @error('password')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recordar Sesión -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="remember" 
                                   class="rounded border-gray-700 text-primary focus:ring-primary bg-black">
                            <span class="ml-2 text-gray-300 text-sm">Recordar sesión</span>
                        </label>
                        <a href="#" class="text-primary hover:text-red-700 text-sm transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <!-- Botón de Login -->
                    <button type="submit" 
                            class="w-full bg-primary hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Iniciar Sesión
                    </button>
                </form>

                <!-- Separador -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-neutral-900 text-gray-400">O continúa con</span>
                    </div>
                </div>

                <!-- Login Social -->
                <div class="space-y-3">
                    <button class="w-full bg-black border border-gray-700 hover:border-primary text-gray-300 hover:text-primary py-3 rounded-lg font-semibold transition-colors">
                        <i class="fab fa-google mr-2"></i>
                        Continuar con Google
                    </button>
                    <button class="w-full bg-black border border-gray-700 hover:border-primary text-gray-300 hover:text-primary py-3 rounded-lg font-semibold transition-colors">
                        <i class="fab fa-facebook mr-2"></i>
                        Continuar con Facebook
                    </button>
                </div>

                <!-- Enlace a Registro -->
                <div class="text-center mt-8 pt-6 border-t border-gray-700">
                    <p class="text-gray-400">
                        ¿No tienes una cuenta? 
                        <a href="{{ route('register') }}" class="text-primary hover:text-red-700 font-semibold transition-colors">
                            Regístrate aquí
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 