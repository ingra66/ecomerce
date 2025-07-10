<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'value',
        'price_adjustment',
        'stock',
        'sku',
        'is_active',
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relación con producto
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope para variantes activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obtener precio ajustado formateado
     */
    public function getFormattedPriceAdjustmentAttribute()
    {
        if ($this->price_adjustment > 0) {
            return '+$' . number_format($this->price_adjustment / 100, 2, ',', '.');
        } elseif ($this->price_adjustment < 0) {
            return '-$' . number_format(abs($this->price_adjustment) / 100, 2, ',', '.');
        }
        return '';
    }

    /**
     * Verificar si está en stock
     */
    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }

    /**
     * Obtener stock bajo
     */
    public function getLowStockAttribute()
    {
        return $this->stock <= 5 && $this->stock > 0;
    }
} 