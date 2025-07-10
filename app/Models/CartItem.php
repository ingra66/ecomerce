<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
        'selected_variants',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'selected_variants' => 'array',
    ];

    /**
     * Relación con carrito
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Relación con producto
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Obtener precio unitario con variantes
     */
    public function getUnitPriceAttribute()
    {
        $price = $this->product->price;
        
        if ($this->selected_variants) {
            foreach ($this->selected_variants as $variantType => $variantValue) {
                $variant = $this->product->variants()
                    ->where('name', $variantType)
                    ->where('value', $variantValue)
                    ->first();
                
                if ($variant) {
                    $price += $variant->price_adjustment;
                }
            }
        }
        
        return $price;
    }

    /**
     * Obtener precio total del item
     */
    public function getTotalPriceAttribute()
    {
        return $this->unit_price * $this->quantity;
    }

    /**
     * Obtener precio unitario formateado
     */
    public function getFormattedUnitPriceAttribute()
    {
        return '$' . number_format($this->unit_price / 100, 2, ',', '.');
    }

    /**
     * Obtener precio total formateado
     */
    public function getFormattedTotalPriceAttribute()
    {
        return '$' . number_format($this->total_price / 100, 2, ',', '.');
    }

    /**
     * Obtener variantes seleccionadas como texto
     */
    public function getSelectedVariantsTextAttribute()
    {
        if (!$this->selected_variants) {
            return '';
        }

        $variants = [];
        foreach ($this->selected_variants as $type => $value) {
            $variants[] = ucfirst($type) . ': ' . $value;
        }

        return implode(', ', $variants);
    }
} 