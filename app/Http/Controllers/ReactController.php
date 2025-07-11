<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReactController extends Controller
{
    /**
     * Servir la aplicación React para todas las rutas del frontend
     */
    public function index()
    {
        return view('app');
    }
} 