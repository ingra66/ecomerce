<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOrSellerOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, ['administrador', 'vendedor'])) {
            abort(403, 'Acceso solo para administradores o vendedores.');
        }
        return $next($request);
    }
}
