<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    /**
     * Obtener carrito actual
     */
    public function index(): JsonResponse
    {
        $cart = Cart::getCurrent();
        
        if (!$cart) {
            return response()->json([
                'success' => true,
                'data' => [
                    'items' => [],
                    'total' => 0,
                    'items_count' => 0,
                    'is_empty' => true
                ]
            ]);
        }

        $cart->load(['items.product.images', 'items.product.variants']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $cart->id,
                'items' => $cart->items,
                'total' => $cart->total,
                'formatted_total' => $cart->formatted_total,
                'items_count' => $cart->items_count,
                'unique_products_count' => $cart->unique_products_count,
                'is_empty' => $cart->is_empty
            ]
        ]);
    }

    /**
     * Agregar producto al carrito
     */
    public function addItem(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variants' => 'sometimes|array'
        ]);

        $cart = Cart::getCurrent();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener el carrito'
            ], 400);
        }

        $product = Product::active()->findOrFail($request->product_id);
        
        // Verificar stock
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuficiente'
            ], 400);
        }

        $variants = $request->get('variants', []);
        
        // Verificar variantes si existen
        if (!empty($variants)) {
            foreach ($variants as $variantType => $variantValue) {
                $variant = $product->variants()
                    ->where('name', $variantType)
                    ->where('value', $variantValue)
                    ->active()
                    ->first();
                
                if (!$variant) {
                    return response()->json([
                        'success' => false,
                        'message' => "Variante {$variantType}: {$variantValue} no válida"
                    ], 400);
                }
            }
        }

        $cartItem = $cart->addItem($request->product_id, $request->quantity, $variants);
        
        $cart->load(['items.product.images', 'items.product.variants']);

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'data' => [
                'cart_item' => $cartItem,
                'cart' => [
                    'total' => $cart->total,
                    'formatted_total' => $cart->formatted_total,
                    'items_count' => $cart->items_count,
                    'unique_products_count' => $cart->unique_products_count
                ]
            ]
        ]);
    }

    /**
     * Actualizar cantidad de un item
     */
    public function updateQuantity(Request $request, $itemId): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = Cart::getCurrent();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener el carrito'
            ], 400);
        }

        $cartItem = $cart->items()->find($itemId);
        
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item no encontrado'
            ], 404);
        }

        // Verificar stock si la cantidad es mayor a 0
        if ($request->quantity > 0) {
            $product = $cartItem->product;
            if ($product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente'
                ], 400);
            }
        }

        $updatedItem = $cart->updateItemQuantity($itemId, $request->quantity);
        
        $cart->load(['items.product.images', 'items.product.variants']);

        return response()->json([
            'success' => true,
            'message' => $request->quantity > 0 ? 'Cantidad actualizada' : 'Item removido',
            'data' => [
                'cart_item' => $updatedItem,
                'cart' => [
                    'total' => $cart->total,
                    'formatted_total' => $cart->formatted_total,
                    'items_count' => $cart->items_count,
                    'unique_products_count' => $cart->unique_products_count
                ]
            ]
        ]);
    }

    /**
     * Remover item del carrito
     */
    public function removeItem($itemId): JsonResponse
    {
        $cart = Cart::getCurrent();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener el carrito'
            ], 400);
        }

        $deleted = $cart->removeItem($itemId);
        
        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Item no encontrado'
            ], 404);
        }

        $cart->load(['items.product.images', 'items.product.variants']);

        return response()->json([
            'success' => true,
            'message' => 'Item removido del carrito',
            'data' => [
                'cart' => [
                    'total' => $cart->total,
                    'formatted_total' => $cart->formatted_total,
                    'items_count' => $cart->items_count,
                    'unique_products_count' => $cart->unique_products_count
                ]
            ]
        ]);
    }

    /**
     * Limpiar carrito
     */
    public function clear(): JsonResponse
    {
        $cart = Cart::getCurrent();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener el carrito'
            ], 400);
        }

        $cart->clear();

        return response()->json([
            'success' => true,
            'message' => 'Carrito limpiado',
            'data' => [
                'cart' => [
                    'total' => 0,
                    'formatted_total' => '$0,00',
                    'items_count' => 0,
                    'unique_products_count' => 0,
                    'is_empty' => true
                ]
            ]
        ]);
    }
} 