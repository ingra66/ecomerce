<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;

class CartController extends Controller
{
    public function index()
    {
        $cart = null;
        $cartItems = [];
        $total = 0;
        $itemCount = 0;

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cartItems = $cart->items()->with(['product', 'variant'])->get();
                $total = $cartItems->sum(function ($item) {
                    return $item->quantity * $item->price;
                });
                $itemCount = $cartItems->sum('quantity');
            }
        } else {
            // Carrito de sesión para usuarios no autenticados
            $sessionCart = session('cart', []);
            $cartItems = collect($sessionCart)->map(function ($item) {
                $product = Product::find($item['product_id']);
                $variant = null;
                if (isset($item['variant_id'])) {
                    $variant = ProductVariant::find($item['variant_id']);
                }
                return (object) [
                    'id' => $item['id'],
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ];
            });
            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->price;
            });
            $itemCount = $cartItems->sum('quantity');
        }

        return view('cart.index', compact('cartItems', 'total', 'itemCount'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;
        $variantId = $request->variant_id;
        
        // Determinar el precio
        $price = $product->price;
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            $price = $variant->price ?? $product->price;
        }

        if (Auth::check()) {
            // Usuario autenticado - guardar en base de datos
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            
            // Verificar si el item ya existe
            $query = $cart->items()->where('product_id', $request->product_id);
            
            // Para simplificar, solo verificamos por producto por ahora
            // Si necesitas variantes específicas, podemos implementarlo después
            $existingItem = $query->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $quantity
                ]);
            } else {
                $cart->items()->create([
                    'product_id' => $request->product_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'selected_variants' => $variantId ? ['variant_id' => $variantId] : null
                ]);
            }
        } else {
            // Usuario no autenticado - guardar en sesión
            $sessionCart = session('cart', []);
            $itemId = uniqid();
            
            $sessionCart[] = [
                'id' => $itemId,
                'product_id' => $request->product_id,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'price' => $price
            ];
            
            session(['cart' => $sessionCart]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'cart_count' => $this->getCartCount()
        ]);
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if (Auth::check()) {
            $cartItem = CartItem::where('id', $itemId)
                ->whereHas('cart', function ($query) {
                    $query->where('user_id', Auth::id());
                })->firstOrFail();

            $cartItem->update(['quantity' => $request->quantity]);
        } else {
            $sessionCart = session('cart', []);
            foreach ($sessionCart as $key => $item) {
                if ($item['id'] == $itemId) {
                    $sessionCart[$key]['quantity'] = $request->quantity;
                    break;
                }
            }
            session(['cart' => $sessionCart]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cantidad actualizada',
            'cart_count' => $this->getCartCount()
        ]);
    }

    public function remove($itemId)
    {
        if (Auth::check()) {
            $cartItem = CartItem::where('id', $itemId)
                ->whereHas('cart', function ($query) {
                    $query->where('user_id', Auth::id());
                })->firstOrFail();

            $cartItem->delete();
        } else {
            $sessionCart = session('cart', []);
            $sessionCart = array_filter($sessionCart, function ($item) use ($itemId) {
                return $item['id'] != $itemId;
            });
            session(['cart' => $sessionCart]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Producto removido del carrito',
            'cart_count' => $this->getCartCount()
        ]);
    }

    public function clear()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cart->items()->delete();
            }
        } else {
            session()->forget('cart');
        }

        return response()->json([
            'success' => true,
            'message' => 'Carrito vaciado',
            'cart_count' => 0
        ]);
    }

    public function getCount()
    {
        return response()->json([
            'count' => $this->getCartCount()
        ]);
    }

    private function getCartCount()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            return $cart ? $cart->items()->sum('quantity') : 0;
        } else {
            $sessionCart = session('cart', []);
            return collect($sessionCart)->sum('quantity');
        }
    }
} 