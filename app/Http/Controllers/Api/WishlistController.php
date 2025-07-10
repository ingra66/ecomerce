<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WishlistController extends Controller
{
    /**
     * Obtener lista de deseos del usuario
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();
        
        $wishlist = Wishlist::with(['product.images', 'product.category'])
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $wishlist
        ]);
    }

    /**
     * Agregar producto a lista de deseos
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = auth()->user();
        $productId = $request->product_id;

        // Verificar si ya está en la lista
        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'El producto ya está en tu lista de deseos'
            ], 400);
        }

        // Verificar que el producto esté activo
        $product = Product::active()->find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no disponible'
            ], 400);
        }

        $wishlistItem = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $productId
        ]);

        $wishlistItem->load(['product.images', 'product.category']);

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado a la lista de deseos',
            'data' => $wishlistItem
        ], 201);
    }

    /**
     * Remover producto de lista de deseos
     */
    public function remove($productId): JsonResponse
    {
        $user = auth()->user();

        $deleted = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado en la lista de deseos'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Producto removido de la lista de deseos'
        ]);
    }

    /**
     * Verificar si un producto está en la lista de deseos
     */
    public function check($productId): JsonResponse
    {
        $user = auth()->user();

        $isInWishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'success' => true,
            'data' => [
                'is_in_wishlist' => $isInWishlist
            ]
        ]);
    }
} 