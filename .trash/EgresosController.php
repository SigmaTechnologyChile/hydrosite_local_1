<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\Egreso;
use App\Http\Requests\EgresoRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EgresosExport;
use Carbon\Carbon;

class EgresosController extends Controller
{
    /**
     * Mostrar lista de egresos
     */
    public function index(): View
    {
        return view('orgs.contable.egresos', [
            'title' => 'Registro de Egresos',
            'egresos' => Egreso::orderBy('fecha', 'desc')->paginate(15)
        ]);
    }

    /**
     * Mostrar formulario para crear nuevo egreso
     */
    public function create(): View
    {
        return view('orgs.contable.egresos.create', [
            'title' => 'Nuevo Egreso'
        ]);
    }

    /**
     * Almacenar nuevo egreso
     */
    public function store(EgresoRequest $request): JsonResponse
    {
        try {
            // Validar saldo suficiente
            $saldoActual = $this->calcularSaldoActual();
            if ($request->monto > $saldoActual) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo insuficiente',
                    'data' => [
                        'saldo_actual' => $saldoActual,
                        'monto_solicitado' => $request->monto,
                        'deficit' => $request->monto - $saldoActual
                    ]
                ], 400);
            }

            $egreso = new Egreso();
            $egreso->fecha = $request->fecha;
            $egreso->detalle = $request->detalle;
            $egreso->monto = $request->monto;
            $egreso->categoria = $request->categoria ?? 'General';
            $egreso->rut_beneficiario = $this->limpiarRut($request->rut_beneficiario);
            $egreso->nombre_beneficiario = $request->nombre_beneficiario;
            $egreso->comprobante = $this->generarNumeroComprobante();
            $egreso->observaciones = $request->observaciones;
            $egreso->save();

            // Actualizar saldo
            $this->actualizarSaldoGeneral();

            return response()->json([
                'success' => true,
                'message' => 'Egreso registrado exitosamente',
                'data' => [
                    'egreso' => $egreso,
                    'comprobante' => $egreso->comprobante,
                    'saldo_actual' => $this->calcularSaldoActual()
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar egreso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar egreso específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $egreso = Egreso::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'egreso' => $egreso,
                    'comprobante_url' => $this->generarUrlComprobante($egreso->id)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Egreso no encontrado'
            ], 404);
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(int $id): View
    {
        $egreso = Egreso::findOrFail($id);
        
        return view('orgs.contable.egresos.edit', [
            'title' => 'Editar Egreso',
            'egreso' => $egreso
        ]);
    }

    /**
     * Actualizar egreso
     */
    public function update(EgresoRequest $request, int $id): JsonResponse
    {
        try {
            $egreso = Egreso::findOrFail($id);
            $montoAnterior = $egreso->monto;
            
            // Validar saldo si el monto aumenta
            $diferenciaMonto = $request->monto - $montoAnterior;
            if ($diferenciaMonto > 0) {
                $saldoActual = $this->calcularSaldoActual() + $montoAnterior; // Restaurar monto anterior temporalmente
                if ($request->monto > $saldoActual) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Saldo insuficiente para la actualización',
                        'data' => [
                            'saldo_disponible' => $saldoActual,
                            'monto_solicitado' => $request->monto
                        ]
                    ], 400);
                }
            }

            $egreso->fecha = $request->fecha;
            $egreso->detalle = $request->detalle;
            $egreso->monto = $request->monto;
            $egreso->categoria = $request->categoria ?? $egreso->categoria;
            $egreso->rut_beneficiario = $this->limpiarRut($request->rut_beneficiario);
            $egreso->nombre_beneficiario = $request->nombre_beneficiario;
            $egreso->observaciones = $request->observaciones;
            $egreso->save();

            // Actualizar saldo
            $this->actualizarSaldoGeneral();

            return response()->json([
                'success' => true,
                'message' => 'Egreso actualizado exitosamente',
                'data' => [
                    'egreso' => $egreso,
                    'diferencia_monto' => $diferenciaMonto,
                    'saldo_actual' => $this->calcularSaldoActual()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar egreso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar egreso
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $egreso = Egreso::findOrFail($id);
            $montoLiberado = $egreso->monto;
            
            $egreso->delete();

            // Actualizar saldo
            $this->actualizarSaldoGeneral();

            return response()->json([
                'success' => true,
                'message' => 'Egreso eliminado exitosamente',
                'data' => [
                    'monto_liberado' => $montoLiberado,
                    'saldo_actual' => $this->calcularSaldoActual()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar egreso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar comprobante de egreso
     */
    public function comprobante(int $id): JsonResponse
    {
        try {
            $egreso = Egreso::findOrFail($id);
            
            $comprobanteData = [
                'numero' => $egreso->comprobante,
                'fecha' => $egreso->fecha,
                'detalle' => $egreso->detalle,
                'monto' => $egreso->monto,
                'categoria' => $egreso->categoria,
                'beneficiario' => [
                    'rut' => $egreso->rut_beneficiario,
                    'nombre' => $egreso->nombre_beneficiario
                ],
                'fecha_emision' => $egreso->created_at,
                'observaciones' => $egreso->observaciones
            ];

            return response()->json([
                'success' => true,
                'comprobante' => $comprobanteData,
                'url_pdf' => route('egresos.comprobante.pdf', $id)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar comprobante: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validar RUT chileno
     */
    public function validarRut(Request $request): JsonResponse
    {
        $rut = $this->limpiarRut($request->rut);
        
        if (!$this->esRutValido($rut)) {
            return response()->json([
                'success' => false,
                'message' => 'RUT inválido',
                'rut_formateado' => $this->formatearRut($rut)
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'RUT válido',
            'rut_formateado' => $this->formatearRut($rut),
            'rut_limpio' => $rut
        ]);
    }

    /**
     * Exportar egresos a Excel
     */
    public function exportarExcel(Request $request)
    {
        try {
            $fechaDesde = $request->get('fecha_desde');
            $fechaHasta = $request->get('fecha_hasta');
            $categoria = $request->get('categoria');

            $nombreArchivo = 'egresos_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(
                new EgresosExport($fechaDesde, $fechaHasta, $categoria),
                $nombreArchivo
            );

        } catch (\Exception $e) {
            return back()->with('error', 'Error al exportar: ' . $e->getMessage());
        }
    }

    // Métodos privados auxiliares

    /**
     * Generar número de comprobante único
     */
    private function generarNumeroComprobante(): string
    {
        $ultimoEgreso = Egreso::orderBy('id', 'desc')->first();
        $siguienteNumero = $ultimoEgreso ? $ultimoEgreso->id + 1 : 1;
        
        return 'EGR-' . str_pad($siguienteNumero, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Calcular saldo actual total
     */
    private function calcularSaldoActual(): float
    {
        $totalIngresos = \App\Models\Ingreso::sum('monto') ?? 0;
        $totalEgresos = Egreso::sum('monto') ?? 0;
        
        return $totalIngresos - $totalEgresos;
    }

    /**
     * Actualizar saldo general del sistema
     */
    private function actualizarSaldoGeneral(): void
    {
        cache()->forget('saldo_actual');
        cache()->put('saldo_actual', $this->calcularSaldoActual(), 3600);
    }

    /**
     * Generar URL del comprobante
     */
    private function generarUrlComprobante(int $id): string
    {
        return route('egresos.comprobante', $id);
    }

    /**
     * Limpiar RUT (quitar puntos y guión)
     */
    private function limpiarRut(string $rut): string
    {
        return preg_replace('/[^0-9kK]/', '', $rut);
    }

    /**
     * Formatear RUT con puntos y guión
     */
    private function formatearRut(string $rut): string
    {
        $rutLimpio = $this->limpiarRut($rut);
        
        if (strlen($rutLimpio) < 2) {
            return $rutLimpio;
        }

        $cuerpo = substr($rutLimpio, 0, -1);
        $dv = substr($rutLimpio, -1);
        
        $cuerpoFormateado = number_format($cuerpo, 0, '', '.');
        
        return $cuerpoFormateado . '-' . $dv;
    }

    /**
     * Validar RUT chileno usando algoritmo oficial
     */
    private function esRutValido(string $rut): bool
    {
        $rutLimpio = $this->limpiarRut($rut);
        
        if (strlen($rutLimpio) < 2) {
            return false;
        }

        $cuerpo = substr($rutLimpio, 0, -1);
        $dv = strtoupper(substr($rutLimpio, -1));
        
        if (!is_numeric($cuerpo)) {
            return false;
        }

        return $this->calcularDigitoVerificador($cuerpo) === $dv;
    }

    /**
     * Calcular dígito verificador del RUT
     */
    private function calcularDigitoVerificador(string $rut): string
    {
        $suma = 0;
        $multiplicador = 2;
        
        for ($i = strlen($rut) - 1; $i >= 0; $i--) {
            $suma += $rut[$i] * $multiplicador;
            $multiplicador = $multiplicador === 7 ? 2 : $multiplicador + 1;
        }
        
        $resto = $suma % 11;
        $dv = 11 - $resto;
        
        if ($dv === 11) {
            return '0';
        } elseif ($dv === 10) {
            return 'K';
        } else {
            return (string) $dv;
        }
    }

    /**
     * Obtener estadísticas de egresos
     */
    public function estadisticas(Request $request): JsonResponse
    {
        try {
            $fechaDesde = $request->get('fecha_desde', Carbon::now()->startOfMonth());
            $fechaHasta = $request->get('fecha_hasta', Carbon::now()->endOfMonth());

            $query = Egreso::whereBetween('fecha', [$fechaDesde, $fechaHasta]);

            $estadisticas = [
                'total' => $query->sum('monto'),
                'cantidad' => $query->count(),
                'promedio' => $query->avg('monto'),
                'mayor' => $query->max('monto'),
                'menor' => $query->min('monto'),
                'por_categoria' => $query->groupBy('categoria')
                                       ->selectRaw('categoria, COUNT(*) as cantidad, SUM(monto) as total')
                                       ->get(),
                'por_beneficiario' => $query->groupBy('nombre_beneficiario')
                                           ->selectRaw('nombre_beneficiario, COUNT(*) as cantidad, SUM(monto) as total')
                                           ->orderBy('total', 'desc')
                                           ->limit(10)
                                           ->get()
            ];

            return response()->json([
                'success' => true,
                'estadisticas' => $estadisticas,
                'periodo' => [
                    'desde' => $fechaDesde,
                    'hasta' => $fechaHasta
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }
}
