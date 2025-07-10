@extends('layouts.app')

@section('title', 'BeltSpot - Tu Tienda Online')
@section('description', 'Descubre los mejores productos en BeltSpot')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-dark to-darker py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6">
                    Descubre <span class="text-primary">Productos</span> Increíbles
                </h1>
                <p class="text-xl text-gray-300 mb-8">
                    Encuentra los mejores productos al mejor precio. Envío gratis en compras superiores a $50.000
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('products') }}" class="bg-primary hover:bg-red-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Ver Productos
                    </a>
                    <a href="{{ route('categories') }}" class="border border-gray-600 hover:border-primary text-gray-300 hover:text-primary px-8 py-4 rounded-lg font-semibold text-lg transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-tags mr-2"></i>
                        Categorías
                    </a>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="relative">
                    <div class="absolute inset-0 bg-primary opacity-10 rounded-full blur-3xl"></div>
                    <div class="relative bg-secondary rounded-2xl p-8 border border-gray-700">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-dark rounded-lg p-4 border border-gray-700">
                                <i class="fas fa-shipping-fast text-primary text-2xl mb-2"></i>
                                <h3 class="text-white font-semibold">Envío Gratis</h3>
                                <p class="text-gray-400 text-sm">En compras superiores a $50.000</p>
                            </div>
                            <div class="bg-dark rounded-lg p-4 border border-gray-700">
                                <i class="fas fa-shield-alt text-primary text-2xl mb-2"></i>
                                <h3 class="text-white font-semibold">Compra Segura</h3>
                                <p class="text-gray-400 text-sm">Pago seguro con MercadoPago</p>
                            </div>
                            <div class="bg-dark rounded-lg p-4 border border-gray-700">
                                <i class="fas fa-undo text-primary text-2xl mb-2"></i>
                                <h3 class="text-white font-semibold">Devolución</h3>
                                <p class="text-gray-400 text-sm">30 días para devoluciones</p>
                            </div>
                            <div class="bg-dark rounded-lg p-4 border border-gray-700">
                                <i class="fas fa-headset text-primary text-2xl mb-2"></i>
                                <h3 class="text-white font-semibold">Soporte 24/7</h3>
                                <p class="text-gray-400 text-sm">Atención al cliente</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categorías Destacadas -->
<section class="py-16 bg-dark">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white mb-4">Categorías Destacadas</h2>
            <p class="text-gray-400">Explora nuestras categorías más populares</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($featuredCategories ?? [] as $category)
            <a href="{{ route('products', ['category' => $category->id]) }}" class="group">
                <div class="bg-secondary rounded-lg p-6 border border-gray-700 hover:border-primary transition-colors">
                    <div class="w-16 h-16 bg-primary rounded-lg flex items-center justify-center mx-auto mb-4 group-hover:bg-red-700 transition-colors">
                        <i class="fas fa-tag text-white text-xl"></i>
                    </div>
                    <h3 class="text-white font-semibold text-center group-hover:text-primary transition-colors">{{ $category->name }}</h3>
                    <p class="text-gray-400 text-sm text-center mt-2">{{ $category->products_count ?? 0 }} productos</p>
                </div>
            </a>
            @endforeach
            
            <!-- Categorías de ejemplo si no hay datos -->
            @if(empty($featuredCategories))
            <a href="#" class="group">
                <div class="bg-secondary rounded-lg p-6 border border-gray-700 hover:border-primary transition-colors">
                    <div class="w-16 h-16 bg-primary rounded-lg flex items-center justify-center mx-auto mb-4 group-hover:bg-red-700 transition-colors">
                        <i class="fas fa-tshirt text-white text-xl"></i>
                    </div>
                    <h3 class="text-white font-semibold text-center group-hover:text-primary transition-colors">Ropa</h3>
                    <p class="text-gray-400 text-sm text-center mt-2">150 productos</p>
                </div>
            </a>
            <a href="#" class="group">
                <div class="bg-secondary rounded-lg p-6 border border-gray-700 hover:border-primary transition-colors">
                    <div class="w-16 h-16 bg-primary rounded-lg flex items-center justify-center mx-auto mb-4 group-hover:bg-red-700 transition-colors">
                        <i class="fas fa-mobile-alt text-white text-xl"></i>
                    </div>
                    <h3 class="text-white font-semibold text-center group-hover:text-primary transition-colors">Tecnología</h3>
                    <p class="text-gray-400 text-sm text-center mt-2">89 productos</p>
                </div>
            </a>
            <a href="#" class="group">
                <div class="bg-secondary rounded-lg p-6 border border-gray-700 hover:border-primary transition-colors">
                    <div class="w-16 h-16 bg-primary rounded-lg flex items-center justify-center mx-auto mb-4 group-hover:bg-red-700 transition-colors">
                        <i class="fas fa-home text-white text-xl"></i>
                    </div>
                    <h3 class="text-white font-semibold text-center group-hover:text-primary transition-colors">Hogar</h3>
                    <p class="text-gray-400 text-sm text-center mt-2">234 productos</p>
                </div>
            </a>
            <a href="#" class="group">
                <div class="bg-secondary rounded-lg p-6 border border-gray-700 hover:border-primary transition-colors">
                    <div class="w-16 h-16 bg-primary rounded-lg flex items-center justify-center mx-auto mb-4 group-hover:bg-red-700 transition-colors">
                        <i class="fas fa-dumbbell text-white text-xl"></i>
                    </div>
                    <h3 class="text-white font-semibold text-center group-hover:text-primary transition-colors">Deportes</h3>
                    <p class="text-gray-400 text-sm text-center mt-2">67 productos</p>
                </div>
            </a>
            @endif
        </div>
    </div>
