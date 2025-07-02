<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CompaniaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\PromocionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\FacturaController;

Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');

Route::middleware(['auth', 'role:User,Admin,Super-Admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/listarVentas', [VentaController::class, 'listVentas'])->name('sales.list');
    Route::get('/venta/show', [VentaController::class, 'show'])->name('venta.show');
    Route::get('/venta', [VentaController::class, 'index'])->name('venta.index');
    Route::post('/venta', [VentaController::class, 'store'])->name('venta.store');
    Route::get('/venta/{id}/ticket', [VentaController::class, 'ticket'])->name('venta.ticket');
    Route::get('/ventas/ticket/{id}', [VentaController::class, 'mostrarTicket'])->name('ventas.ticket');
    Route::get('/ventas/ticket-abono/{id}', [VentaController::class, 'ticketAbono'])->name('ventas.ticket.abono');
    Route::get('/ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');
    Route::post('/ventas/registrar-abono/{clienteId}', [VentaController::class, 'registrarAbono'])->name('ventas.registrarAbono');

    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('/listarProductos', [DatatableController::class, 'products'])->name('products.list');
    Route::get('/productos/list', [ProductoController::class, 'list'])->name('products.list');
    Route::get('/products/list', [ProductoController::class, 'list'])->name('products.list');

    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/{id}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/list', [ClienteController::class, 'listarClientes'])->name('clients.list');

    Route::get('ventas/{venta}/devoluciones', [App\Http\Controllers\DevolucionController::class, 'historial'])->name('venta.devolucion.historial');
    Route::get('/devoluciones/todas', function () {
            $devoluciones = \App\Models\Devolucion::with(['detalles.producto', 'user'])->orderByDesc('created_at')->get();
            return response()->json($devoluciones);
        });
        Route::get('ventas/{venta}/detalles-json', function(App\Models\Venta $venta) {
            $venta->load('detalles.producto');
            return response()->json([
                'detalles' => $venta->detalles->map(function($d){
                    $p = $d->producto;
                    $sePuede = 'NO';
                    if ($p) {
                        $sePuede = $p->devolucion ? 'SI' : 'NO';
                    }
                    return [
                        'producto' => [
                            'id' => $p->id ?? null,
                            'nombre' => $p->nombre ?? $p->producto ?? 'Producto eliminado',
                        ],
                        'cantidad' => $d->cantidad,
                        'precio' => $d->precio,
                        'se_puede_devolver' => $sePuede,
                    ];
                }),
            ]);
        });

        Route::get('/devoluciones/{id}/ticket', [DevolucionController::class, 'ticket'])->name('devoluciones.ticket');
        Route::get('/devoluciones/{id}/ticket', [App\Http\Controllers\DevolucionController::class, 'ticket'])->name('devoluciones.ticket');
        Route::get('/ventas/{venta}/devoluciones', [DevolucionController::class, 'historial'])->name('ventas.devolucion.historial');
        Route::post('ventas/{venta}/devolucion', [DevolucionController::class, 'store'])->name('venta.devolucion.store');

        Route::get('/export-productos-vendidos/{formato}', [DashboardController::class, 'exportProductosVendidos'])->name('export.productos.vendidos');
        Route::get('/export-ventas-dia/{formato}', [DashboardController::class, 'exportVentasDia'])->name('export.ventas.dia');
        Route::get('/export-ventas-semana/{formato}', [DashboardController::class, 'exportVentasSemana'])->name('export.ventas.semana');
        Route::get('/export-ventas-mes/{formato}', [DashboardController::class, 'exportVentasMes'])->name('export.ventas.mes');

        Route::post('/venta', [VentaController::class, 'procesarVenta'])->name('venta.store');
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::get('/clientes/{id}', [ClienteController::class, 'show'])->name('clientes.show');
        Route::get('/clientes/list', [ClienteController::class, 'listarClientes'])->name('clients.list');
        Route::post('/guardar-deudor', [ClienteController::class, 'guardarDeudor'])->name('guardar.deudor');
        Route::post('/clientes/{id}/abonar', [ClienteController::class, 'registrarAbono']);
        Route::get('/clientes/{id}/abono-ticket', [ClienteController::class, 'ticketAbono'])->name('generar.ticket.abono');

        Route::get('/venta/{cliente}/registrar-abono-final/{monto}', [VentaController::class, 'registrarAbonoFinal']);
        Route::get('/ventas/ticket/{id}', [VentaController::class, 'mostrarTicket'])->name('ventas.ticket');
        Route::post('/ventas/registrar-abono/{clienteId}', [App\Http\Controllers\VentaController::class, 'registrarAbono'])->name('ventas.registrarAbono');
        Route::get('/ventas/ticket-abono/{id}', [VentaController::class, 'ticketAbono'])->name('ventas.ticket.abono');
});

