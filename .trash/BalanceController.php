<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\Ingreso;
use App\Models\Egreso;
use Carbon\Carbon;

class BalanceController extends Controller
{
    public function index(): View
    {
        return view('orgs.contable.balance', [
            'title' => 'Balance Financiero'
        ]);
    }

    public function calcular(Request $request): JsonResponse
    {
        try {
            $fechaDesde = $request->get('fecha_desde', Carbon::now()->startOfMonth());
            $fechaHasta = $request->get('fecha_hasta', Carbon::now()->endOfMonth());

            $totalIngresos = Ingreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])->sum('monto');
            $totalEgresos = Egreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])->sum('monto');
            $balanceNeto = $totalIngresos - $totalEgresos;

            // Análisis por categorías
            $ingresosPorCategoria = Ingreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])
                                          ->groupBy('categoria')
                                          ->selectRaw('categoria, SUM(monto) as total')
                                          ->get();

            $egresosPorCategoria = Egreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])
                                        ->groupBy('categoria')
                                        ->selectRaw('categoria, SUM(monto) as total')
                                        ->get();

            return response()->json([
                'success' => true,
                'balance' => [
                    'total_ingresos' => $totalIngresos,
                    'total_egresos' => $totalEgresos,
                    'balance_neto' => $balanceNeto,
                    'ingresos_por_categoria' => $ingresosPorCategoria,
                    'egresos_por_categoria' => $egresosPorCategoria,
                    'indicadores' => [
                        'rentabilidad' => $totalIngresos > 0 ? ($balanceNeto / $totalIngresos) * 100 : 0,
                        'eficiencia_gastos' => $totalIngresos > 0 ? ($totalEgresos / $totalIngresos) * 100 : 0,
                        'margen_operacional' => $totalIngresos > 0 ? ($balanceNeto / $totalIngresos) * 100 : 0
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function datosGraficos(Request $request): JsonResponse
    {
        try {
            $periodo = $request->get('periodo', 'mes'); // mes, trimestre, año

            $datos = $this->obtenerDatosPorPeriodo($periodo);

            return response()->json([
                'success' => true,
                'graficos' => $datos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function indicadores(Request $request): JsonResponse
    {
        try {
            $fechaDesde = $request->get('fecha_desde', Carbon::now()->startOfMonth());
            $fechaHasta = $request->get('fecha_hasta', Carbon::now()->endOfMonth());

            $indicadores = $this->calcularIndicadoresFinancieros($fechaDesde, $fechaHasta);

            return response()->json([
                'success' => true,
                'indicadores' => $indicadores
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function obtenerDatosPorPeriodo(string $periodo): array
    {
        $datos = [];
        
        switch ($periodo) {
            case 'año':
                // Datos mensuales del año actual
                for ($i = 1; $i <= 12; $i++) {
                    $fecha = Carbon::create(null, $i, 1);
                    $ingresos = Ingreso::whereMonth('fecha', $i)->sum('monto');
                    $egresos = Egreso::whereMonth('fecha', $i)->sum('monto');
                    
                    $datos[] = [
                        'periodo' => $fecha->format('M'),
                        'ingresos' => $ingresos,
                        'egresos' => $egresos,
                        'balance' => $ingresos - $egresos
                    ];
                }
                break;
                
            case 'trimestre':
                // Datos mensuales del trimestre actual
                $inicio = Carbon::now()->startOfQuarter();
                for ($i = 0; $i < 3; $i++) {
                    $fecha = $inicio->copy()->addMonths($i);
                    $ingresos = Ingreso::whereMonth('fecha', $fecha->month)->sum('monto');
                    $egresos = Egreso::whereMonth('fecha', $fecha->month)->sum('monto');
                    
                    $datos[] = [
                        'periodo' => $fecha->format('M'),
                        'ingresos' => $ingresos,
                        'egresos' => $egresos,
                        'balance' => $ingresos - $egresos
                    ];
                }
                break;
                
            default: // mes
                // Datos diarios del mes actual
                $inicio = Carbon::now()->startOfMonth();
                $fin = Carbon::now()->endOfMonth();
                
                for ($fecha = $inicio->copy(); $fecha->lte($fin); $fecha->addDay()) {
                    $ingresos = Ingreso::whereDate('fecha', $fecha)->sum('monto');
                    $egresos = Egreso::whereDate('fecha', $fecha)->sum('monto');
                    
                    $datos[] = [
                        'periodo' => $fecha->format('d'),
                        'ingresos' => $ingresos,
                        'egresos' => $egresos,
                        'balance' => $ingresos - $egresos
                    ];
                }
                break;
        }
        
        return $datos;
    }

    private function calcularIndicadoresFinancieros(string $fechaDesde, string $fechaHasta): array
    {
        $totalIngresos = Ingreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])->sum('monto');
        $totalEgresos = Egreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])->sum('monto');
        $balanceNeto = $totalIngresos - $totalEgresos;

        return [
            'liquidez' => [
                'saldo_actual' => $balanceNeto,
                'flujo_neto' => $balanceNeto,
                'capacidad_pago' => $totalIngresos > 0 ? ($totalIngresos / $totalEgresos) : 0
            ],
            'rentabilidad' => [
                'margen_neto' => $totalIngresos > 0 ? ($balanceNeto / $totalIngresos) * 100 : 0,
                'retorno_inversion' => $totalEgresos > 0 ? ($balanceNeto / $totalEgresos) * 100 : 0
            ],
            'eficiencia' => [
                'relacion_gastos' => $totalIngresos > 0 ? ($totalEgresos / $totalIngresos) * 100 : 0,
                'productividad' => $totalEgresos > 0 ? ($totalIngresos / $totalEgresos) : 0
            ],
            'crecimiento' => [
                'variacion_ingresos' => $this->calcularVariacionPeriodoAnterior('ingresos', $fechaDesde, $fechaHasta),
                'variacion_egresos' => $this->calcularVariacionPeriodoAnterior('egresos', $fechaDesde, $fechaHasta)
            ]
        ];
    }

    private function calcularVariacionPeriodoAnterior(string $tipo, string $fechaDesde, string $fechaHasta): float
    {
        $diasPeriodo = Carbon::parse($fechaDesde)->diffInDays(Carbon::parse($fechaHasta));
        $fechaInicioAnterior = Carbon::parse($fechaDesde)->subDays($diasPeriodo);
        $fechaFinAnterior = Carbon::parse($fechaDesde)->subDay();

        if ($tipo === 'ingresos') {
            $periodoActual = Ingreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])->sum('monto');
            $periodoAnterior = Ingreso::whereBetween('fecha', [$fechaInicioAnterior, $fechaFinAnterior])->sum('monto');
        } else {
            $periodoActual = Egreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])->sum('monto');
            $periodoAnterior = Egreso::whereBetween('fecha', [$fechaInicioAnterior, $fechaFinAnterior])->sum('monto');
        }

        return $periodoAnterior > 0 ? (($periodoActual - $periodoAnterior) / $periodoAnterior) * 100 : 0;
    }
}
