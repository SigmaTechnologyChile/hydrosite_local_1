<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\Ingreso;
use App\Models\Egreso;
use App\Models\Giro;
use App\Models\Deposito;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard principal
     */
    public function index(): View
    {
        return view('orgs.contable.dashboard');
    }

    /**
     * Obtener estadísticas financieras para el dashboard
     */
    public function estadisticas(Request $request): JsonResponse
    {
        try {
            // Obtener fecha actual o la proporcionada
            $fecha = $request->get('fecha', Carbon::now()->format('Y-m-d'));
            $fechaInicio = Carbon::parse($fecha)->startOfMonth();
            $fechaFin = Carbon::parse($fecha)->endOfMonth();

            // Calcular totales del mes actual
            $totalIngresos = $this->calcularTotalIngresos($fechaInicio, $fechaFin);
            $totalEgresos = $this->calcularTotalEgresos($fechaInicio, $fechaFin);
            $saldoFinal = $totalIngresos - $totalEgresos;

            // Estadísticas adicionales
            $cantidadMovimientos = $this->contarMovimientos($fechaInicio, $fechaFin);
            $promedioIngresosDiario = $this->calcularPromedioIngresos($fechaInicio, $fechaFin);
            $mayorEgreso = $this->obtenerMayorEgreso($fechaInicio, $fechaFin);

            return response()->json([
                'success' => true,
                'data' => [
                    'totalIngresos' => $totalIngresos,
                    'totalEgresos' => $totalEgresos,
                    'saldoFinal' => $saldoFinal,
                    'cantidadMovimientos' => $cantidadMovimientos,
                    'promedioIngresosDiario' => $promedioIngresosDiario,
                    'mayorEgreso' => $mayorEgreso,
                    'fecha' => $fecha,
                    'periodo' => $fechaInicio->format('d/m/Y') . ' - ' . $fechaFin->format('d/m/Y')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener saldo actual en tiempo real
     */
    public function saldoActual(): JsonResponse
    {
        try {
            $totalIngresos = $this->calcularTotalIngresos();
            $totalEgresos = $this->calcularTotalEgresos();
            $saldoActual = $totalIngresos - $totalEgresos;

            return response()->json([
                'success' => true,
                'saldo' => $saldoActual,
                'timestamp' => Carbon::now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular saldo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener resumen financiero completo
     */
    public function resumenFinanciero(Request $request): JsonResponse
    {
        try {
            $periodo = $request->get('periodo', 'mes'); // mes, semana, año
            $fechas = $this->calcularRangoFechas($periodo);

            $resumen = [
                'ingresos' => [
                    'total' => $this->calcularTotalIngresos($fechas['inicio'], $fechas['fin']),
                    'cantidad' => $this->contarIngresos($fechas['inicio'], $fechas['fin']),
                    'promedio' => $this->calcularPromedioIngresos($fechas['inicio'], $fechas['fin'])
                ],
                'egresos' => [
                    'total' => $this->calcularTotalEgresos($fechas['inicio'], $fechas['fin']),
                    'cantidad' => $this->contarEgresos($fechas['inicio'], $fechas['fin']),
                    'promedio' => $this->calcularPromedioEgresos($fechas['inicio'], $fechas['fin'])
                ],
                'transferencias' => [
                    'giros' => $this->calcularTotalGiros($fechas['inicio'], $fechas['fin']),
                    'depositos' => $this->calcularTotalDepositos($fechas['inicio'], $fechas['fin'])
                ],
                'balance' => [
                    'actual' => $this->calcularTotalIngresos($fechas['inicio'], $fechas['fin']) - 
                               $this->calcularTotalEgresos($fechas['inicio'], $fechas['fin']),
                    'proyectado' => $this->calcularBalanceProyectado($fechas['inicio'], $fechas['fin'])
                ]
            ];

            return response()->json([
                'success' => true,
                'resumen' => $resumen,
                'periodo' => $periodo,
                'fechas' => $fechas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar resumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener últimos movimientos
     */
    public function ultimosMovimientos(Request $request): JsonResponse
    {
        try {
            $limite = $request->get('limite', 10);
            $movimientos = [];

            // Obtener últimos ingresos
            $ingresos = Ingreso::orderBy('created_at', 'desc')
                              ->limit($limite)
                              ->get()
                              ->map(function ($ingreso) {
                                  return [
                                      'id' => $ingreso->id,
                                      'tipo' => 'ingreso',
                                      'fecha' => $ingreso->fecha,
                                      'detalle' => $ingreso->detalle,
                                      'monto' => $ingreso->monto,
                                      'created_at' => $ingreso->created_at
                                  ];
                              });

            // Obtener últimos egresos
            $egresos = Egreso::orderBy('created_at', 'desc')
                            ->limit($limite)
                            ->get()
                            ->map(function ($egreso) {
                                return [
                                    'id' => $egreso->id,
                                    'tipo' => 'egreso',
                                    'fecha' => $egreso->fecha,
                                    'detalle' => $egreso->detalle,
                                    'monto' => $egreso->monto,
                                    'created_at' => $egreso->created_at
                                ];
                            });

            // Combinar y ordenar por fecha
            $movimientos = $ingresos->concat($egresos)
                                  ->sortByDesc('created_at')
                                  ->take($limite)
                                  ->values();

            return response()->json([
                'success' => true,
                'movimientos' => $movimientos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener movimientos: ' . $e->getMessage()
            ], 500);
        }
    }

    // Métodos privados para cálculos

    private function calcularTotalIngresos($fechaInicio = null, $fechaFin = null): float
    {
        $query = Ingreso::query();
        
        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        }
        
        return $query->sum('monto') ?? 0;
    }

    private function calcularTotalEgresos($fechaInicio = null, $fechaFin = null): float
    {
        $query = Egreso::query();
        
        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        }
        
        return $query->sum('monto') ?? 0;
    }

    private function calcularTotalGiros($fechaInicio = null, $fechaFin = null): float
    {
        $query = Giro::query();
        
        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        }
        
        return $query->sum('monto') ?? 0;
    }

    private function calcularTotalDepositos($fechaInicio = null, $fechaFin = null): float
    {
        $query = Deposito::query();
        
        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        }
        
        return $query->sum('monto') ?? 0;
    }

    private function contarMovimientos($fechaInicio, $fechaFin): int
    {
        $ingresos = Ingreso::whereBetween('fecha', [$fechaInicio, $fechaFin])->count();
        $egresos = Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin])->count();
        $giros = Giro::whereBetween('fecha', [$fechaInicio, $fechaFin])->count();
        $depositos = Deposito::whereBetween('fecha', [$fechaInicio, $fechaFin])->count();
        
        return $ingresos + $egresos + $giros + $depositos;
    }

    private function contarIngresos($fechaInicio, $fechaFin): int
    {
        return Ingreso::whereBetween('fecha', [$fechaInicio, $fechaFin])->count();
    }

    private function contarEgresos($fechaInicio, $fechaFin): int
    {
        return Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin])->count();
    }

    private function calcularPromedioIngresos($fechaInicio, $fechaFin): float
    {
        $total = $this->calcularTotalIngresos($fechaInicio, $fechaFin);
        $dias = Carbon::parse($fechaInicio)->diffInDays(Carbon::parse($fechaFin)) + 1;
        
        return $dias > 0 ? $total / $dias : 0;
    }

    private function calcularPromedioEgresos($fechaInicio, $fechaFin): float
    {
        $total = $this->calcularTotalEgresos($fechaInicio, $fechaFin);
        $dias = Carbon::parse($fechaInicio)->diffInDays(Carbon::parse($fechaFin)) + 1;
        
        return $dias > 0 ? $total / $dias : 0;
    }

    private function obtenerMayorEgreso($fechaInicio, $fechaFin): float
    {
        return Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin])
                    ->max('monto') ?? 0;
    }

    private function calcularBalanceProyectado($fechaInicio, $fechaFin): float
    {
        $promedioIngresos = $this->calcularPromedioIngresos($fechaInicio, $fechaFin);
        $promedioEgresos = $this->calcularPromedioEgresos($fechaInicio, $fechaFin);
        $diasRestantes = Carbon::now()->diffInDays(Carbon::parse($fechaFin)->endOfMonth());
        
        return ($promedioIngresos - $promedioEgresos) * $diasRestantes;
    }

    private function calcularRangoFechas(string $periodo): array
    {
        $hoy = Carbon::now();
        
        switch ($periodo) {
            case 'semana':
                return [
                    'inicio' => $hoy->startOfWeek(),
                    'fin' => $hoy->endOfWeek()
                ];
            case 'año':
                return [
                    'inicio' => $hoy->startOfYear(),
                    'fin' => $hoy->endOfYear()
                ];
            case 'mes':
            default:
                return [
                    'inicio' => $hoy->startOfMonth(),
                    'fin' => $hoy->endOfMonth()
                ];
        }
    }
}
