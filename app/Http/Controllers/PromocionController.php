<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromocionController extends Controller
{
    public function guardar(Request $request)
{
    // Aquí podrías guardar la promoción (validar, asociar productos, etc.)
    return back()->with('success', 'Promoción guardada correctamente');
}
}
