<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostrar la página de inicio
     */
    public function index()
    {
        // Obtener productos destacados
        $featuredProducts = Product::with(['category', 'images'])
            ->active()
            ->featured()
            ->ordered()
            ->take(8)
            ->get();

        // Obtener categorías destacadas
        $featuredCategories = Category::withCount('products')
            ->active()
            ->ordered()
            ->take(4)
            ->get();

        return view('home', compact('featuredProducts', 'featuredCategories'));
    }

    /**
     * Mostrar página de productos
     */
    public function products(Request $request)
    {
        $query = Product::with(['category', 'images'])
            ->active();

        // Filtros
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
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

        // Categorías para filtros
        $categories = Category::active()->ordered()->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Mostrar detalle de producto
     */
    public function product($id)
    {
        $product = Product::with([
            'category', 
            'images', 
            'variants',
            'reviews' => function ($query) {
                $query->approved()->latest();
            }
        ])->active()->findOrFail($id);

        // Productos relacionados
        $relatedProducts = Product::with(['category', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Mostrar categorías
     */
    public function categories()
    {
        $categories = Category::withCount('products')
            ->active()
            ->ordered()
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Mostrar productos por categoría
     */
    public function category($id)
    {
        $category = Category::active()->findOrFail($id);
        
        $products = Product::with(['category', 'images'])
            ->where('category_id', $id)
            ->active()
            ->ordered()
            ->paginate(12);

        return view('categories.show', compact('category', 'products'));
    }

    /**
     * Mostrar carrito
     */
    public function cart()
    {
        return view('cart.index');
    }

    /**
     * Mostrar wishlist
     */
    public function wishlist()
    {
        return view('wishlist.index');
    }

    /**
     * Mostrar perfil
     */
    public function profile()
    {
        return view('profile.index');
    }

    /**
     * Mostrar órdenes
     */
    public function orders()
    {
        return view('orders.index');
    }

    /**
     * Mostrar contacto
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Mostrar sobre nosotros
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Mostrar FAQ
     */
    public function faq()
    {
        return view('faq');
    }

    /**
     * Mostrar envíos
     */
    public function shipping()
    {
        return view('shipping');
    }

    /**
     * Mostrar devoluciones
     */
    public function returns()
    {
        return view('returns');
    }

    /**
     * Mostrar privacidad
     */
    public function privacy()
    {
        return view('privacy');
    }
} 