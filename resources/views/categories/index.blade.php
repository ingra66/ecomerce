@extends('layouts.app')

@section('title', 'Categorías - Beltspot')
@section('description', 'Explora todas las categorías de productos en Beltspot')

@section('content')
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white mb-4">Categorías</h2>
            <p class="text-gray-400">Explora todas las categorías disponibles en nuestra tienda</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @forelse($categories as $category)
                <a href="{{ route('category.show', $category->id) }}" class="group">
                    <div class="bg-neutral-900 rounded-lg p-6 border border-gray-700 hover:border-red-700 transition-colors">
                        <div class="w-16 h-16 bg-primary rounded-lg flex items-center justify-center mx-auto mb-4 group-hover:bg-red-700 transition-colors">
                            <i class="fas fa-tag text-white text-xl"></i>
                        </div>
                        <h3 class="text-white font-semibold text-center group-hover:text-primary transition-colors">{{ $category->name }}</h3>
                        <p class="text-gray-400 text-sm text-center mt-2">{{ $category->products_count ?? 0 }} productos</p>
                    </div>
                </a>
            @empty
                <div class="col-span-4 text-center text-gray-400">No hay categorías disponibles.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection 