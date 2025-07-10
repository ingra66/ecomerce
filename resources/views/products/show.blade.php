@extends('layouts.app')

@section('title', $product->name . ' - BeltSpot')
@section('description', $product->short_description ?: $product->name)

@section('content')
<div class="bg-dark min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-400 hover:text-primary transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Inicio
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                        <a href="{{ route('products') }}" class="text-gray-400 hover:text-primary transition-colors">
                            Productos
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                        <a href="{{ route('category.show', $product->category->id) }}" class="text-gray-400 hover:text-primary transition-colors">
                            {{ $product->category->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                        <span class="text-gray-300">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Galería de Imágenes -->
            <div>
                <div class="relative">
                    <!-- Imagen Principal -->
                    <div class="bg-secondary rounded-lg overflow-hidden mb-4">
                        <img src="{{ $product->primary_image_url }}" 
                             alt="{{ $product->name }}" 
                             id="main-image"
                             class="w-full h-96 object-cover">
                    </div>
                    
                    <!-- Miniaturas -->
                    @if($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images->take(4) as $image)
                        <button onclick="changeImage('{{ $image->image_url }}')" 
                                class="bg-secondary rounded-lg overflow-hidden border-2 border-gray-700 hover:border-primary transition-colors">
                            <img src="{{ $image->image_url }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-20 object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Información del Producto -->
            <div>
                <h1 class="text-3xl font-bold text-white mb-4">{{ $product->name }}</h1>
                
                <!-- Rating -->
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400 mr-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-600' }}"></i>
                        @endfor
                    </div>
                    <span class="text-gray-400">({{ $product->reviews_count }} reseñas)</span>
                </div>

                <!-- Precio -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <span class="text-primary font-bold text-3xl">{{ $product->formatted_price }}</span>
                        @if($product->has_discount)
                        <span class="text-gray-400 line-through text-xl ml-3">{{ $product->formatted_compare_price }}</span>
                        <span class="bg-primary text-white px-2 py-1 rounded text-sm font-semibold ml-3">
                            -{{ $product->discount_percentage }}%
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Descripción Corta -->
                @if($product->short_description)
                <div class="mb-6">
                    <p class="text-gray-300">{{ $product->short_description }}</p>
                </div>
                @endif

                <!-- Variantes -->
                @if($product->variants->count() > 0)
                <div class="mb-6">
                    <h3 class="text-white font-semibold mb-3">Opciones</h3>
                    <div class="space-y-3">
                        @foreach($product->variants_grouped as $variantType => $variants)
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">{{ ucfirst($variantType) }}</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($variants as $variant)
                                <button class="px-4 py-2 border border-gray-700 rounded-lg text-gray-300 hover:border-primary hover:text-primary transition-colors {{ $variant->in_stock ? '' : 'opacity-50 cursor-not-allowed' }}">
                                    {{ $variant->value }}
                                    @if($variant->price_adjustment != 0)
                                    <span class="text-xs text-gray-400">{{ $variant->formatted_price_adjustment }}</span>
                                    @endif
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Cantidad y Stock -->
                <div class="mb-6">
                    <label class="block text-gray-300 text-sm font-medium mb-2">Cantidad</label>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center border border-gray-700 rounded-lg">
                            <button class="px-3 py-2 text-gray-300 hover:text-primary transition-colors">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" 
                                   value="1" 
                                   min="1" 
                                   max="{{ $product->stock }}"
                                   class="w-16 text-center bg-dark border-0 text-white focus:outline-none">
                            <button class="px-3 py-2 text-gray-300 hover:text-primary transition-colors">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <span class="text-gray-400 text-sm">
                            {{ $product->stock }} disponibles
                        </span>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="space-y-3 mb-6">
                    <button onclick="addToCart({{ $product->id }})" 
                            class="w-full bg-primary hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Agregar al Carrito
                    </button>
                    <button onclick="buyNow({{ $product->id }})" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-credit-card mr-2"></i>
                        Comprar Ahora
                    </button>
                    <div class="flex space-x-3">
                        <button class="flex-1 border border-gray-700 hover:border-primary text-gray-300 hover:text-primary py-3 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-heart mr-2"></i>
                            Agregar a Wishlist
                        </button>
                        <button class="flex-1 border border-gray-700 hover:border-primary text-gray-300 hover:text-primary py-3 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-share mr-2"></i>
                            Compartir
                        </button>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="border-t border-gray-700 pt-6">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-400">SKU:</span>
                            <span class="text-white ml-2">{{ $product->sku }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Categoría:</span>
                            <span class="text-white ml-2">{{ $product->category->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Stock:</span>
                            <span class="text-white ml-2">{{ $product->stock }} unidades</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Estado:</span>
                            <span class="text-green-400 ml-2">{{ $product->in_stock ? 'En stock' : 'Sin stock' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Descripción Completa -->
        @if($product->description)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-white mb-6">Descripción</h2>
            <div class="bg-secondary rounded-lg p-6 border border-gray-700">
                <div class="text-gray-300 prose prose-invert max-w-none">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
        </div>
        @endif

        <!-- Reseñas -->
        @if($product->reviews->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-white mb-6">Reseñas ({{ $product->reviews_count }})</h2>
            <div class="space-y-4">
                @foreach($product->reviews->take(5) as $review)
                <div class="bg-secondary rounded-lg p-4 border border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }}"></i>
                                @endfor
                            </div>
                            <span class="text-white font-semibold">{{ $review->user->name }}</span>
                            @if($review->is_verified_purchase)
                            <span class="bg-green-600 text-white text-xs px-2 py-1 rounded ml-2">Compra verificada</span>
                            @endif
                        </div>
                        <span class="text-gray-400 text-sm">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    @if($review->title)
                    <h4 class="text-white font-semibold mb-2">{{ $review->title }}</h4>
                    @endif
                    @if($review->comment)
                    <p class="text-gray-300">{{ $review->comment }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Productos Relacionados -->
        @if($relatedProducts->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-white mb-6">Productos Relacionados</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                <div class="bg-secondary rounded-lg border border-gray-700 hover:border-primary transition-colors group">
                    <div class="relative">
                        <a href="{{ route('product.show', $relatedProduct->id) }}">
                            <img src="{{ $relatedProduct->primary_image_url }}" 
                                 alt="{{ $relatedProduct->name }}" 
                                 class="w-full h-48 object-cover rounded-t-lg group-hover:opacity-90 transition-opacity">
                        </a>
                        @if($relatedProduct->has_discount)
                        <div class="absolute top-2 left-2 bg-primary text-white px-2 py-1 rounded text-sm font-semibold">
                            -{{ $relatedProduct->discount_percentage }}%
                        </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <a href="{{ route('product.show', $relatedProduct->id) }}" class="block">
                            <h3 class="text-white font-semibold mb-2 group-hover:text-primary transition-colors">
                                {{ $relatedProduct->name }}
                            </h3>
                        </a>
                        <div class="flex items-center justify-between">
                            <span class="text-primary font-bold">{{ $relatedProduct->formatted_price }}</span>
                            <span class="text-gray-400 text-sm">{{ $relatedProduct->category->name }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function changeImage(imageUrl) {
    document.getElementById('main-image').src = imageUrl;
}

function addToCart(productId) {
    const quantity = document.querySelector('input[type="number"]').value;
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: parseInt(quantity)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar notificación de éxito
            showNotification('Producto agregado al carrito', 'success');
            // Actualizar contador del carrito
            updateCartCount();
        } else {
            showNotification('Error al agregar el producto', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al agregar el producto', 'error');
    });
}

function showNotification(message, type) {
    // Crear notificación
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

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

function buyNow(productId) {
    const quantity = document.querySelector('input[type="number"]').value;

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: parseInt(quantity)
        })
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.indexOf('application/json') !== -1) {
            const data = await response.json();
            if (data.success) {
                window.location.href = '/orders/checkout';
            } else {
                showNotification('Error al agregar el producto', 'error');
            }
        } else {
            // Si no es JSON, probablemente es HTML de login
            window.location.href = '/login';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al agregar el producto', 'error');
    });
}

// Mejorar la funcionalidad de cantidad
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.querySelector('input[type="number"]');
    const minusBtn = quantityInput.previousElementSibling;
    const plusBtn = quantityInput.nextElementSibling;
    
    if (minusBtn && plusBtn) {
        minusBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
        
        plusBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            const maxValue = parseInt(quantityInput.getAttribute('max'));
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        });
    }
});
</script>
@endsection 