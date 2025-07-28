<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
// ...otros use...
use App\Models\ConfiguracionInicial;
use Illuminate\Http\Request;

// ...código existente...

class ContableController extends Controller
{
    /**
     * Exporta los movimientos a Excel para la organización.
     */
    public function exportarExcel($id)
    {
        // Obtener movimientos filtrados por organización
        $movimientos = \App\Models\Movimiento::whereHas('cuentaOrigen', function($q) use ($id) {
            $q->where('org_id', $id);
        })->orderBy('fecha', 'desc')->get();

        // Crear el contenido CSV manualmente (puedes cambiar a Laravel Excel si está instalado)
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="movimientos_org_' . $id . '.csv"',
        ];

        $callback = function() use ($movimientos) {
            $handle = fopen('php://output', 'w');
            // Encabezados
            fputcsv($handle, ['Fecha', 'Descripción', 'Total Consumo', 'Cuotas Incorporación', 'Otros Ingresos', 'Giros']);
            foreach ($movimientos as $m) {
                fputcsv($handle, [
                    $m->fecha,
                    $m->descripcion,
                    $m->total_consumo,
                    $m->cuotas_incorporacion,
                    $m->otros_ingresos,
                    $m->giros,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'saldo_caja_general' => 'required|numeric',
            'banco_caja_general' => 'nullable|string',
            'numero_caja_general' => 'nullable|string',
            'responsable_caja_general' => 'nullable|string',
            'tipo_cuenta_caja_general' => 'nullable|string',
            'observaciones_caja_general' => 'nullable|string',

            'saldo_cta_corriente_1' => 'required|numeric',
            'banco_cta_corriente_1' => 'nullable|string',
            'numero_cta_corriente_1' => 'nullable|string',
            'responsable_cta_corriente_1' => 'nullable|string',
            'tipo_cuenta_cta_corriente_1' => 'nullable|string',
            'observaciones_cta_corriente_1' => 'nullable|string',

            'saldo_cta_corriente_2' => 'required|numeric',
            'banco_cta_corriente_2' => 'nullable|string',
            'numero_cta_corriente_2' => 'nullable|string',
            'responsable_cta_corriente_2' => 'nullable|string',
            'tipo_cuenta_cta_corriente_2' => 'nullable|string',
            'observaciones_cta_corriente_2' => 'nullable|string',

            'saldo_cuenta_ahorro' => 'required|numeric',
            'banco_cuenta_ahorro' => 'nullable|string',
            'numero_cuenta_ahorro' => 'nullable|string',
            'responsable_cuenta_ahorro' => 'nullable|string',
            'tipo_cuenta_cuenta_ahorro' => 'nullable|string',
            'observaciones_cuenta_ahorro' => 'nullable|string',
        ]);

        $orgId = $request->input('orgId');
        // Caja General
        $cuentaCajaGeneral = Cuenta::where('tipo', 'caja_general')->first();
        if ($cuentaCajaGeneral) {
            $cuentaCajaGeneral->banco = $request->banco_caja_general;
            $cuentaCajaGeneral->numero_cuenta = $request->numero_caja_general;
            $cuentaCajaGeneral->save();
            ConfiguracionInicial::create([
                'org_id' => $orgId,
                'cuenta_id' => $cuentaCajaGeneral->id,
                'saldo_inicial' => $request->saldo_caja_general,
                'responsable' => $request->responsable_caja_general,
                'banco' => $request->banco_caja_general,
                'numero_cuenta' => $request->numero_caja_general,
                'tipo_cuenta' => $request->tipo_cuenta_caja_general,
                'observaciones' => $request->observaciones_caja_general ?? null,
            ]);
        }
        // Cuenta Corriente 1
        $cuentaCorriente1 = Cuenta::where('tipo', 'cuenta_corriente_1')->first();
        if ($cuentaCorriente1) {
            $cuentaCorriente1->banco = $request->banco_cta_corriente_1;
            $cuentaCorriente1->numero_cuenta = $request->numero_cta_corriente_1;
            $cuentaCorriente1->save();
            ConfiguracionInicial::create([
                'org_id' => $orgId,
                'cuenta_id' => $cuentaCorriente1->id,
                'saldo_inicial' => $request->saldo_cta_corriente_1,
                'responsable' => $request->responsable_cta_corriente_1,
                'banco' => $request->banco_cta_corriente_1,
                'numero_cuenta' => $request->numero_cta_corriente_1,
                'tipo_cuenta' => $request->tipo_cuenta_cta_corriente_1,
                'observaciones' => $request->observaciones_cta_corriente_1 ?? null,
            ]);
        }
        // Cuenta Corriente 2
        $cuentaCorriente2 = Cuenta::where('tipo', 'cuenta_corriente_2')->first();
        if ($cuentaCorriente2) {
            $cuentaCorriente2->banco = $request->banco_cta_corriente_2;
            $cuentaCorriente2->numero_cuenta = $request->numero_cta_corriente_2;
            $cuentaCorriente2->save();
            ConfiguracionInicial::create([
                'org_id' => $orgId,
                'cuenta_id' => $cuentaCorriente2->id,
                'saldo_inicial' => $request->saldo_cta_corriente_2,
                'responsable' => $request->responsable_cta_corriente_2,
                'banco' => $request->banco_cta_corriente_2,
                'numero_cuenta' => $request->numero_cta_corriente_2,
                'tipo_cuenta' => $request->tipo_cuenta_cta_corriente_2,
                'observaciones' => $request->observaciones_cta_corriente_2 ?? null,
            ]);
        }
        // Cuenta de Ahorro
        $cuentaAhorro = Cuenta::where('tipo', 'cuenta_ahorro')->first();
        if ($cuentaAhorro) {
            $cuentaAhorro->banco = $request->banco_cuenta_ahorro;
            $cuentaAhorro->numero_cuenta = $request->numero_cuenta_ahorro;
            $cuentaAhorro->save();
            ConfiguracionInicial::create([
                'org_id' => $orgId,
                'cuenta_id' => $cuentaAhorro->id,
                'saldo_inicial' => $request->saldo_cuenta_ahorro,
                'responsable' => $request->responsable_cuenta_ahorro,
                'banco' => $request->banco_cuenta_ahorro,
                'numero_cuenta' => $request->numero_cuenta_ahorro,
                'tipo_cuenta' => $request->tipo_cuenta_cuenta_ahorro,
                'observaciones' => $request->observaciones_cuenta_ahorro ?? null,
            ]);
        }

        return redirect()->route('contable.index', ['id' => $request->orgId])->with('success', 'Cuentas iniciales guardadas correctamente.');
    }


    public function index($id)
    {
        $configuraciones = ConfiguracionInicial::with('cuenta')->where('org_id', $id)->get();
        $cuentasIniciales = \App\Models\Cuenta::all();
        $bancos = \App\Models\Banco::orderBy('nombre')->get();
        $categoriasIngresos = \App\Models\Categoria::where('tipo', 'ingreso')->orderBy('nombre')->get();
        $categoriasEgresos = \App\Models\Categoria::where('tipo', 'egreso')->orderBy('nombre')->get();
        $resumen = $this->getResumenSaldos($id);
        // Refuerzo: asegurar que orgId siempre se envía correctamente
        $orgId = $id;
        return view('orgs.contable.index', array_merge([
            'orgId' => $orgId,
            'mostrarLibroCaja' => false,
            'cuentasIniciales' => $cuentasIniciales,
            'configuraciones' => $configuraciones,
            'bancos' => $bancos,
            'categoriasIngresos' => $categoriasIngresos,
            'categoriasEgresos' => $categoriasEgresos,
        ], $resumen));
    }


    public function mostrarLibroCaja($id)
    {
        $movimientos = \App\Models\Movimiento::whereHas('cuentaOrigen', function($q) use ($id) {
            $q->where('org_id', $id);
        })
        ->orderBy('fecha', 'desc')
        ->get();
        $resumen = $this->getResumenSaldos($id);
        return view('orgs.contable.libro-caja', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
        ], $resumen));
    }

    /**
     * Centraliza la obtención de saldos iniciales y objetos de cuentas para el partial y las vistas
     */
    private function getResumenSaldos($orgId)
    {
        $cuentaCajaGeneral = ConfiguracionInicial::where('org_id', $orgId)->whereHas('cuenta', function($q) {
            $q->where('tipo', 'caja_general');
        })->orderByDesc('id')->first();
        $cuentaCorriente1 = ConfiguracionInicial::where('org_id', $orgId)->whereHas('cuenta', function($q) {
            $q->where('tipo', 'cuenta_corriente_1');
        })->orderByDesc('id')->first();
        $cuentaCorriente2 = ConfiguracionInicial::where('org_id', $orgId)->whereHas('cuenta', function($q) {
            $q->where('tipo', 'cuenta_corriente_2');
        })->orderByDesc('id')->first();
        $cuentaAhorro = ConfiguracionInicial::where('org_id', $orgId)->whereHas('cuenta', function($q) {
            $q->where('tipo', 'cuenta_ahorro');
        })->orderByDesc('id')->first();

        // Saldos iniciales
        $saldosIniciales = [
            'Caja General' => $cuentaCajaGeneral->saldo_inicial ?? 0,
            'Cuenta Corriente 1' => $cuentaCorriente1->saldo_inicial ?? 0,
            'Cuenta Corriente 2' => $cuentaCorriente2->saldo_inicial ?? 0,
            'Cuenta de Ahorro' => $cuentaAhorro->saldo_inicial ?? 0,
        ];

        // Saldos actuales
        $cuentaCajaGeneralActual = $cuentaCajaGeneral && $cuentaCajaGeneral->cuenta ? $cuentaCajaGeneral->cuenta->saldo_actual : 0;
        $cuentaCorriente1Actual = $cuentaCorriente1 && $cuentaCorriente1->cuenta ? $cuentaCorriente1->cuenta->saldo_actual : 0;
        $cuentaCorriente2Actual = $cuentaCorriente2 && $cuentaCorriente2->cuenta ? $cuentaCorriente2->cuenta->saldo_actual : 0;
        $cuentaAhorroActual = $cuentaAhorro && $cuentaAhorro->cuenta ? $cuentaAhorro->cuenta->saldo_actual : 0;

        // Suma total inicial y actual
        $totalSaldoInicial = array_sum($saldosIniciales);
        $totalSaldoActual = $cuentaCajaGeneralActual + $cuentaCorriente1Actual + $cuentaCorriente2Actual + $cuentaAhorroActual;
        $saldosIniciales['Saldo Total'] = $totalSaldoInicial + $totalSaldoActual;

        // Totales de movimientos
        $movimientos = \App\Models\Movimiento::whereHas('cuentaOrigen', function($q) use ($orgId) {
            $q->where('org_id', $orgId);
        })->get();
        $totalIngresos = $movimientos->sum(function($m) {
            return ($m->total_consumo ?? 0) + ($m->cuotas_incorporacion ?? 0) + ($m->otros_ingresos ?? 0);
        });
        $totalEgresos = $movimientos->sum(function($m) {
            return ($m->giros ?? 0);
        });
        $saldoFinal = $saldosIniciales['Saldo Total'] + $totalIngresos - $totalEgresos;

        return [
            'saldosIniciales' => $saldosIniciales,
            'cuentaCajaGeneral' => $cuentaCajaGeneral,
            'cuentaCorriente1' => $cuentaCorriente1,
            'cuentaCorriente2' => $cuentaCorriente2,
            'cuentaAhorro' => $cuentaAhorro,
            'totalSaldoInicial' => $totalSaldoInicial,
            'totalSaldoActual' => $totalSaldoActual,
            'totalIngresos' => $totalIngresos,
            'totalEgresos' => $totalEgresos,
            'saldoFinal' => $saldoFinal,
        ];
    }
}