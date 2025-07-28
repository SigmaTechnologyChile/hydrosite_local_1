<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class InformeRubroController extends Controller
{
    public function index(): View
    {
        return view('orgs.contable.informe-rubro', [
            'title' => 'Informe por Rubro'
        ]);
    }

    public function generar(Request $request): JsonResponse
    {
        try {
            $fechaDesde = $request->get('fecha_desde');
            $fechaHasta = $request->get('fecha_hasta');
            $tipoInforme = $request->get('tipo', 'categoria');

            $datos = $this->obtenerDatosPorRubro($fechaDesde, $fechaHasta, $tipoInforme);

            return response()->json([
                'success' => true,
                'informe' => $datos
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
            $fechaDesde = $request->get('fecha_desde');
            $fechaHasta = $request->get('fecha_hasta');

            $datosGraficos = [
                'ingresos_por_categoria' => $this->obtenerIngresosPorCategoria($fechaDesde, $fechaHasta),
                'egresos_por_categoria' => $this->obtenerEgresosPorCategoria($fechaDesde, $fechaHasta),
                'comparativo_mensual' => $this->obtenerComparativoMensual($fechaDesde, $fechaHasta)
            ];

            return response()->json([
                'success' => true,
                'graficos' => $datosGraficos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function categorizar(Request $request): JsonResponse
    {
        try {
            $movimientos = $request->get('movimientos', []);
            $categorizados = [];

            foreach ($movimientos as $movimiento) {
                $categoria = $this->clasificarMovimiento($movimiento);
                $categorizados[] = array_merge($movimiento, ['categoria_sugerida' => $categoria]);
            }

            return response()->json([
                'success' => true,
                'movimientos_categorizados' => $categorizados
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function obtenerDatosPorRubro(string $fechaDesde, string $fechaHasta, string $tipo): array
    {
        $ingresos = \App\Models\Ingreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])
                                      ->get()
                                      ->groupBy('categoria');

        $egresos = \App\Models\Egreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])
                                    ->get()
                                    ->groupBy('categoria');

        $resumen = [];

        // Procesar ingresos por categoría
        foreach ($ingresos as $categoria => $movimientos) {
            if (!isset($resumen[$categoria])) {
                $resumen[$categoria] = ['ingresos' => 0, 'egresos' => 0, 'balance' => 0];
            }
            $resumen[$categoria]['ingresos'] = $movimientos->sum('monto');
        }

        // Procesar egresos por categoría
        foreach ($egresos as $categoria => $movimientos) {
            if (!isset($resumen[$categoria])) {
                $resumen[$categoria] = ['ingresos' => 0, 'egresos' => 0, 'balance' => 0];
            }
            $resumen[$categoria]['egresos'] = $movimientos->sum('monto');
        }

        // Calcular balances
        foreach ($resumen as $categoria => &$datos) {
            $datos['balance'] = $datos['ingresos'] - $datos['egresos'];
        }

        return $resumen;
    }

    private function obtenerIngresosPorCategoria(string $fechaDesde, string $fechaHasta): array
    {
        return \App\Models\Ingreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])
                                 ->groupBy('categoria')
                                 ->selectRaw('categoria, SUM(monto) as total')
                                 ->get()
                                 ->toArray();
    }

    private function obtenerEgresosPorCategoria(string $fechaDesde, string $fechaHasta): array
    {
        return \App\Models\Egreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])
                                ->groupBy('categoria')
                                ->selectRaw('categoria, SUM(monto) as total')
                                ->get()
                                ->toArray();
    }

    private function obtenerComparativoMensual(string $fechaDesde, string $fechaHasta): array
    {
        $datos = [];
        
        $ingresosMensuales = \App\Models\Ingreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])
                                              ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
                                              ->groupBy('mes')
                                              ->get();

        $egresosMensuales = \App\Models\Egreso::whereBetween('fecha', [$fechaDesde, $fechaHasta])
                                             ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
                                             ->groupBy('mes')
                                             ->get();

        for ($mes = 1; $mes <= 12; $mes++) {
            $ingresoMes = $ingresosMensuales->where('mes', $mes)->first();
            $egresoMes = $egresosMensuales->where('mes', $mes)->first();

            $datos[] = [
                'mes' => $mes,
                'nombre_mes' => \Carbon\Carbon::create(null, $mes, 1)->format('M'),
                'ingresos' => $ingresoMes ? $ingresoMes->total : 0,
                'egresos' => $egresoMes ? $egresoMes->total : 0
            ];
        }

        return $datos;
    }

    private function clasificarMovimiento(array $movimiento): string
    {
        $detalle = strtolower($movimiento['detalle'] ?? '');
        
        // Palabras clave para clasificación automática
        $categorias = [
            'Ventas' => ['venta', 'cliente', 'factura', 'ingreso por'],
            'Servicios' => ['servicio', 'honorario', 'consultoria', 'asesoria'],
            'Gastos Operacionales' => ['oficina', 'arriendo', 'luz', 'agua', 'telefono', 'internet'],
            'Personal' => ['sueldo', 'salario', 'honorario', 'empleado', 'trabajador'],
            'Marketing' => ['publicidad', 'marketing', 'promocion', 'facebook', 'google'],
            'Suministros' => ['material', 'suministro', 'papeleria', 'utiles'],
            'Transporte' => ['combustible', 'bencina', 'taxi', 'uber', 'viaje'],
            'Alimentación' => ['almuerzo', 'comida', 'restaurant', 'cafe'],
            'Tecnología' => ['software', 'licencia', 'computador', 'tecnologia'],
            'Otros' => []
        ];

        foreach ($categorias as $categoria => $palabrasClave) {
            foreach ($palabrasClave as $palabra) {
                if (strpos($detalle, $palabra) !== false) {
                    return $categoria;
                }
            }
        }

        return 'Sin Categorizar';
    }

    public function detalle(string $categoria, Request $request): JsonResponse
    {
        try {
            $fechaDesde = $request->get('fecha_desde');
            $fechaHasta = $request->get('fecha_hasta');

            $ingresos = \App\Models\Ingreso::where('categoria', $categoria)
                                          ->when($fechaDesde, function ($query) use ($fechaDesde) {
                                              return $query->where('fecha', '>=', $fechaDesde);
                                          })
                                          ->when($fechaHasta, function ($query) use ($fechaHasta) {
                                              return $query->where('fecha', '<=', $fechaHasta);
                                          })
                                          ->get();

            $egresos = \App\Models\Egreso::where('categoria', $categoria)
                                        ->when($fechaDesde, function ($query) use ($fechaDesde) {
                                            return $query->where('fecha', '>=', $fechaDesde);
                                        })
                                        ->when($fechaHasta, function ($query) use ($fechaHasta) {
                                            return $query->where('fecha', '<=', $fechaHasta);
                                        })
                                        ->get();

            return response()->json([
                'success' => true,
                'categoria' => $categoria,
                'detalle' => [
                    'ingresos' => $ingresos,
                    'egresos' => $egresos,
                    'resumen' => [
                        'total_ingresos' => $ingresos->sum('monto'),
                        'total_egresos' => $egresos->sum('monto'),
                        'balance' => $ingresos->sum('monto') - $egresos->sum('monto')
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
}
