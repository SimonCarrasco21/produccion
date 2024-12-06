<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaginaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FiadoController;
use App\Http\Controllers\PagoPosController;
use App\Http\Controllers\VentaRegistroController;

// Redirigir la raíz del sitio al login
Route::get('/', fn() => redirect('/login'));

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'mostrarDashboard'])->name('dashboard');

    // Perfil del usuario
    Route::get('/perfil', [ProfileController::class, 'show'])->name('perfil');
    Route::post('/perfil', [ProfileController::class, 'updateProfile'])->name('perfil.update');
    Route::post('/perfil/cambiar-contrasena', [ProfileController::class, 'updatePassword'])->name('perfil.cambiar-contrasena');
    Route::post('/perfil/actualizar-foto', [ProfileController::class, 'updateProfilePicture'])->name('perfil.actualizar-foto'); // Nueva ruta para foto

    // Inventario y productos
    Route::get('/inventario', [DashboardController::class, 'mostrarInventario'])->name('inventario');
    Route::post('/inventario/eliminar', [DashboardController::class, 'eliminarProductosSeleccionados'])->name('inventario.eliminar');
    Route::get('/productos/categoria/{id}', [PaginaController::class, 'mostrarProductosPorCategoria'])->name('productos.categoria');
    Route::post('/eliminar-productos-seleccionados', [PaginaController::class, 'eliminarProductosSeleccionados'])->name('eliminarProductosSeleccionados');
    Route::get('/agregar-producto', [PaginaController::class, 'mostrarPagina'])->name('agregar-producto');
    Route::post('/guardar-producto', [PaginaController::class, 'guardarProducto'])->name('guardarProducto');
    Route::delete('/eliminar-producto/{id}', [PaginaController::class, 'eliminarProducto'])->name('eliminarProducto');
    Route::get('/editar-producto/{id}', [PaginaController::class, 'editarProducto'])->name('editarProducto');
    Route::put('/actualizar-producto/{id}', [PaginaController::class, 'actualizarProducto'])->name('actualizarProducto');
    Route::post('/guardar-producto-unico', [PaginaController::class, 'guardarProductoUnico'])->name('guardarProductoUnico')->middleware('web');



    // Alertas y reportes
    Route::get('/productos-por-categoria', [PaginaController::class, 'productosPorCategoria'])->name('productos.por.categoria');
    Route::get('/dashboard/productos-por-vencer', [DashboardController::class, 'productosPorVencer'])->name('dashboard.productos-por-vencer');
    Route::get('/dashboard/productos-stock-bajo', [DashboardController::class, 'productosConStockBajo'])->name('dashboard.productos-stock-bajo');
    Route::get('/dashboard/productos-vencidos', [DashboardController::class, 'obtenerProductosVencidos']);

    Route::get('/inventario/graficos', [DashboardController::class, 'datosGraficos'])->name('inventario.graficos');

    // Fiados
    Route::get('/fiados', [FiadoController::class, 'index'])->name('fiados.index');
    Route::post('/fiados', [FiadoController::class, 'store'])->name('fiados.store');
    Route::delete('/fiados/{id}', [FiadoController::class, 'destroy'])->name('fiados.destroy');
    Route::get('/pago/fiado/{id}', [PagoPosController::class, 'pagoFiado'])->name('pago.fiado');

    // Pagos
    Route::get('/pago', [PagoPosController::class, 'mostrarVistaPago'])->name('pagina.pago');
    Route::post('/pago-pos', [PagoPosController::class, 'procesarPagoPos'])->name('payments.pay.pos');
    Route::post('/procesar-datos', [PagoPosController::class, 'procesarDatos']);
    Route::post('/pago-efectivo', [PagoPosController::class, 'pagarEnEfectivo'])->name('pago.efectivo');
    Route::get('/pagar/buscar-productos', [PagoPosController::class, 'buscarProductos'])->name('pagar.buscar-productos');

    // Registro de ventas
    Route::get('/registro-ventas', [VentaRegistroController::class, 'mostrarRegistroVentas'])->name('registro-ventas');
    Route::get('/ventas', [VentaRegistroController::class, 'mostrarRegistroVentas'])->name('ventas.historial');
    Route::post('/ventas/imprimir', [VentaRegistroController::class, 'generarPdfVentas'])->name('ventas.imprimir');
    Route::post('/ventas/guardar', [VentaRegistroController::class, 'guardarRegistroVentas'])->name('ventas.guardar');
    Route::delete('/ventas/eliminar/{id}', [VentaRegistroController::class, 'eliminarRegistro'])->name('ventas.reporte.eliminar');
    Route::get('/ventas/descargar/{id}', [VentaRegistroController::class, 'descargarRegistro'])->name('ventas.reporte.descargar');
    Route::post('/ventas/eliminar', [VentaRegistroController::class, 'eliminarVentas'])->name('ventas.eliminar');
});

// Incluir las rutas de autenticación generadas por Breeze
require __DIR__ . '/auth.php';
