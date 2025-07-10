<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total',
        'currency',
        'notes',
        'shipping_address',
        'billing_address',
        'payment_method',
        'payment_status',
        'mercadopago_payment_id',
        'mercadopago_preference_id',
        'paid_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Estados de orden
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    /**
     * Estados de pago
     */
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';

    /**
     * Relación con usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con items de la orden
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relación con reseñas de la orden
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Scope para órdenes pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope para órdenes procesando
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    /**
     * Scope para órdenes enviadas
     */
    public function scopeShipped($query)
    {
        return $query->where('status', self::STATUS_SHIPPED);
    }

    /**
     * Scope para órdenes entregadas
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    /**
     * Scope para órdenes canceladas
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope para pagos pendientes
     */
    public function scopePaymentPending($query)
    {
        return $query->where('payment_status', self::PAYMENT_STATUS_PENDING);
    }

    /**
     * Scope para pagos completados
     */
    public function scopePaymentPaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_STATUS_PAID);
    }

    /**
     * Generar número de orden único
     */
    public static function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Obtener total formateado
     */
    public function getFormattedTotalAttribute()
    {
        return '$' . number_format($this->total / 100, 2, ',', '.');
    }

    /**
     * Obtener subtotal formateado
     */
    public function getFormattedSubtotalAttribute()
    {
        return '$' . number_format($this->subtotal / 100, 2, ',', '.');
    }

    /**
     * Obtener descuento formateado
     */
    public function getFormattedDiscountAttribute()
    {
        return '$' . number_format($this->discount_amount / 100, 2, ',', '.');
    }

    /**
     * Obtener envío formateado
     */
    public function getFormattedShippingAttribute()
    {
        return '$' . number_format($this->shipping_amount / 100, 2, ',', '.');
    }

    /**
     * Obtener impuestos formateados
     */
    public function getFormattedTaxAttribute()
    {
        return '$' . number_format($this->tax_amount / 100, 2, ',', '.');
    }

    /**
     * Obtener estado formateado
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_PROCESSING => 'Procesando',
            self::STATUS_SHIPPED => 'Enviado',
            self::STATUS_DELIVERED => 'Entregado',
            self::STATUS_CANCELLED => 'Cancelado',
            self::STATUS_REFUNDED => 'Reembolsado',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Obtener estado de pago formateado
     */
    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            self::PAYMENT_STATUS_PENDING => 'Pendiente',
            self::PAYMENT_STATUS_PAID => 'Pagado',
            self::PAYMENT_STATUS_FAILED => 'Fallido',
            self::PAYMENT_STATUS_REFUNDED => 'Reembolsado',
        ];

        return $labels[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Obtener clase CSS para el estado
     */
    public function getStatusClassAttribute()
    {
        $classes = [
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_PROCESSING => 'badge-info',
            self::STATUS_SHIPPED => 'badge-primary',
            self::STATUS_DELIVERED => 'badge-success',
            self::STATUS_CANCELLED => 'badge-error',
            self::STATUS_REFUNDED => 'badge-secondary',
        ];

        return $classes[$this->status] ?? 'badge-secondary';
    }

    /**
     * Obtener clase CSS para el estado de pago
     */
    public function getPaymentStatusClassAttribute()
    {
        $classes = [
            self::PAYMENT_STATUS_PENDING => 'badge-warning',
            self::PAYMENT_STATUS_PAID => 'badge-success',
            self::PAYMENT_STATUS_FAILED => 'badge-error',
            self::PAYMENT_STATUS_REFUNDED => 'badge-secondary',
        ];

        return $classes[$this->payment_status] ?? 'badge-secondary';
    }

    /**
     * Verificar si está pagada
     */
    public function getIsPaidAttribute()
    {
        return $this->payment_status === self::PAYMENT_STATUS_PAID;
    }

    /**
     * Verificar si está enviada
     */
    public function getIsShippedAttribute()
    {
        return in_array($this->status, [self::STATUS_SHIPPED, self::STATUS_DELIVERED]);
    }

    /**
     * Verificar si está entregada
     */
    public function getIsDeliveredAttribute()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Verificar si está cancelada
     */
    public function getIsCancelledAttribute()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Calcular total de items
     */
    public function calculateTotals()
    {
        $subtotal = $this->items->sum(function ($item) {
            return $item->total_price;
        });

        $this->update([
            'subtotal' => $subtotal,
            'total' => $subtotal + $this->tax_amount + $this->shipping_amount - $this->discount_amount,
        ]);
    }

    /**
     * Marcar como pagada
     */
    public function markAsPaid()
    {
        $this->update([
            'payment_status' => self::PAYMENT_STATUS_PAID,
            'paid_at' => now(),
        ]);
    }

    /**
     * Marcar como enviada
     */
    public function markAsShipped()
    {
        $this->update([
            'status' => self::STATUS_SHIPPED,
            'shipped_at' => now(),
        ]);
    }

    /**
     * Marcar como entregada
     */
    public function markAsDelivered()
    {
        $this->update([
            'status' => self::STATUS_DELIVERED,
            'delivered_at' => now(),
        ]);
    }

    /**
     * Cancelar orden
     */
    public function cancel()
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
        ]);

        // Restaurar stock
        foreach ($this->items as $item) {
            $item->product->increaseStock($item->quantity);
        }
    }
} 