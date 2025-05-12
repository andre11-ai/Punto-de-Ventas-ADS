<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Promocion;

class PromocionController extends Controller
{
     // En PromocionController.php
 public function guardar(Request $request)
    {
         $validated = $request->validate([
        'id_categoria' => [
            'nullable',
            'required_without_all:id_proveedor', // Solo requerido si no hay proveedor
            'exists:categorias,id'
        ],
        'id_proveedor' => [
            'nullable',
            'required_without_all:id_categoria', // Solo requerido si no hay categoría
            'exists:proveedores,id'
        ],
        'tipo_promocion' => [
            'required',
            'string',
            'not_in:ninguna'
        ]
    ], [
        'id_categoria.required_without_all' => 'Debes seleccionar al menos una categoría o un proveedor',
        'id_proveedor.required_without_all' => 'Debes seleccionar al menos una categoría o un proveedor',
        'tipo_promocion.not_in' => 'Debes seleccionar un tipo de promoción válido'
    ]);

        // Crear la promoción
        $promocion = Promocion::create([
            'tipo' => $request->tipo_promocion,
            'id_categoria' => $request->id_categoria,
            'id_proveedor' => $request->id_proveedor
        ]);

        // Dentro del método guardar()
if (empty($validated['id_categoria']) && empty($validated['id_proveedor'])) {
    return redirect()->back()
        ->withInput()
        ->withErrors([
            'id_categoria' => 'Debes seleccionar al menos una categoría o un proveedor',
            'id_proveedor' => 'Debes seleccionar al menos una categoría o un proveedor'
        ]);
}

if ($validated['tipo_promocion'] === 'ninguna') {
    return redirect()->back()
        ->withInput()
        ->withErrors([
            'tipo_promocion' => 'Debes seleccionar un tipo de promoción válido'
        ]);
}

        // Actualizar productos con la promoción
        $query = Producto::query();

        if ($request->id_categoria) {
            $query->where('id_categoria', $request->id_categoria);
        }

        if ($request->id_proveedor) {
            $query->where('id_proveedor', $request->id_proveedor);
        }

        $query->update(['promocion_id' => $promocion->id]);

        return redirect()->route('productos.index')
               ->with('success', 'Promoción aplicada correctamente.');
    }

    public function update(Request $request, $id)
{
    $promocion = Promocion::findOrFail($id);

    // Validar datos
    $request->validate([
        'tipo_promocion' => 'required|string|not_in:ninguna',
        'productos' => 'nullable|array',
    ]);

    $productosSeleccionados = $request->input('productos', []);
    $tipoPromocion = $request->input('tipo_promocion');

    // Actualizar tipo de promoción de la promoción
    $promocion->tipo = $tipoPromocion;
    $promocion->save();

    // Obtener todos los productos que tienen actualmente esta promoción
    $productosActuales = Producto::where('promocion_id', $promocion->id)->get();

    // Desasociar los productos que fueron desmarcados
    foreach ($productosActuales as $producto) {
        if (!in_array($producto->id, $productosSeleccionados)) {
            $producto->promocion_id = null;
            $producto->save();
        }
    }

    // Asociar los productos seleccionados
    foreach ($productosSeleccionados as $productoId) {
        $producto = Producto::find($productoId);
        if ($producto) {
            $producto->promocion_id = $promocion->id;
            $producto->save();
        }
    }

    return redirect()->route('productos.index')->with('success', 'Promoción actualizada correctamente');
}

}
