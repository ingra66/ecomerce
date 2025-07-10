@extends('layouts.app')

@section('title', 'Contacto - Beltspot')
@section('description', 'Contáctanos para cualquier consulta')

@section('content')
<div class="bg-black min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-white mb-4">Contáctanos</h1>
            <p class="text-gray-400 text-lg">¿Tienes alguna pregunta? Estamos aquí para ayudarte</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Información de Contacto -->
            <div>
                <div class="bg-neutral-900 rounded-lg p-8 border border-gray-700">
                    <h2 class="text-2xl font-bold text-white mb-6">Información de Contacto</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold mb-1">Dirección</h3>
                                <p class="text-gray-400">Av. Corrientes 1234<br>Buenos Aires, Argentina</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold mb-1">Teléfono</h3>
                                <p class="text-gray-400">+54 11 1234-5678</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold mb-1">Email</h3>
                                <p class="text-gray-400">info@beltspot.com</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold mb-1">Horarios de Atención</h3>
                                <p class="text-gray-400">Lunes a Viernes: 9:00 - 18:00<br>Sábados: 9:00 - 13:00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Redes Sociales -->
                    <div class="mt-8 pt-8 border-t border-gray-700">
                        <h3 class="text-white font-semibold mb-4">Síguenos</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white hover:bg-red-700 transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white hover:bg-red-700 transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white hover:bg-red-700 transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white hover:bg-red-700 transition-colors">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Contacto -->
            <div>
                <div class="bg-neutral-900 rounded-lg p-8 border border-gray-700">
                    <h2 class="text-2xl font-bold text-white mb-6">Envíanos un Mensaje</h2>
                    
                    <form method="POST" action="#" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-gray-300 text-sm font-medium mb-2">Nombre</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       required
                                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary">
                            </div>
                            <div>
                                <label for="email" class="block text-gray-300 text-sm font-medium mb-2">Email</label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       required
                                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-gray-300 text-sm font-medium mb-2">Asunto</label>
                            <select id="subject" 
                                    name="subject" 
                                    required
                                    class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-gray-100 focus:outline-none focus:border-primary">
                                <option value="">Selecciona un asunto</option>
                                <option value="general">Consulta General</option>
                                <option value="product">Consulta sobre Producto</option>
                                <option value="order">Consulta sobre Orden</option>
                                <option value="technical">Soporte Técnico</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>

                        <div>
                            <label for="message" class="block text-gray-300 text-sm font-medium mb-2">Mensaje</label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="5" 
                                      required
                                      placeholder="Escribe tu mensaje aquí..."
                                      class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary"></textarea>
                        </div>

                        <button type="submit" 
                                class="w-full bg-primary hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Enviar Mensaje
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mapa -->
        <div class="mt-12">
            <div class="bg-neutral-900 rounded-lg p-8 border border-gray-700">
                <h2 class="text-2xl font-bold text-white mb-6">Ubicación</h2>
                <div class="bg-black rounded-lg h-64 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-map text-gray-500 text-4xl mb-4"></i>
                        <p class="text-gray-400">Mapa de ubicación</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 