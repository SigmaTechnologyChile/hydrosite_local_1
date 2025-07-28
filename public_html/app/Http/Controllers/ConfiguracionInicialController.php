<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionInicial;
use App\Models\Cuenta;
use App\Models\Banco;
use Illuminate\Http\Request;

class ConfiguracionInicialController extends Controller
{
    /**
     * Sincroniza los saldos iniciales de configuracion_cuentas_iniciales con saldo_actual en cuentas
     */
    public function sincronizarSaldosIniciales()
    {
        $configs = \App\Models\ConfiguracionInicial::all();
        foreach ($configs as $config) {
            $cuenta = \App\Models\Cuenta::find($config->cuenta_id);
            if ($cuenta) {
                $cuenta->saldo_actual = $config->saldo_inicial;
                $cuenta->save();
            }
        }
        return redirect()->back()->with('success', 'Saldos iniciales sincronizados correctamente.');
    }
    public function edit($id)
    {
        $configuracion = ConfiguracionInicial::findOrFail($id);
        $cuentas = Cuenta::all();
        $bancos = Banco::orderBy('nombre')->get();
        return view('orgs.contable.configuracion-inicial-edit', compact('configuracion', 'cuentas', 'bancos'));
    }

    public function update(Request $request, $id)
    {
        $configuracion = ConfiguracionInicial::findOrFail($id);
        $request->validate([
            'cuenta_id' => 'required|exists:cuentas,id|unique:configuracion_cuentas_iniciales,cuenta_id,' . $id,
            'saldo_inicial' => 'required|numeric',
            'responsable' => 'required|string|max:100',
            'banco_id' => 'required|exists:bancos,id',
            'numero_cuenta' => 'nullable|string|max:50',
            'tipo_cuenta' => 'required|in:caja_general,cuenta_corriente_1,cuenta_corriente_2,cuenta_ahorro',
        ]);
        $data = $request->only(['cuenta_id','saldo_inicial','responsable','banco_id','numero_cuenta','tipo_cuenta']);
        $data['saldo_inicial'] = is_numeric($data['saldo_inicial']) ? intval($data['saldo_inicial']) : 0;
        $configuracion->update($data);
        $cuenta = Cuenta::find($data['cuenta_id']);
        if ($cuenta) {
            if (isset($data['numero_cuenta'])) {
                $cuenta->numero_cuenta = $data['numero_cuenta'];
            }
            // Sincronizar saldo_actual con saldo_inicial automáticamente
            $cuenta->saldo_actual = $data['saldo_inicial'];
            $cuenta->save();
        }

        return redirect()->route('configuracion-inicial.index')->with('success', 'Configuración inicial actualizada correctamente.');
    }

    public function index()
    {
        $orgId = auth()->user()->org_id ?? 1;
        $configuraciones = ConfiguracionInicial::with('cuenta')->where('org_id', $orgId)->get();
        $saldos = [
            'caja_general' => ConfiguracionInicial::where('org_id', $orgId)->whereHas('cuenta', function($q) { $q->where('tipo', 'caja_general'); })->sum('saldo_inicial'),
            'cuenta_corriente_1' => ConfiguracionInicial::where('org_id', $orgId)->whereHas('cuenta', function($q) { $q->where('tipo', 'cuenta_corriente_1'); })->sum('saldo_inicial'),
            'cuenta_corriente_2' => ConfiguracionInicial::where('org_id', $orgId)->whereHas('cuenta', function($q) { $q->where('tipo', 'cuenta_corriente_2'); })->sum('saldo_inicial'),
            'cuenta_ahorro' => ConfiguracionInicial::where('org_id', $orgId)->whereHas('cuenta', function($q) { $q->where('tipo', 'cuenta_ahorro'); })->sum('saldo_inicial'),
        ];
        $saldoTotal = array_sum($saldos);
        $cuentasIniciales = Cuenta::where('org_id', $orgId)->get(['id','tipo','nombre']);
        $bancos = Banco::orderBy('nombre')->get();
        return view('orgs.contable.index', compact('configuraciones', 'saldos', 'saldoTotal', 'cuentasIniciales', 'bancos'));
    }

