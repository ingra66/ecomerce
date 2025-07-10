@extends('layouts.app')

@section('title', 'Registrarse - Beltspot')
@section('description', 'Crea tu cuenta en Beltspot')

@section('content')
<div class="bg-black min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Crear Cuenta</h1>
                <p class="text-gray-400">Únete a Beltspot y descubre productos increíbles</p>
            </div>

            <!-- Formulario de Registro -->
            <div class="bg-neutral-900 rounded-lg p-8 border border-gray-700">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-gray-300 text-sm font-medium mb-2">
                            Nombre Completo
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               required 
                               autofocus
                               class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary @error('name') border-red-500 @enderror"
                               placeholder="Tu nombre completo">
                        @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

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
                               placeholder="Mínimo 8 caracteres">
                        @error('password')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-gray-300 text-sm font-medium mb-2">
                            Confirmar Contraseña
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required
                               class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary"
                               placeholder="Repite tu contraseña">
                    </div>

                    <!-- Términos y Condiciones -->
                    <div class="flex items-start">
                        <input type="checkbox" 
                               id="terms" 
                               name="terms" 
                               required
                               class="mt-1 rounded border-gray-700 text-primary focus:ring-primary bg-black">
                        <label for="terms" class="ml-2 text-gray-300 text-sm">
                            Acepto los 
                            <a href="#" class="text-primary hover:text-red-700">Términos y Condiciones</a> 
                            y la 
                            <a href="#" class="text-primary hover:text-red-700">Política de Privacidad</a>
                        </label>
                    </div>

                    <!-- Newsletter -->
                    <div class="flex items-start">
                        <input type="checkbox" 
                               id="newsletter" 
                               name="newsletter"
                               class="mt-1 rounded border-gray-700 text-primary focus:ring-primary bg-black">
                        <label for="newsletter" class="ml-2 text-gray-300 text-sm">
                            Quiero recibir ofertas y novedades por email
                        </label>
                    </div>

                    <!-- Botón de Registro -->
                    <button type="submit" 
                            class="w-full bg-primary hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>
                        Crear Cuenta
                    </button>
                </form>

                <!-- Separador -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-neutral-900 text-gray-400">O regístrate con</span>
                    </div>
                </div>

                <!-- Registro Social -->
                <div class="space-y-3">
                    <button class="w-full bg-black border border-gray-700 hover:border-primary text-gray-300 hover:text-primary py-3 rounded-lg font-semibold transition-colors">
                        <i class="fab fa-google mr-2"></i>
                        Registrarse con Google
                    </button>
                    <button class="w-full bg-black border border-gray-700 hover:border-primary text-gray-300 hover:text-primary py-3 rounded-lg font-semibold transition-colors">
                        <i class="fab fa-facebook mr-2"></i>
                        Registrarse con Facebook
                    </button>
                </div>

                <!-- Enlace a Login -->
                <div class="text-center mt-8 pt-6 border-t border-gray-700">
                    <p class="text-gray-400">
                        ¿Ya tienes una cuenta? 
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