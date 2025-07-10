<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Obtener lista de productos
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'images', 'variants', 'reviews'])
            ->active();

        // Filtros
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->has('featured')) {
            $query->featured();
        }

        if ($request->has('in_stock')) {
            $query->inStock();
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginación
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * Obtener producto específico
     */
    public function show($id): JsonResponse
    {
        $product = Product::with([
            'category', 
            'images', 
            'variants', 
            'reviews' => function ($query) {
                $query->approved()->latest();
            }
        ])->active()->findOrFail($id);

        // Agregar información adicional
        $product->load('reviews.user');
        
        // Verificar si está en lista de deseos del usuario actual
        if (auth()->check()) {
            $product->is_in_wishlist = $product->isInWishlist(auth()->id());
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Obtener productos destacados
     */
    public function featured(): JsonResponse
    {
        $products = Product::with(['category', 'images'])
            ->active()
            ->featured()
            ->ordered()
            ->take(8)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Obtener productos por categoría
     */
    public function byCategory($categoryId): JsonResponse
    {
        $category = Category::active()->findOrFail($categoryId);
        
        $products = Product::with(['category', 'images'])
            ->where('category_id', $categoryId)
            ->active()
            ->ordered()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'products' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ]
            ]
        ]);
    }

    /**
     * Buscar productos
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        $query = $request->get('q');
        
        $products = Product::with(['category', 'images'])
            ->active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->ordered()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * Obtener categorías
     */
    public function categories(): JsonResponse
    {
        $categories = Category::with(['products' => function ($query) {
            $query->active()->count();
        }])
        ->active()
        ->ordered()
        ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
} 