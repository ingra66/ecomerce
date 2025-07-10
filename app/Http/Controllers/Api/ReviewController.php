<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /**
     * Obtener reseñas del usuario
     */
    public function myReviews(): JsonResponse
    {
        $user = auth()->user();
        
        $reviews = Review::with(['product.images', 'product.category'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ]
        ]);
    }

    /**
     * Crear reseña
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'sometimes|string|max:255',
            'comment' => 'sometimes|string|max:1000',
            'order_id' => 'sometimes|exists:orders,id'
        ]);

        $user = auth()->user();
        $productId = $request->product_id;

        // Verificar si ya existe una reseña del usuario para este producto
        $existingReview = Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has reseñado este producto'
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

        // Verificar compra verificada si se proporciona order_id
        $isVerifiedPurchase = false;
        if ($request->has('order_id')) {
            $order = $user->orders()->where('id', $request->order_id)->first();
            if ($order && $order->items()->where('product_id', $productId)->exists()) {
                $isVerifiedPurchase = true;
            }
        }

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_verified_purchase' => $isVerifiedPurchase,
            'is_approved' => false // Por defecto requiere aprobación
        ]);

        $review->load(['product.images', 'product.category']);

        return response()->json([
            'success' => true,
            'message' => 'Reseña creada exitosamente. Será revisada antes de ser publicada.',
            'data' => $review
        ], 201);
    }

    /**
     * Actualizar reseña
     */
    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'title' => 'sometimes|string|max:255',
            'comment' => 'sometimes|string|max:1000'
        ]);

        $user = auth()->user();
        
        $review = Review::where('user_id', $user->id)
            ->findOrFail($id);

        // Solo permitir actualizar si no está aprobada
        if ($review->is_approved) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede editar una reseña aprobada'
            ], 400);
        }

        $review->update($request->only(['rating', 'title', 'comment']));
        $review->load(['product.images', 'product.category']);

        return response()->json([
            'success' => true,
            'message' => 'Reseña actualizada exitosamente',
            'data' => $review
        ]);
    }

    /**
     * Eliminar reseña
     */
    public function destroy($id): JsonResponse
    {
        $user = auth()->user();
        
        $review = Review::where('user_id', $user->id)
            ->findOrFail($id);

        // Solo permitir eliminar si no está aprobada
        if ($review->is_approved) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar una reseña aprobada'
            ], 400);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reseña eliminada exitosamente'
        ]);
    }

    /**
     * Obtener reseñas de un producto
     */
    public function productReviews($productId): JsonResponse
    {
        $reviews = Review::with(['user'])
            ->where('product_id', $productId)
            ->approved()
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ]
        ]);
    }
} 