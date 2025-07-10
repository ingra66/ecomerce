<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'max_uses',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'max_uses' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
        'starts_at' => 'date',
        'expires_at' => 'date',
    ];

    /**
     * Tipos de cupón
     */
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';

    /**
     * Scope para cupones activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para cupones válidos por fecha
     */
    public function scopeValidByDate($query)
    {
        $now = Carbon::now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('starts_at')
              ->orWhere('starts_at', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>=', $now);
        });
    }

    /**
     * Scope para cupones con usos disponibles
     */
    public function scopeWithUsesAvailable($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('max_uses')
              ->orWhereRaw('used_count < max_uses');
        });
    }

    /**
     * Buscar cupón por código
     */
    public static function findByCode($code)
    {
        return self::active()
            ->validByDate()
            ->withUsesAvailable()
            ->where('code', strtoupper($code))
            ->first();
    }

    /**
     * Verificar si el cupón es válido
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        // Verificar fechas
        if ($this->starts_at && $this->starts_at->gt($now)) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->lt($now)) {
            return false;
        }

        // Verificar usos máximos
        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Verificar si el cupón es válido para un monto
     */
    public function isValidForAmount($amount)
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->minimum_amount && $amount < $this->minimum_amount) {
            return false;
        }

        return true;
    }

    /**
     * Calcular descuento para un monto
     */
    public function calculateDiscount($amount)
    {
        if (!$this->isValidForAmount($amount)) {
            return 0;
        }

        if ($this->type === self::TYPE_PERCENTAGE) {
            return ($amount * $this->value) / 100;
        } else {
            return $this->value;
        }
    }

    /**
     * Obtener descuento formateado
     */
    public function getFormattedDiscountAttribute()
    {
        if ($this->type === self::TYPE_PERCENTAGE) {
            return $this->value . '%';
        } else {
            return '$' . number_format($this->value / 100, 2, ',', '.');
        }
    }

    /**
     * Obtener monto mínimo formateado
     */
    public function getFormattedMinimumAmountAttribute()
    {
        if ($this->minimum_amount) {
            return '$' . number_format($this->minimum_amount / 100, 2, ',', '.');
        }
        return 'Sin mínimo';
    }

    /**
     * Incrementar contador de usos
     */
    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    /**
     * Verificar si está expirado
     */
    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->lt(Carbon::now());
    }

    /**
     * Verificar si aún no ha comenzado
     */
    public function getIsNotStartedAttribute()
    {
        return $this->starts_at && $this->starts_at->gt(Carbon::now());
    }

    /**
     * Verificar si se agotaron los usos
     */
    public function getIsExhaustedAttribute()
    {
        return $this->max_uses && $this->used_count >= $this->max_uses;
    }

    /**
     * Obtener usos restantes
     */
    public function getRemainingUsesAttribute()
    {
        if (!$this->max_uses) {
            return null; // Sin límite
        }
        return max(0, $this->max_uses - $this->used_count);
    }
} 