</section>

<!-- Productos Destacados -->
<section class="py-16 bg-darker">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white mb-4">Productos Destacados</h2>
            <p class="text-gray-400">Los productos más populares de nuestra tienda</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts ?? [] as $product)
            <div class="bg-secondary rounded-lg border border-gray-700 hover:border-primary transition-colors group">
                <div class="relative">
                    <img src="{{ $product->primary_image_url }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-48 object-cover rounded-t-lg">
                    @if($product->has_discount)
                    <div class="absolute top-2 left-2 bg-primary text-white px-2 py-1 rounded text-sm font-semibold">
                        -{{ $product->discount_percentage }}%
                    </div>
                    @endif
                    <button class="absolute top-2 right-2 w-8 h-8 bg-dark bg-opacity-80 rounded-full flex items-center justify-center text-gray-300 hover:text-primary transition-colors">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <div class="p-4">
                    <h3 class="text-white font-semibold mb-2 group-hover:text-primary transition-colors">
                        {{ $product->name }}
                    </h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-600' }}"></i>
                            @endfor
                        </div>
                        <span class="text-gray-400 text-sm ml-2">({{ $product->reviews_count }})</span>
                    </div>
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="text-primary font-bold text-lg">{{ $product->formatted_price }}</span>
                            @if($product->has_discount)
                            <span class="text-gray-400 line-through text-sm ml-2">{{ $product->formatted_compare_price }}</span>
                            @endif
                        </div>
                        <span class="text-gray-400 text-sm">{{ $product->category->name }}</span>
                    </div>
                    <button class="w-full bg-primary hover:bg-red-700 text-white py-2 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Agregar al Carrito
                    </button>
                </div>
            </div>
            @endforeach
            
            <!-- Productos de ejemplo si no hay datos -->
            @if(empty($featuredProducts))
            @for($i = 1; $i <= 4; $i++)
            <div class="bg-secondary rounded-lg border border-gray-700 hover:border-primary transition-colors group">
                <div class="relative">
                    <div class="w-full h-48 bg-gray-700 rounded-t-lg flex items-center justify-center">
                        <i class="fas fa-image text-gray-500 text-3xl"></i>
                    </div>
                    <div class="absolute top-2 left-2 bg-primary text-white px-2 py-1 rounded text-sm font-semibold">
                        -20%
                    </div>
                    <button class="absolute top-2 right-2 w-8 h-8 bg-dark bg-opacity-80 rounded-full flex items-center justify-center text-gray-300 hover:text-primary transition-colors">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <div class="p-4">
                    <h3 class="text-white font-semibold mb-2 group-hover:text-primary transition-colors">
                        Producto Ejemplo {{ $i }}
                    </h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-gray-600"></i>
                        </div>
                        <span class="text-gray-400 text-sm ml-2">(12)</span>
                    </div>
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="text-primary font-bold text-lg">${{ number_format(rand(10000, 50000), 0, ',', '.') }}</span>
                            <span class="text-gray-400 line-through text-sm ml-2">${{ number_format(rand(15000, 60000), 0, ',', '.') }}</span>
                        </div>
                        <span class="text-gray-400 text-sm">Categoría</span>
                    </div>
                    <button class="w-full bg-primary hover:bg-red-700 text-white py-2 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Agregar al Carrito
                    </button>
                </div>
            </div>
            @endfor
            @endif
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('products') }}" class="inline-flex items-center text-primary hover:text-red-700 font-semibold transition-colors">
                Ver todos los productos
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Características -->
<section class="py-16 bg-dark">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shipping-fast text-white text-2xl"></i>
                </div>
                <h3 class="text-white font-semibold text-xl mb-2">Envío Gratis</h3>
                <p class="text-gray-400">En compras superiores a $50.000 en todo el país</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-white font-semibold text-xl mb-2">Compra Segura</h3>
                <p class="text-gray-400">Pago seguro con MercadoPago y envío garantizado</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-white text-2xl"></i>
                </div>
                <h3 class="text-white font-semibold text-xl mb-2">Soporte 24/7</h3>
                <p class="text-gray-400">Atención al cliente disponible todo el día</p>
            </div>
        </div>
    </div>
</section>
@endsection 