<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ConciliacionController extends Controller
{
    public function index(): View
    {
        return view('orgs.contable.conciliacion', [
            'title' => 'Conciliación Bancaria'
        ]);
    }

    public function procesar(Request $request): JsonResponse
    {
        try {
            $movimientosBanco = $request->get('movimientos_banco', []);
            $movimientosSistema = $this->obtenerMovimientosSistema($request);

            $conciliacion = $this->realizarConciliacion($movimientosBanco, $movimientosSistema);

            return response()->json([
                'success' => true,
                'conciliacion' => $conciliacion
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function importarBanco(Request $request): JsonResponse
    {
        try {
            $archivo = $request->file('archivo_banco');
            
            if (!$archivo) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se proporcionó archivo'
                ], 400);
            }

            $movimientos = $this->procesarArchivoBanco($archivo);

            return response()->json([
                'success' => true,
                'movimientos' => $movimientos,
                'cantidad' => count($movimientos)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerMovimientos(Request $request): JsonResponse
    {
        try {
            $fechaDesde = $request->get('fecha_desde');
            $fechaHasta = $request->get('fecha_hasta');

            $movimientos = $this->obtenerMovimientosSistema($request);

            return response()->json([
                'success' => true,
                'movimientos' => $movimientos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function obtenerMovimientosSistema(Request $request): array
    {
        $fechaDesde = $request->get('fecha_desde');
        $fechaHasta = $request->get('fecha_hasta');

        $movimientos = [];

        // Obtener ingresos
        $ingresos = \App\Models\Ingreso::when($fechaDesde, function ($query) use ($fechaDesde) {
                                              return $query->where('fecha', '>=', $fechaDesde);
                                          })
                                          ->when($fechaHasta, function ($query) use ($fechaHasta) {
                                              return $query->where('fecha', '<=', $fechaHasta);
                                          })
                                          ->get();

        foreach ($ingresos as $ingreso) {
            $movimientos[] = [
                'fecha' => $ingreso->fecha,
                'detalle' => $ingreso->detalle,
                'monto' => $ingreso->monto,
                'tipo' => 'ingreso',
                'id' => $ingreso->id,
                'conciliado' => false
            ];
        }

        // Obtener egresos
        $egresos = \App\Models\Egreso::when($fechaDesde, function ($query) use ($fechaDesde) {
                                             return $query->where('fecha', '>=', $fechaDesde);
                                         })
                                         ->when($fechaHasta, function ($query) use ($fechaHasta) {
                                             return $query->where('fecha', '<=', $fechaHasta);
                                         })
                                         ->get();

        foreach ($egresos as $egreso) {
            $movimientos[] = [
                'fecha' => $egreso->fecha,
                'detalle' => $egreso->detalle,
                'monto' => -$egreso->monto, // Negativo para egresos
                'tipo' => 'egreso',
                'id' => $egreso->id,
                'conciliado' => false
            ];
        }

        return $movimientos;
    }

    private function realizarConciliacion(array $movimientosBanco, array $movimientosSistema): array
    {
        $conciliados = [];
        $diferencias = [];
        $soloEnBanco = [];
        $soloEnSistema = $movimientosSistema;

        foreach ($movimientosBanco as $movBanco) {
            $encontrado = false;
            
            foreach ($movimientosSistema as $index => $movSistema) {
                if ($this->sonMovimientosIguales($movBanco, $movSistema)) {
                    $conciliados[] = [
                        'banco' => $movBanco,
                        'sistema' => $movSistema,
                        'estado' => 'conciliado'
                    ];
                    unset($soloEnSistema[$index]);
                    $encontrado = true;
                    break;
                }
            }

            if (!$encontrado) {
                $soloEnBanco[] = $movBanco;
            }
        }

        return [
            'conciliados' => $conciliados,
            'solo_en_banco' => $soloEnBanco,
            'solo_en_sistema' => array_values($soloEnSistema),
            'resumen' => [
                'total_conciliados' => count($conciliados),
                'total_diferencias' => count($soloEnBanco) + count($soloEnSistema),
                'saldo_banco' => array_sum(array_column($movimientosBanco, 'monto')),
                'saldo_sistema' => array_sum(array_column($movimientosSistema, 'monto'))
            ]
        ];
    }

    private function sonMovimientosIguales(array $movBanco, array $movSistema): bool
    {
        // Comparar fecha y monto con tolerancia
        $fechaIgual = $movBanco['fecha'] === $movSistema['fecha'];
        $montoIgual = abs($movBanco['monto'] - $movSistema['monto']) < 0.01;

        return $fechaIgual && $montoIgual;
    }

    private function procesarArchivoBanco($archivo): array
    {
        $movimientos = [];
        $contenido = file_get_contents($archivo->getPathname());
        $lineas = explode("\n", $contenido);

        foreach ($lineas as $linea) {
            $datos = str_getcsv($linea, ';');
            
            if (count($datos) >= 3) {
                $movimientos[] = [
                    'fecha' => $datos[0],
                    'detalle' => $datos[1],
                    'monto' => floatval($datos[2])
                ];
            }
        }

        return $movimientos;
    }

    public function marcarConciliado(Request $request): JsonResponse
    {
        try {
            $tipo = $request->get('tipo');
            $id = $request->get('id');

            // Aquí se marcaría el movimiento como conciliado en la base de datos
            
            return response()->json([
                'success' => true,
                'message' => 'Movimiento marcado como conciliado'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
