<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaRegistroController extends Controller
{
    public function mostrarRegistroVentas(Request $request)
    {
        // Capturar las fechas desde el formulario
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        // Consulta a la base de datos con filtros dinámicos
        $ventas = DB::table('ventas')
            ->where('user_id', Auth::id())
            ->when($fechaInicio, function ($query) use ($fechaInicio) {
                return $query->where('created_at', '>=', \Carbon\Carbon::parse($fechaInicio)->startOfDay());
            })
            ->when($fechaFin, function ($query) use ($fechaFin) {
                return $query->where('created_at', '<=', \Carbon\Carbon::parse($fechaFin)->endOfDay());
            })
            ->get();

        // Calcular las ganancias totales
        $totalGanancias = $ventas->sum('amount');

        // Obtener los registros guardados
        $registrosReporte = DB::table('registros_reporte')->where('user_id', Auth::id())->get();

        // Retornar los datos a la vista
        return view('registro-ventas', compact('ventas', 'totalGanancias', 'fechaInicio', 'fechaFin', 'registrosReporte'));
    }



    public function generarPdfVentas(Request $request)
    {
        // Obtener el rango de fechas del filtro
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $ventasQuery = DB::table('ventas')->where('user_id', Auth::id());

        if ($fechaInicio && $fechaFin) {
            try {
                $fechaInicio = \Carbon\Carbon::parse($fechaInicio)->startOfDay();
                $fechaFin = \Carbon\Carbon::parse($fechaFin)->endOfDay();
                $ventasQuery->whereBetween('created_at', [$fechaInicio, $fechaFin]);
            } catch (\Exception $e) {
                return redirect()->route('ventas.historial')
                    ->with('error', 'Formato de fecha inválido. Por favor, selecciona fechas válidas.');
            }
        }

        $ventas = $ventasQuery->get();
        $totalGanancias = $ventas->sum('amount');

        $pdf = Pdf::loadView('registro-ventas-pdf', ['ventas' => $ventas, 'totalGanancias' => $totalGanancias]);
        return $pdf->download('reporte_ventas.pdf');
    }

    public function guardarRegistroVentas(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $ventasQuery = DB::table('ventas')->where('user_id', Auth::id());

        if ($fechaInicio && $fechaFin) {
            try {
                $fechaInicio = \Carbon\Carbon::parse($fechaInicio)->startOfDay();
                $fechaFin = \Carbon\Carbon::parse($fechaFin)->endOfDay();
                $ventasQuery->whereBetween('created_at', [$fechaInicio, $fechaFin]);
            } catch (\Exception $e) {
                return redirect()->route('ventas.historial')
                    ->with('error', 'Formato de fecha inválido. Por favor, selecciona fechas válidas.');
            }
        }

        $ventas = $ventasQuery->get();
        $totalGanancias = $ventas->sum('amount');

        if ($ventas->isEmpty()) {
            return redirect()->route('ventas.historial')->with('error', 'No hay ventas para guardar en este rango de fechas.');
        }

        $registroId = DB::table('registros_reporte')->insertGetId([
            'user_id' => Auth::id(),
            'fecha_generacion' => now(),
            'total_ganancias' => $totalGanancias,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $pdf = Pdf::loadView('registro-ventas-pdf', ['ventas' => $ventas, 'totalGanancias' => $totalGanancias]);
        $pdf->save(storage_path("app/public/reportes/reporte_ventas_{$registroId}.pdf"));

        return redirect()->route('ventas.historial')->with('success', 'Registro de ventas guardado correctamente.');
    }

    public function eliminarVentas(Request $request)
    {
        // Eliminar todas las ventas del usuario autenticado
        $deletedRows = DB::table('ventas')->where('user_id', Auth::id())->delete();

        if ($deletedRows > 0) {
            return redirect()->route('ventas.historial')->with('success', 'Todas las ventas fueron eliminadas correctamente.');
        } else {
            return redirect()->route('ventas.historial')->with('warning', 'No hay ventas para eliminar.');
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
        DB::table('registros_reporte')->where('id', $id)->where('user_id', Auth::id())->delete();

        $filePath = storage_path("app/public/reportes/reporte_ventas_{$id}.pdf");
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        return redirect()->route('ventas.historial')->with('success', 'Registro de ventas eliminado correctamente.');
    }
}
