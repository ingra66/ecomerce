@extends('layouts.app')

@section('title', 'Productos - Beltspot')
@section('description', 'Descubre todos nuestros productos')

@section('content')
<div class="bg-black min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Productos</h1>
            <p class="text-gray-400">Encuentra los mejores productos al mejor precio</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filtros -->
            <div class="lg:col-span-1">
                <div class="bg-neutral-900 rounded-lg p-6 border border-gray-700 sticky top-24">
                    <h3 class="text-white font-semibold text-lg mb-4">Filtros</h3>
                    
                    <!-- Buscador -->
                    <div class="mb-6">
                        <label class="block text-gray-300 text-sm font-medium mb-2">Buscar</label>
                        <form method="GET" action="{{ route('products') }}">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Buscar productos..." 
                                   class="w-full bg-black border border-gray-700 rounded-lg px-3 py-2 text-gray-100 placeholder-gray-400 focus:outline-none focus:border-primary">
                        </form>
                    </div>

                    <!-- Categorías -->
                    <div class="mb-6">
                        <label class="block text-gray-300 text-sm font-medium mb-2">Categorías</label>
                        <div class="space-y-2">
                            <a href="{{ route('products') }}" 
                               class="block text-gray-400 hover:text-primary transition-colors {{ !request('category') ? 'text-primary' : '' }}">
                                Todas las categorías
                            </a>
                            @foreach($categories as $category)
                            <a href="{{ route('products', ['category' => $category->id]) }}" 
                               class="block text-gray-400 hover:text-primary transition-colors {{ request('category') == $category->id ? 'text-primary' : '' }}">
                                {{ $category->name }}
                                <span class="text-gray-500 text-sm">({{ $category->products_count }})</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ordenar -->
                    <div class="mb-6">
                        <label class="block text-gray-300 text-sm font-medium mb-2">Ordenar por</label>
                        <select name="sort_by" 
                                onchange="this.form.submit()" 
                                class="w-full bg-black border border-gray-700 rounded-lg px-3 py-2 text-gray-100 focus:outline-none focus:border-primary">
                            <option value="sort_order" {{ request('sort_by') == 'sort_order' ? 'selected' : '' }}>Más relevantes</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                            <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Precio menor</option>
                            <option value="price" {{ request('sort_by') == 'price' && request('sort_order') == 'desc' ? 'selected' : '' }}>Precio mayor</option>
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Más recientes</option>
                        </select>
                    </div>

                    <!-- Filtros adicionales -->
                    <div class="space-y-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="featured" 
                                   value="1" 
                                   {{ request('featured') ? 'checked' : '' }}
                                   onchange="this.form.submit()"
                                   class="rounded border-gray-700 text-primary focus:ring-primary bg-black">
                            <span class="ml-2 text-gray-300 text-sm">Solo destacados</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="in_stock" 
                                   value="1" 
                                   {{ request('in_stock') ? 'checked' : '' }}
                                   onchange="this.form.submit()"
                                   class="rounded border-gray-700 text-primary focus:ring-primary bg-black">
                            <span class="ml-2 text-gray-300 text-sm">Solo en stock</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Lista de Productos -->
            <div class="lg:col-span-3">
                <!-- Resultados -->
                <div class="flex items-center justify-between mb-6">
                    <p class="text-gray-400">
                        Mostrando {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} de {{ $products->total() }} productos
                    </p>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-400 text-sm">Ver:</span>
                        <button class="text-primary bg-primary bg-opacity-20 px-3 py-1 rounded text-sm">12</button>
                        <button class="text-gray-400 hover:text-primary px-3 py-1 rounded text-sm">24</button>
                        <button class="text-gray-400 hover:text-primary px-3 py-1 rounded text-sm">48</button>
                    </div>
                </div>

                <!-- Grid de Productos -->
                @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                    <div class="bg-neutral-900 rounded-lg border border-gray-700 hover:border-red-700 transition-colors group">
                        <div class="relative">
                            <a href="{{ route('product.show', $product->id) }}">
                                <img src="{{ $product->primary_image_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover rounded-t-lg group-hover:opacity-90 transition-opacity">
                            </a>
                            @if($product->has_discount)
                            <div class="absolute top-2 left-2 bg-primary text-white px-2 py-1 rounded text-sm font-semibold">
                                -{{ $product->discount_percentage }}%
                            </div>
                            @endif
                            <button class="absolute top-2 right-2 w-8 h-8 bg-black bg-opacity-80 rounded-full flex items-center justify-center text-gray-300 hover:text-primary transition-colors">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <a href="{{ route('product.show', $product->id) }}" class="block">
                                <h3 class="text-white font-semibold mb-2 group-hover:text-primary transition-colors">
                                    {{ $product->name }}
                                </h3>
                            </a>
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
                </div>

                <!-- Paginación -->
                @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
                @endif

                @else
                <!-- Sin productos -->
                <div class="text-center py-12">
                    <i class="fas fa-search text-gray-500 text-4xl mb-4"></i>
                    <h3 class="text-white text-xl font-semibold mb-2">No se encontraron productos</h3>
                    <p class="text-gray-400 mb-6">Intenta ajustar los filtros o buscar algo diferente</p>
                    <a href="{{ route('products') }}" class="bg-primary hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        Ver todos los productos
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 