    public function create()
    {
        $cuentas = Cuenta::all();
        $bancosFijos = [
            'Banco de Chile', 'Banco Santander', 'Banco BCI', 'Banco Estado', 'Scotiabank Chile',
            'Banco Itaú', 'Banco Security', 'Banco Falabella', 'Banco Ripley', 'Banco Consorcio',
            'Banco Internacional', 'Banco BICE', 'Banco BTG Pactual Chile', 'Banco BICE Life',
            'Tenpo', 'MACH', 'Chek', 'Superdigital', 'Coopeuch', 'Fintual', 'RappiPay', 'Global66'
        ];
        $bancosDB = ConfiguracionInicial::distinct()->pluck('banco')->toArray();
        $bancos = array_unique(array_merge($bancosFijos, $bancosDB));
        sort($bancos);
        return view('orgs.contable.configuracion-inicial-create', compact('cuentas', 'bancos'));
    }

    public function store(Request $request)
    {
        \Log::info('Entrando a store de ConfiguracionInicialController');
        $cuentas = $request->input('cuentas', []);
        \Log::info('Contenido de $cuentas', ['cuentas' => $cuentas]);
        $responsable = $request->input('responsable');
        // Usa orgId del usuario autenticado si no viene en el request
        $orgId = $request->input('orgId') ?? (auth()->user()->org_id ?? 1);
        $tipos = [
            'caja_general' => 'caja_general',
            'cuenta_corriente_1' => 'cuenta_corriente_1',
            'cuenta_corriente_2' => 'cuenta_corriente_2',
            'cuenta_ahorro' => 'cuenta_ahorro',
        ];
        $errores = [];        
        \DB::beginTransaction();
        try {
            foreach ($tipos as $key => $tipoCuenta) {
                if (!isset($cuentas[$key])) continue;
                $data = $cuentas[$key];
                // Buscar cuenta_id por tipo y organización
                $cuenta = Cuenta::where('tipo', $tipoCuenta)->where('org_id', $orgId)->first();
                if (!$cuenta) {
                    $errores[] = "No existe la cuenta para tipo: $tipoCuenta";
                    continue;
                }
                // Validar datos mínimos
                $validador = \Validator::make($data, [
                    'saldo_inicial' => 'required|numeric',
                    'banco_id' => 'required|exists:bancos,id',
                    'numero_cuenta' => 'nullable|string|max:50',
                ]);
                if ($validador->fails()) {
                    \Log::error('Validación fallida para ' . $tipoCuenta . ': ' . json_encode($validador->errors()->all()));
                    $errores[] = "Datos inválidos para $tipoCuenta: " . implode(' ', $validador->errors()->all());
                    continue;
                }
                \Log::info('Intentando guardar configuración inicial', [
                    'org_id' => $orgId,
                    'cuenta_id' => $cuenta->id,
                    'tipo_cuenta' => $tipoCuenta,
                    'data' => $data
                ]);
                ConfiguracionInicial::updateOrCreate(
                    [
                        'org_id' => $orgId,
                        'cuenta_id' => $cuenta->id,
                    ],
                    [
                        'saldo_inicial' => is_numeric($data['saldo_inicial']) ? intval($data['saldo_inicial']) : 0,
                        'responsable' => $responsable,
                        'banco_id' => $data['banco_id'],
                        'numero_cuenta' => $data['numero_cuenta'] ?? null,
                        'tipo_cuenta' => $tipoCuenta,
                    ]
                );
                // Actualizar el número de cuenta en la tabla cuentas
                if (isset($data['numero_cuenta'])) {
                    $cuenta->numero_cuenta = $data['numero_cuenta'];
                    $cuenta->save();
                }
                // Sincronizar saldo_actual con saldo_inicial automáticamente
                $cuenta->saldo_actual = $data['saldo_inicial'];
                $cuenta->save();
            }
            \DB::commit();
            if (count($errores) > 0) {
                return back()->withErrors($errores);
            }
            return redirect()->route('configuracion-inicial.index')->with('success', 'Configuraciones iniciales guardadas correctamente.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error en store: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }




    // Puedes agregar aquí el método destroy si lo necesitas en el futuro.
}