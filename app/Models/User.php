<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Relación con carritos del usuario
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Relación con órdenes del usuario
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relación con reseñas del usuario
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relación con lista de deseos del usuario
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Obtener carrito activo del usuario
     */
    public function getActiveCartAttribute()
    {
        return $this->carts()->latest()->first();
    }

    /**
     * Obtener órdenes recientes
     */
    public function getRecentOrdersAttribute()
    {
        return $this->orders()->latest()->take(5)->get();
    }

    /**
     * Métodos helper para roles
     */
    public function isAdmin(): bool
    {
        return $this->role === 'administrador';
    }
    public function isSeller(): bool
    {
        return $this->role === 'vendedor';
    }
    public function isCustomer(): bool
    {
        return $this->role === 'cliente';
    }
}
