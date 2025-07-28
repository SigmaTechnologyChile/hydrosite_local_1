<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Ingreso;
use App\Http\Requests\IngresoRequest;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IngresosExport;
use App\Imports\IngresosImport;
use Carbon\Carbon;

class IngresosController extends Controller
{
    /**
     * Mostrar lista de ingresos
     */
    public function index(): View
    {
        return view('orgs.contable.ingresos', [
            'title' => 'Registro de Ingresos',
            'ingresos' => Ingreso::orderBy('fecha', 'desc')->paginate(15)
        ]);
    }

    /**
     * Mostrar formulario para crear nuevo ingreso
     */
    public function create(): View
    {
        return view('orgs.contable.ingresos.create', [
            'title' => 'Nuevo Ingreso'
        ]);
    }

    /**
     * Almacenar nuevo ingreso
     */
    public function store(IngresoRequest $request): JsonResponse
    {
        try {
            $ingreso = new Ingreso();
            $ingreso->fecha = $request->fecha;
            $ingreso->detalle = $request->detalle;
            $ingreso->monto = $request->monto;
            $ingreso->categoria = $request->categoria ?? 'General';
            $ingreso->comprobante = $this->generarNumeroComprobante();
            $ingreso->observaciones = $request->observaciones;
            $ingreso->save();

            // Actualizar saldo en tiempo real
            $this->actualizarSaldoGeneral();

            return response()->json([
                'success' => true,
                'message' => 'Ingreso registrado exitosamente',
                'data' => [
                    'ingreso' => $ingreso,
                    'comprobante' => $ingreso->comprobante,
                    'saldo_actual' => $this->calcularSaldoActual()
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar ingreso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar ingreso específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $ingreso = Ingreso::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'ingreso' => $ingreso,
                    'comprobante_url' => $this->generarUrlComprobante($ingreso->id)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ingreso no encontrado'
            ], 404);
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(int $id): View
    {
        $ingreso = Ingreso::findOrFail($id);
        
        return view('orgs.contable.ingresos.edit', [
            'title' => 'Editar Ingreso',
            'ingreso' => $ingreso
        ]);
    }

    /**
     * Actualizar ingreso
     */
    public function update(IngresoRequest $request, int $id): JsonResponse
    {
        try {
            $ingreso = Ingreso::findOrFail($id);
            
            $ingreso->fecha = $request->fecha;
            $ingreso->detalle = $request->detalle;
            $ingreso->monto = $request->monto;
            $ingreso->categoria = $request->categoria ?? $ingreso->categoria;
            $ingreso->observaciones = $request->observaciones;
            $ingreso->save();

            // Actualizar saldo
            $this->actualizarSaldoGeneral();

            return response()->json([
                'success' => true,
                'message' => 'Ingreso actualizado exitosamente',
                'data' => [
                    'ingreso' => $ingreso,
                    'saldo_actual' => $this->calcularSaldoActual()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar ingreso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar ingreso
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $ingreso = Ingreso::findOrFail($id);
            $montoEliminado = $ingreso->monto;
            
            $ingreso->delete();

            // Actualizar saldo
            $this->actualizarSaldoGeneral();

            return response()->json([
                'success' => true,
                'message' => 'Ingreso eliminado exitosamente',
                'data' => [
                    'monto_eliminado' => $montoEliminado,
                    'saldo_actual' => $this->calcularSaldoActual()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar ingreso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar comprobante de ingreso
     */
    public function comprobante(int $id): JsonResponse
    {
        try {
            $ingreso = Ingreso::findOrFail($id);
            
            $comprobanteData = [
                'numero' => $ingreso->comprobante,
                'fecha' => $ingreso->fecha,
                'detalle' => $ingreso->detalle,
                'monto' => $ingreso->monto,
                'categoria' => $ingreso->categoria,
                'fecha_emision' => $ingreso->created_at,
                'observaciones' => $ingreso->observaciones
            ];

            return response()->json([
                'success' => true,
                'comprobante' => $comprobanteData,
                'url_pdf' => route('ingresos.comprobante.pdf', $id)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar comprobante: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Importar ingresos desde Excel
     */
    public function importar(Request $request): JsonResponse
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $archivo = $request->file('archivo');
            $import = new IngresosImport();
            
            Excel::import($import, $archivo);

            // Actualizar saldo después de la importación
            $this->actualizarSaldoGeneral();

            return response()->json([
                'success' => true,
                'message' => 'Ingresos importados exitosamente',
                'data' => [
                    'registros_importados' => $import->getRowCount(),
                    'errores' => $import->getErrors(),
                    'saldo_actual' => $this->calcularSaldoActual()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al importar archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar ingresos a Excel
     */
    public function exportarExcel(Request $request)
    {
        try {
            $fechaDesde = $request->get('fecha_desde');
            $fechaHasta = $request->get('fecha_hasta');
            $categoria = $request->get('categoria');

            $nombreArchivo = 'ingresos_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(
                new IngresosExport($fechaDesde, $fechaHasta, $categoria),
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
        $ultimoIngreso = Ingreso::orderBy('id', 'desc')->first();
        $siguienteNumero = $ultimoIngreso ? $ultimoIngreso->id + 1 : 1;
        
        return 'ING-' . str_pad($siguienteNumero, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Calcular saldo actual total
     */
    private function calcularSaldoActual(): float
    {
        $totalIngresos = Ingreso::sum('monto') ?? 0;
        $totalEgresos = \App\Models\Egreso::sum('monto') ?? 0;
        
        return $totalIngresos - $totalEgresos;
    }

    /**
     * Actualizar saldo general del sistema
     */
    private function actualizarSaldoGeneral(): void
    {
        // Aquí se puede implementar lógica adicional para actualizar 
        // caches o notificar a otros componentes del sistema
        cache()->forget('saldo_actual');
        cache()->put('saldo_actual', $this->calcularSaldoActual(), 3600);
    }

    /**
     * Generar URL del comprobante
     */
    private function generarUrlComprobante(int $id): string
    {
        return route('ingresos.comprobante', $id);
    }

    /**
     * Validar datos del ingreso
     */
    private function validarDatosIngreso(array $datos): array
    {
        $errores = [];

        if (!isset($datos['fecha']) || empty($datos['fecha'])) {
            $errores[] = 'La fecha es requerida';
        }

        if (!isset($datos['detalle']) || empty($datos['detalle'])) {
            $errores[] = 'El detalle es requerido';
        }

        if (!isset($datos['monto']) || !is_numeric($datos['monto']) || $datos['monto'] <= 0) {
            $errores[] = 'El monto debe ser un valor numérico positivo';
        }

        return $errores;
    }

    /**
     * Obtener estadísticas de ingresos
     */
    public function estadisticas(Request $request): JsonResponse
    {
        try {
            $fechaDesde = $request->get('fecha_desde', Carbon::now()->startOfMonth());
            $fechaHasta = $request->get('fecha_hasta', Carbon::now()->endOfMonth());

            $query = Ingreso::whereBetween('fecha', [$fechaDesde, $fechaHasta]);

            $estadisticas = [
                'total' => $query->sum('monto'),
                'cantidad' => $query->count(),
                'promedio' => $query->avg('monto'),
                'mayor' => $query->max('monto'),
                'menor' => $query->min('monto'),
                'por_categoria' => $query->groupBy('categoria')
                                       ->selectRaw('categoria, COUNT(*) as cantidad, SUM(monto) as total')
                                       ->get(),
                'por_mes' => $query->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
                                  ->groupBy('mes')
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
