<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    /**
     * Relación con usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con items del carrito
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Obtener o crear carrito para usuario
     */
    public static function getOrCreateForUser($userId = null, $sessionId = null)
    {
        if ($userId) {
            $cart = self::where('user_id', $userId)->first();
            if (!$cart) {
                $cart = self::create(['user_id' => $userId]);
            }
        } elseif ($sessionId) {
            $cart = self::where('session_id', $sessionId)->first();
            if (!$cart) {
                $cart = self::create(['session_id' => $sessionId]);
            }
        } else {
            return null;
        }

        return $cart;
    }

    /**
     * Obtener carrito actual
     */
    public static function getCurrent()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        if ($userId) {
            return self::getOrCreateForUser($userId);
        }

        return self::getOrCreateForUser(null, $sessionId);
    }

    /**
     * Agregar producto al carrito
     */
    public function addItem($productId, $quantity = 1, $variants = [])
    {
        $existingItem = $this->items()
            ->where('product_id', $productId)
            ->where('selected_variants', json_encode($variants))
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);
            return $existingItem;
        }

        return $this->items()->create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'selected_variants' => $variants,
        ]);
    }

    /**
     * Remover item del carrito
     */
    public function removeItem($itemId)
    {
        return $this->items()->where('id', $itemId)->delete();
    }

    /**
     * Actualizar cantidad de un item
     */
    public function updateItemQuantity($itemId, $quantity)
    {
        $item = $this->items()->find($itemId);
        if ($item) {
            if ($quantity <= 0) {
                $item->delete();
                return null;
            }
            $item->update(['quantity' => $quantity]);
            return $item;
        }
        return null;
    }

    /**
     * Limpiar carrito
     */
    public function clear()
    {
        return $this->items()->delete();
    }

    /**
     * Obtener total del carrito
     */
    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            $product = $item->product;
            $basePrice = $product->price;
            
            // Aplicar ajustes de variantes
            if ($item->selected_variants) {
                foreach ($item->selected_variants as $variantType => $variantValue) {
                    $variant = $product->variants()
                        ->where('name', $variantType)
                        ->where('value', $variantValue)
                        ->first();
                    
                    if ($variant) {
                        $basePrice += $variant->price_adjustment;
                    }
                }
            }
            
            return $basePrice * $item->quantity;
        });
    }

    /**
     * Obtener total formateado
     */
    public function getFormattedTotalAttribute()
    {
        return '$' . number_format($this->total / 100, 2, ',', '.');
    }

    /**
     * Obtener cantidad total de items
     */
    public function getItemsCountAttribute()
    {
        return $this->items->sum('quantity');
    }

    /**
     * Verificar si el carrito está vacío
     */
    public function getIsEmptyAttribute()
    {
        return $this->items->isEmpty();
    }

    /**
     * Obtener productos únicos en el carrito
     */
    public function getUniqueProductsCountAttribute()
    {
        return $this->items->count();
    }

    /**
     * Convertir carrito a orden
     */
    public function convertToOrder($user, $shippingAddress, $billingAddress, $coupon = null)
    {
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => Order::generateOrderNumber(),
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_STATUS_PENDING,
            'currency' => 'ARS',
            'shipping_address' => $shippingAddress,
            'billing_address' => $billingAddress,
        ]);

        // Agregar items a la orden
        foreach ($this->items as $cartItem) {
            $product = $cartItem->product;
            $unitPrice = $product->price;
            
            // Aplicar ajustes de variantes
            if ($cartItem->selected_variants) {
                foreach ($cartItem->selected_variants as $variantType => $variantValue) {
                    $variant = $product->variants()
                        ->where('name', $variantType)
                        ->where('value', $variantValue)
                        ->first();
                    
                    if ($variant) {
                        $unitPrice += $variant->price_adjustment;
                    }
                }
            }

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'quantity' => $cartItem->quantity,
                'unit_price' => $unitPrice,
                'total_price' => $unitPrice * $cartItem->quantity,
                'product_variants' => $cartItem->selected_variants,
            ]);

            // Reducir stock
            $product->reduceStock($cartItem->quantity);
        }

        // Calcular totales
        $order->calculateTotals();

        // Aplicar cupón si existe
        if ($coupon) {
            $discount = $this->calculateCouponDiscount($coupon);
            $order->update(['discount_amount' => $discount]);
            $order->calculateTotals();
        }

        // Limpiar carrito
        $this->clear();

        return $order;
    }

    /**
     * Calcular descuento de cupón
     */
    protected function calculateCouponDiscount($coupon)
    {
        if ($coupon->type === 'percentage') {
            return ($this->total * $coupon->value) / 100;
        } else {
            return $coupon->value;
        }
    }
} 