Route::middleware(['auth', 'role:Admin,Super-Admin'])->group(function () {

    Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{id}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');


    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/listarUsuarios', [DatatableController::class, 'users'])->name('users.list');
    Route::get('usuarios/list', [UsuarioController::class, 'list'])->name('usuarios.list');

    Route::resource('categorias', CategoriaController::class);
    Route::get('/api/categoria-info/{id}', [CategoriaController::class, 'getCategoriaInfo'])->name('api.categoria.info');
    Route::get('/proveedores-por-categoria/{id}', [CategoriaController::class, 'proveedoresPorCategoria']);
    Route::get('/listarCategorias', [DatatableController::class, 'categories'])->name('categories.list');


    Route::resource('proveedores', ProveedoresController::class);

    Route::get('/promociones', [ProductoController::class, 'index'])->name('promociones.index');
    Route::post('/promociones/guardar', [PromocionController::class, 'guardar'])->name('promociones.guardar');
    Route::get('/productos/{id}/edit-promocion', [ProductoController::class, 'editPromocion'])->name('productos.edit-promocion');
    Route::put('/productos/{id}/update-promocion', [ProductoController::class, 'updatePromocion'])->name('productos.update-promocion');
    Route::delete('/productos/{id}/remove-promocion', [ProductoController::class, 'removePromocion'])->name('productos.remove-promocion');
    Route::get('/promociones/categorias/{proveedor}',[PromocionController::class, 'getCategorias'])->name('promociones.categorias');
    Route::get('/promociones/verificar', [PromocionController::class, 'verificarExpiradas'])->name('promociones.verificar');
    Route::get('/promociones/proveedores', [PromocionController::class, 'getProveedores'])->name('promociones.proveedores');
    Route::get('/promociones/productos/{categoria}', [PromocionController::class, 'getProductos']);
    Route::get('/promociones/categorias/{proveedorId}', [PromocionController::class, 'getCategorias'])->name('promociones.categorias');

    Route::get('/compania', [CompaniaController::class, 'index'])->name('compania.index');
    Route::put('/compania/{compania}', [CompaniaController::class, 'update'])->name('compania.update');
});

Route::middleware(['auth', 'role:Super-Admin'])->group(function () {
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    Route::prefix('facturas')->group(function () {
        Route::get('/', [FacturaController::class, 'index'])->name('factura.index');
        Route::get('/crear/{ventaId}', [FacturaController::class, 'create'])->name('factura.create');
        Route::post('/guardar', [FacturaController::class, 'store'])->name('factura.store');
        Route::get('/{id}', [FacturaController::class, 'show'])->name('factura.show');
        Route::get('/{id}/pdf', [FacturaController::class, 'pdf'])->name('factura.pdf');
        Route::get('/{id}/ticket', [FacturaController::class, 'ticketFactura'])->name('factura.ticket');
        Route::delete('/{id}', [FacturaController::class, 'destroy'])->name('factura.destroy');
        Route::get('/all', [FacturaController::class, 'showAll'])->name('factura.showAll');
        Route::get('/{id}/pdffactura', [FacturaController::class, 'pdffactura'])->name('factura.pdffactura');

        Route::get('/factura/ticket/{id}', [FacturaController::class, 'ticketFactura'])->name('factura.ticketFactura');

    });
});

require __DIR__ . '/auth.php';
