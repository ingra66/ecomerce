<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BeltSpot - Tu Tienda Online')</title>
    <meta name="description" content="@yield('description', 'Descubre los mejores productos en BeltSpot')">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Configuración de Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#dc2626', // Rojo
                        'secondary': '#1f2937', // Gris oscuro
                        'dark': '#111827', // Negro
                        'darker': '#0f172a', // Negro más oscuro
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- MercadoPago SDK -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    
    @stack('styles')
</head>
<body class="bg-dark text-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-darker border-b border-gray-800 sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-primary">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        BeltSpot
                    </a>
                </div>

                <!-- Navegación Principal -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-primary transition-colors">
                        Inicio
                    </a>
                    <a href="{{ route('products') }}" class="text-gray-300 hover:text-primary transition-colors">
                        Productos
                    </a>
                    <a href="{{ route('categories') }}" class="text-gray-300 hover:text-primary transition-colors">
                        Categorías
                    </a>
                    <a href="{{ route('contact') }}" class="text-gray-300 hover:text-primary transition-colors">
                        Contacto
                    </a>
                </nav>

                <!-- Acciones del Usuario -->
                <div class="flex items-center space-x-4">
                    <!-- Buscador -->
                    <div class="relative hidden md:block">
                        <input type="text" 
                               placeholder="Buscar productos..." 
                               class="bg-secondary border border-gray-700 rounded-lg px-4 py-2 pl-10 text-sm text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    </div>

                    <!-- Wishlist -->
                    <a href="{{ route('wishlist') }}" class="text-gray-300 hover:text-primary transition-colors relative">
                        <i class="fas fa-heart text-xl"></i>
                        <span class="absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                    </a>

                    <!-- Carrito -->
                    <a href="{{ route('cart.index') }}" class="text-gray-300 hover:text-primary transition-colors relative">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                    </a>

                    <!-- Usuario -->
                    @auth
                        <div class="relative group">
                            <button class="flex items-center text-gray-300 hover:text-primary transition-colors">
                                <i class="fas fa-user-circle text-xl mr-2"></i>
                                <span class="hidden md:block">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-darker border border-gray-700 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-300 hover:bg-secondary hover:text-primary">
                                    <i class="fas fa-user mr-2"></i> Mi Perfil
                                </a>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-gray-300 hover:bg-secondary hover:text-primary">
                                    <i class="fas fa-box mr-2"></i> Mis Órdenes
                                </a>
                                <hr class="border-gray-700">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-300 hover:bg-secondary hover:text-primary">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-primary transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <span class="hidden md:block">Iniciar Sesión</span>
                        </a>
                    @endauth

                    <!-- Menú Móvil -->
                    <button class="md:hidden text-gray-300 hover:text-primary transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-darker border-t border-gray-800 mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Información de la Tienda -->
                <div>
                    <h3 class="text-xl font-bold text-primary mb-4">BeltSpot</h3>
                    <p class="text-gray-400 text-sm">
                        Tu tienda online de confianza. Productos de calidad al mejor precio.
                    </p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Enlaces Rápidos -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-200 mb-4">Enlaces Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('products') }}" class="text-gray-400 hover:text-primary transition-colors">Productos</a></li>
                        <li><a href="{{ route('categories') }}" class="text-gray-400 hover:text-primary transition-colors">Categorías</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-primary transition-colors">Nosotros</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-primary transition-colors">Contacto</a></li>
                    </ul>
                </div>

                <!-- Ayuda -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-200 mb-4">Ayuda</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('faq') }}" class="text-gray-400 hover:text-primary transition-colors">Preguntas Frecuentes</a></li>
                        <li><a href="{{ route('shipping') }}" class="text-gray-400 hover:text-primary transition-colors">Envíos</a></li>
                        <li><a href="{{ route('returns') }}" class="text-gray-400 hover:text-primary transition-colors">Devoluciones</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-gray-400 hover:text-primary transition-colors">Privacidad</a></li>
                    </ul>
                </div>

                <!-- Contacto -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-200 mb-4">Contacto</h4>
                    <div class="space-y-2 text-gray-400 text-sm">
                        <p><i class="fas fa-phone mr-2"></i> +54 11 1234-5678</p>
                        <p><i class="fas fa-envelope mr-2"></i> info@beltspot.com</p>
                        <p><i class="fas fa-map-marker-alt mr-2"></i> Buenos Aires, Argentina</p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-700 my-8">

            <!-- Copyright -->
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} BeltSpot. Todos los derechos reservados.
                </p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <img src="https://www.mercadopago.com/developers/static/img/logo/logo-mercado-pago.png" 
                         alt="MercadoPago" 
                         class="h-8 opacity-50">
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Funcionalidad básica
        document.addEventListener('DOMContentLoaded', function() {
            // Menú móvil
            const mobileMenuBtn = document.querySelector('.md\\:hidden');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Buscador
            const searchInput = document.querySelector('input[placeholder="Buscar productos..."]');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const query = this.value.trim();
                        if (query) {
                            window.location.href = `/products?search=${encodeURIComponent(query)}`;
                        }
                    }
                });
            }

            // Actualizar contador del carrito
            updateCartCount();
        });

        // Función para actualizar el contador del carrito
        function updateCartCount() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.count;
                        if (data.count > 0) {
                            cartCountElement.style.display = 'flex';
                        } else {
                            cartCountElement.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error al obtener el contador del carrito:', error);
                });
        }
    </script>

    @stack('scripts')
</body>
</html> 