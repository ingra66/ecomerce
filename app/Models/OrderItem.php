<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'unit_price',
        'total_price',
        'product_variants',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'product_variants' => 'array',
    ];

    /**
     * Relación con orden
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación con producto
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
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
     * Obtener variantes como texto
     */
    public function getVariantsTextAttribute()
    {
        if (!$this->product_variants) {
            return '';
        }

        $variants = [];
        foreach ($this->product_variants as $type => $value) {
            $variants[] = ucfirst($type) . ': ' . $value;
        }

        return implode(', ', $variants);
    }
} 