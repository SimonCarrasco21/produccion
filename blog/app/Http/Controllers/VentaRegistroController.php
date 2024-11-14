<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Importar el facade de DomPDF

class VentaRegistroController extends Controller
{
    public function mostrarRegistroVentas(Request $request)
    {
        // Filtro de búsqueda por fecha
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $ventasQuery = DB::table('ventas')->where('user_id', Auth::id());

        if ($fechaInicio && $fechaFin) {
            $fechaInicio = \Carbon\Carbon::parse($fechaInicio)->startOfDay();
            $fechaFin = \Carbon\Carbon::parse($fechaFin)->endOfDay();
            $ventasQuery->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }

        $ventas = $ventasQuery->get();

        // Calcular las ganancias totales
        $totalGanancias = $ventas->sum('amount');

        // Obtener los registros guardados
        $registrosReporte = DB::table('registros_reporte')->where('user_id', Auth::id())->get();

        return view('registro-ventas', compact('ventas', 'totalGanancias', 'fechaInicio', 'fechaFin', 'registrosReporte'));
    }

    public function generarPdfVentas(Request $request)
    {
        // Obtener el rango de fechas del filtro
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $ventasQuery = DB::table('ventas')->where('user_id', Auth::id());

        if ($fechaInicio && $fechaFin) {
            $fechaInicio = \Carbon\Carbon::parse($fechaInicio)->startOfDay();
            $fechaFin = \Carbon\Carbon::parse($fechaFin)->endOfDay();
            $ventasQuery->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }

        $ventas = $ventasQuery->get();

        // Calcular las ganancias totales
        $totalGanancias = $ventas->sum('amount');

        // Generar la vista HTML que se convertirá en PDF usando la vista que ya existe
        $pdf = Pdf::loadView('registro-ventas-pdf', ['ventas' => $ventas, 'totalGanancias' => $totalGanancias]);

        // Descargar el archivo PDF
        return $pdf->download('reporte_ventas.pdf');
    }

    public function guardarRegistroVentas(Request $request)
    {
        // Obtener el rango de fechas del filtro
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $ventasQuery = DB::table('ventas')->where('user_id', Auth::id());

        if ($fechaInicio && $fechaFin) {
            $fechaInicio = \Carbon\Carbon::parse($fechaInicio)->startOfDay();
            $fechaFin = \Carbon\Carbon::parse($fechaFin)->endOfDay();
            $ventasQuery->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }

        $ventas = $ventasQuery->get();
        $totalGanancias = $ventas->sum('amount');

        // Verificar si hay ventas para guardar
        if ($ventas->isEmpty()) {
            return redirect()->route('ventas.historial')->with('error', 'No hay ventas para guardar en este rango de fechas.');
        }

        // Guardar un registro del reporte generado en la tabla 'registros_reporte'
        $registroId = DB::table('registros_reporte')->insertGetId([
            'user_id' => Auth::id(),
            'fecha_generacion' => now(),
            'total_ganancias' => $totalGanancias,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Guardar el PDF en el almacenamiento después de guardar el registro
        $pdf = Pdf::loadView('registro-ventas-pdf', ['ventas' => $ventas, 'totalGanancias' => $totalGanancias]);
        $pdf->save(storage_path("app/public/reportes/reporte_ventas_{$registroId}.pdf"));

        // Eliminar las ventas después de generar y guardar el reporte
        $deletedRows = DB::table('ventas')
            ->where('user_id', Auth::id())
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->delete();

        // Verificar si las ventas fueron eliminadas correctamente
        if ($deletedRows > 0) {
            return redirect()->route('ventas.historial')->with('success', 'Registro de ventas guardado y ventas eliminadas correctamente.');
        } else {
            return redirect()->route('ventas.historial')->with('warning', 'Registro guardado, pero no se pudieron eliminar las ventas. Por favor, verifica.');
        }
    }

    public function descargarRegistro($id)
    {
        $registro = DB::table('registros_reporte')->where('id', $id)->where('user_id', Auth::id())->first();

        if ($registro) {
            $filePath = storage_path("app/public/reportes/reporte_ventas_{$registro->id}.pdf");
            if (file_exists($filePath)) {
                return response()->download($filePath);
            }
        }

        return redirect()->route('ventas.historial')->with('error', 'El registro solicitado no existe o no se pudo encontrar el archivo.');
    }

    public function eliminarRegistro($id)
    {
        // Eliminar el registro del reporte por ID
        DB::table('registros_reporte')->where('id', $id)->where('user_id', Auth::id())->delete();

        // También eliminar el archivo PDF correspondiente
        $filePath = storage_path("app/public/reportes/reporte_ventas_{$id}.pdf");
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        return redirect()->route('ventas.historial')->with('success', 'Registro de ventas eliminado correctamente.');
    }
}
