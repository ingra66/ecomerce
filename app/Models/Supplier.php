<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nick',
        'nombre',
        'email',
        'telefono',
        'direccion',
        'web',
        'notas',
    ];

    /**
     * Relación muchos a muchos con productos
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_supplier')
            ->withPivot('enlace')
            ->withTimestamps();
    }
}
