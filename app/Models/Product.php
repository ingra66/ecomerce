<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'compare_price',
        'stock',
        'sku',
        'barcode',
        'weight',
        'height',
        'width',
        'length',
        'is_active',
        'is_featured',
        'sort_order',
        'meta_data',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'stock' => 'integer',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'width' => 'decimal:2',
        'length' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'meta_data' => 'array',
    ];

    /**
     * Relación con categoría
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación con imágenes
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Relación con variantes
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Relación con reseñas
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relación con lista de deseos
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Imagen principal
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Scope para productos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para productos destacados
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope para productos en stock
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope para ordenar por sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Obtener precio formateado
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price / 100, 2, ',', '.');
    }

    /**
     * Obtener precio de comparación formateado
     */
    public function getFormattedComparePriceAttribute()
    {
        if ($this->compare_price) {
            return '$' . number_format($this->compare_price / 100, 2, ',', '.');
        }
        return null;
    }

    /**
     * Calcular descuento
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }

    /**
     * Verificar si tiene descuento
     */
    public function getHasDiscountAttribute()
    {
        return $this->compare_price && $this->compare_price > $this->price;
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

    /**
     * Obtener URL de la imagen principal
     */
    public function getPrimaryImageUrlAttribute()
    {
        $primaryImage = $this->primaryImage;
        if ($primaryImage) {
            return asset('storage/' . $primaryImage->image_path);
        }
        
        // Si no hay imagen principal, tomar la primera
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return asset('storage/' . $firstImage->image_path);
        }
        
        return asset('images/placeholder-product.jpg');
    }

    /**
     * Obtener todas las URLs de imágenes
     */
    public function getAllImagesUrlsAttribute()
    {
        return $this->images()->orderBy('sort_order', 'asc')->get()->map(function ($image) {
            return asset('storage/' . $image->image_path);
        });
    }

    /**
     * Obtener variantes agrupadas por tipo
     */
    public function getVariantsGroupedAttribute()
    {
        return $this->variants()
            ->where('is_active', true)
            ->get()
            ->groupBy('name');
    }

    /**
     * Obtener rating promedio
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()
            ->where('is_approved', true)
            ->avg('rating') ?? 0;
    }

    /**
     * Obtener cantidad de reseñas
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()
            ->where('is_approved', true)
            ->count();
    }

    /**
     * Verificar si el usuario lo tiene en lista de deseos
     */
    public function isInWishlist($userId = null)
    {
        if (!$userId) {
            $userId = auth()->id();
        }
        
        if (!$userId) {
            return false;
        }
        
        return $this->wishlists()->where('user_id', $userId)->exists();
    }

    /**
     * Reducir stock
     */
    public function reduceStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    /**
     * Aumentar stock
     */
    public function increaseStock($quantity)
    {
        $this->increment('stock', $quantity);
    }
} 