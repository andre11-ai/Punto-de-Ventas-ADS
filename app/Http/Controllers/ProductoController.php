<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Proveedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class ProductoController
 * @package App\Http\Controllers
 */
class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index()
{
    $productos = Producto::all();
    return view('producto.index', compact('productos'));
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
public function create()
{
    $producto = new Producto();
    $producto->codigo = '';
    $producto->codigo_barras = '';
    $producto->id_proveedor = null;

    $categorias = Categoria::with('proveedor')
                  ->whereNotNull('upc')
                  ->get()
                  ->mapWithKeys(function ($categoria) {
                      return [$categoria->id => $categoria->full_name];
                  });

    return view('producto.create', compact('producto', 'categorias'));
}
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'producto' => 'required|string|max:255',
        'codigo' => 'required|string|size:13|unique:productos,codigo', // Cambiado a 13 dígitos
        'precio_compra' => 'required|numeric|min:0',
        'precio_venta' => 'required|numeric|min:0|gt:precio_compra',
        'id_categoria' => 'required|exists:categorias,id',
        'id_proveedor' => 'required|exists:proveedores,id',
        'codigo_barras' => 'required|string|size:13|unique:productos,codigo_barras',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Crear producto (ya no necesitamos generar EAN-13)
    $producto = Producto::create($validated);

    // Manejo de imagen
    if ($request->hasFile('foto')) {
        $producto->foto = $request->file('foto')->store('productos', 'public');
        $producto->save();
    }

    return redirect()->route('productos.index')
           ->with('success', "Producto {$producto->producto} creado.");
}

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = Producto::find($id);

        return view('producto.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
   public function edit($id)
{
    $producto = Producto::with(['categoria.proveedor'])->findOrFail($id);

    $categorias = Categoria::with('proveedor')
                  ->whereNotNull('upc')
                  ->get()
                  ->pluck('full_name', 'id');

    return view('producto.edit', compact('producto', 'categorias'));
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Producto $producto
     * @return \Illuminate\Http\Response
     */
   public function update(Request $request, Producto $producto)
{
    $validated = $request->validate([
        'producto' => 'required|string|max:255',
        'codigo' => 'required|string|max:13|unique:productos,codigo,'.$producto->id,
        'precio_compra' => 'required|numeric|min:0',
        'precio_venta' => 'required|numeric|min:0|gt:precio_compra',
        'id_categoria' => 'required|exists:categorias,id',
        'id_proveedor' => 'required|exists:proveedores,id',
        'codigo_barras' => 'required|string|size:13|unique:productos,codigo_barras,'.$producto->id,
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Actualizar campos
    $producto->update($validated);

    // Manejo de imagen
    if ($request->hasFile('foto')) {
        // Eliminar imagen anterior si existe
        if ($producto->foto) {
            Storage::disk('public')->delete($producto->foto);
        }
        $producto->foto = $request->file('foto')->store('productos', 'public');
        $producto->save();
    }

    return redirect()->route('productos.index')
           ->with('success', "Producto actualizado.");
}

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
public function destroy($id)
{
    $producto = Producto::findOrFail($id);
    $producto->delete();

    return response()->json(['success' => true]);
}


public function list()
{
    $productos = Producto::with(['categoria', 'proveedor'])->get();

    $data = $productos->map(function ($producto) {
        return [
            'id' => $producto->id,
            'codigo' => $producto->codigo,
            'producto' => $producto->producto,
            'precio_compra' => $producto->precio_compra,
            'precio_venta' => $producto->precio_venta,
            'categoria' => $producto->categoria ? ['nombre' => $producto->categoria->nombre] : null,
            'proveedor' => $producto->proveedor ? ['nombre' => $producto->proveedor->nombre] : null,
            'foto' => $producto->foto,
            'codigo_barras' => $producto->codigo_barras,
        ];
    });

    return response()->json(['data' => $data]);
}

public function getCategoriaInfo($id)
{
    try {
        $categoria = Categoria::with('proveedor')->findOrFail($id);

        $totalProductos = Producto::where('id_categoria', $id)->count();
        $correlativo = str_pad($totalProductos + 1, 2, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'data' => [
                'proveedor' => [
                    'id' => $categoria->proveedor->id,
                    'nombre' => $categoria->proveedor->nombre
                ],
                'upc_completo' => $categoria->upc . $correlativo,
                'total_productos' => $totalProductos
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Error al cargar datos de categoría',
            'details' => env('APP_DEBUG') ? $e->getMessage() : null
        ], 500);
    }
}

/**
 * Genera un código EAN-13 válido
 */
private function generateEAN13($upc)
{
    // Asegurarse que el código tenga 13 dígitos
    return str_pad($upc, 13, '0', STR_PAD_LEFT);
}
}

