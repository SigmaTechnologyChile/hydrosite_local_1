<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\Ingreso;
use App\Models\Egreso;
use Carbon\Carbon;

class LibroCajaController extends Controller
{
    public function index(): View
    {
        return view('orgs.contable.libro-caja', [
            'title' => 'Libro de Caja Tabular'
        ]);
    }

    public function obtenerDatos(Request $request): JsonResponse
    {
        try {
            $fechaDesde = $request->get('fecha_desde', Carbon::now()->startOfMonth());
            $fechaHasta = $request->get('fecha_hasta', Carbon::now()->endOfMonth());

            $movimientos = collect();

            // Obtener ingresos
            $ingresos = Ingreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])
                              ->orderBy('fecha', 'asc')
                              ->get()
                              ->map(function ($ingreso) {
                                  return [
                                      'fecha' => $ingreso->fecha,
                                      'detalle' => $ingreso->detalle,
                                      'debe' => $ingreso->monto,
                                      'haber' => 0,
                                      'tipo' => 'ingreso',
                                      'id' => $ingreso->id
                                  ];
                              });

            // Obtener egresos
            $egresos = Egreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])
                            ->orderBy('fecha', 'asc')
                            ->get()
                            ->map(function ($egreso) {
                                return [
                                    'fecha' => $egreso->fecha,
                                    'detalle' => $egreso->detalle,
                                    'debe' => 0,
                                    'haber' => $egreso->monto,
                                    'tipo' => 'egreso',
                                    'id' => $egreso->id
                                ];
                            });

            // Combinar y ordenar
            $movimientos = $ingresos->concat($egresos)
                                  ->sortBy('fecha')
                                  ->values();

            // Calcular saldos acumulados
            $saldoAcumulado = 0;
            $movimientos = $movimientos->map(function ($movimiento) use (&$saldoAcumulado) {
                $saldoAcumulado += ($movimiento['debe'] - $movimiento['haber']);
                $movimiento['saldo'] = $saldoAcumulado;
                return $movimiento;
            });

            return response()->json([
                'success' => true,
                'movimientos' => $movimientos,
                'resumen' => [
                    'total_debe' => $movimientos->sum('debe'),
                    'total_haber' => $movimientos->sum('haber'),
                    'saldo_final' => $saldoAcumulado
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function filtrar(Request $request): JsonResponse
    {
        return $this->obtenerDatos($request);
    }

    public function exportarExcel(Request $request)
    {
        // Implementar exportación a Excel
    }

    public function exportarPDF(Request $request)
    {
        // Implementar exportación a PDF
    }

    public function estadisticas(Request $request): JsonResponse
    {
        try {
            $fechaDesde = $request->get('fecha_desde', Carbon::now()->startOfMonth());
            $fechaHasta = $request->get('fecha_hasta', Carbon::now()->endOfMonth());

            $totalIngresos = Ingreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])->sum('monto');
            $totalEgresos = Egreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])->sum('monto');
            $cantidadMovimientos = Ingreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])->count() +
                                 Egreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])->count();

            return response()->json([
                'success' => true,
                'estadisticas' => [
                    'total_ingresos' => $totalIngresos,
                    'total_egresos' => $totalEgresos,
                    'saldo_neto' => $totalIngresos - $totalEgresos,
                    'cantidad_movimientos' => $cantidadMovimientos,
                    'promedio_ingreso' => $cantidadMovimientos > 0 ? $totalIngresos / $cantidadMovimientos : 0,
                    'promedio_egreso' => $cantidadMovimientos > 0 ? $totalEgresos / $cantidadMovimientos : 0
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
