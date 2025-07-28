<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MovimientosController extends Controller
{
    public function index(): View
    {
        return view('orgs.contable.movimientos', [
            'title' => 'Registro de Movimientos'
        ]);
    }

    public function listar(Request $request): JsonResponse
    {
        try {
            $page = $request->get('page', 1);
            $limit = $request->get('limit', 25);
            $offset = ($page - 1) * $limit;

            $movimientos = $this->obtenerTodosLosMovimientos($request);
            $total = count($movimientos);
            $movimientosPagina = array_slice($movimientos, $offset, $limit);

            return response()->json([
                'success' => true,
                'movimientos' => $movimientosPagina,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $total,
                    'total_pages' => ceil($total / $limit)
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
        try {
            $movimientos = $this->obtenerTodosLosMovimientos($request);
            $movimientosFiltrados = $this->aplicarFiltros($movimientos, $request);

            return response()->json([
                'success' => true,
                'movimientos' => $movimientosFiltrados,
                'total' => count($movimientosFiltrados)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function buscar(Request $request): JsonResponse
    {
        try {
            $termino = $request->get('termino', '');
            $movimientos = $this->obtenerTodosLosMovimientos($request);
            
            $resultados = array_filter($movimientos, function ($movimiento) use ($termino) {
                return stripos($movimiento['detalle'], $termino) !== false;
            });

            return response()->json([
                'success' => true,
                'resultados' => array_values($resultados),
                'total' => count($resultados)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $tipo = $request->get('tipo');
            $movimiento = $this->obtenerMovimientoPorId($id, $tipo);

            if (!$movimiento) {
                return response()->json([
                    'success' => false,
                    'message' => 'Movimiento no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'movimiento' => $movimiento
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(int $id, Request $request): JsonResponse
    {
        try {
            $tipo = $request->get('tipo');
            $resultado = $this->eliminarMovimiento($id, $tipo);

            if (!$resultado) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar el movimiento'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Movimiento eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function eliminarMultiples(Request $request): JsonResponse
    {
        try {
            $movimientos = $request->get('movimientos', []);
            $eliminados = 0;

            foreach ($movimientos as $movimiento) {
                if ($this->eliminarMovimiento($movimiento['id'], $movimiento['tipo'])) {
                    $eliminados++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$eliminados} movimientos eliminados exitosamente",
                'eliminados' => $eliminados
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function estadisticas(Request $request): JsonResponse
    {
        try {
            $movimientos = $this->obtenerTodosLosMovimientos($request);
            
            $ingresos = array_filter($movimientos, function ($m) {
                return in_array($m['tipo'], ['ingreso', 'giro']);
            });
            
            $egresos = array_filter($movimientos, function ($m) {
                return in_array($m['tipo'], ['egreso', 'deposito']);
            });

            $estadisticas = [
                'total_movimientos' => count($movimientos),
                'total_ingresos' => array_sum(array_column($ingresos, 'monto')),
                'total_egresos' => array_sum(array_column($egresos, 'monto')),
                'cantidad_ingresos' => count($ingresos),
                'cantidad_egresos' => count($egresos),
                'promedio_ingreso' => count($ingresos) > 0 ? array_sum(array_column($ingresos, 'monto')) / count($ingresos) : 0,
                'promedio_egreso' => count($egresos) > 0 ? array_sum(array_column($egresos, 'monto')) / count($egresos) : 0,
                'balance_neto' => array_sum(array_column($ingresos, 'monto')) - array_sum(array_column($egresos, 'monto'))
            ];

            return response()->json([
                'success' => true,
                'estadisticas' => $estadisticas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function obtenerTodosLosMovimientos(Request $request): array
    {
        $movimientos = [];
        $fechaDesde = $request->get('fecha_desde');
        $fechaHasta = $request->get('fecha_hasta');

        // Obtener ingresos
        $queryIngresos = \App\Models\Ingreso::query();
        if ($fechaDesde) $queryIngresos->where('fecha', '>=', $fechaDesde);
        if ($fechaHasta) $queryIngresos->where('fecha', '<=', $fechaHasta);
        
        $ingresos = $queryIngresos->get();
        foreach ($ingresos as $ingreso) {
            $movimientos[] = [
                'id' => $ingreso->id,
                'fecha' => $ingreso->fecha,
                'detalle' => $ingreso->detalle,
                'monto' => $ingreso->monto,
                'tipo' => 'ingreso',
                'categoria' => $ingreso->categoria ?? 'General',
                'created_at' => $ingreso->created_at
            ];
        }

        // Obtener egresos
        $queryEgresos = \App\Models\Egreso::query();
        if ($fechaDesde) $queryEgresos->where('fecha', '>=', $fechaDesde);
        if ($fechaHasta) $queryEgresos->where('fecha', '<=', $fechaHasta);
        
        $egresos = $queryEgresos->get();
        foreach ($egresos as $egreso) {
            $movimientos[] = [
                'id' => $egreso->id,
                'fecha' => $egreso->fecha,
                'detalle' => $egreso->detalle,
                'monto' => $egreso->monto,
                'tipo' => 'egreso',
                'categoria' => $egreso->categoria ?? 'General',
                'created_at' => $egreso->created_at
            ];
        }

        // Obtener giros si existen
        if (class_exists('\App\Models\Giro')) {
            $queryGiros = \App\Models\Giro::query();
            if ($fechaDesde) $queryGiros->where('fecha', '>=', $fechaDesde);
            if ($fechaHasta) $queryGiros->where('fecha', '<=', $fechaHasta);
            
            $giros = $queryGiros->get();
            foreach ($giros as $giro) {
                $movimientos[] = [
                    'id' => $giro->id,
                    'fecha' => $giro->fecha,
                    'detalle' => $giro->detalle,
                    'monto' => $giro->monto,
                    'tipo' => 'giro',
                    'categoria' => 'Transferencia',
                    'created_at' => $giro->created_at
                ];
            }
        }

        // Obtener depósitos si existen
        if (class_exists('\App\Models\Deposito')) {
            $queryDepositos = \App\Models\Deposito::query();
            if ($fechaDesde) $queryDepositos->where('fecha', '>=', $fechaDesde);
            if ($fechaHasta) $queryDepositos->where('fecha', '<=', $fechaHasta);
            
            $depositos = $queryDepositos->get();
            foreach ($depositos as $deposito) {
                $movimientos[] = [
                    'id' => $deposito->id,
                    'fecha' => $deposito->fecha,
                    'detalle' => $deposito->detalle,
                    'monto' => $deposito->monto,
                    'tipo' => 'deposito',
                    'categoria' => 'Transferencia',
                    'created_at' => $deposito->created_at
                ];
            }
        }

        // Ordenar por fecha descendente
        usort($movimientos, function ($a, $b) {
            return strcmp($b['fecha'], $a['fecha']);
        });

        return $movimientos;
    }

    private function aplicarFiltros(array $movimientos, Request $request): array
    {
        $tipo = $request->get('tipo');
        $montoMin = $request->get('monto_min');
        $montoMax = $request->get('monto_max');
        $categoria = $request->get('categoria');

        return array_filter($movimientos, function ($movimiento) use ($tipo, $montoMin, $montoMax, $categoria) {
            if ($tipo && $movimiento['tipo'] !== $tipo) return false;
            if ($montoMin && $movimiento['monto'] < $montoMin) return false;
            if ($montoMax && $movimiento['monto'] > $montoMax) return false;
            if ($categoria && $movimiento['categoria'] !== $categoria) return false;
            
            return true;
        });
    }

    private function obtenerMovimientoPorId(int $id, string $tipo): ?array
    {
        switch ($tipo) {
            case 'ingreso':
                $movimiento = \App\Models\Ingreso::find($id);
                break;
            case 'egreso':
                $movimiento = \App\Models\Egreso::find($id);
                break;
            case 'giro':
                $movimiento = class_exists('\App\Models\Giro') ? \App\Models\Giro::find($id) : null;
                break;
            case 'deposito':
                $movimiento = class_exists('\App\Models\Deposito') ? \App\Models\Deposito::find($id) : null;
                break;
            default:
                return null;
        }

        if (!$movimiento) return null;

        return [
            'id' => $movimiento->id,
            'fecha' => $movimiento->fecha,
            'detalle' => $movimiento->detalle,
            'monto' => $movimiento->monto,
            'tipo' => $tipo,
            'categoria' => $movimiento->categoria ?? 'General'
        ];
    }

    private function eliminarMovimiento(int $id, string $tipo): bool
    {
        try {
            switch ($tipo) {
                case 'ingreso':
                    return \App\Models\Ingreso::destroy($id) > 0;
                case 'egreso':
                    return \App\Models\Egreso::destroy($id) > 0;
                case 'giro':
                    return class_exists('\App\Models\Giro') ? \App\Models\Giro::destroy($id) > 0 : false;
                case 'deposito':
                    return class_exists('\App\Models\Deposito') ? \App\Models\Deposito::destroy($id) > 0 : false;
                default:
                    return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
