<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\Giro;
use App\Models\Deposito;
use Carbon\Carbon;

class GirosDepositosController extends Controller
{
    public function index(): View
    {
        return view('orgs.contable.giros-depositos', [
            'title' => 'Giros y Depósitos'
        ]);
    }

    public function registrarGiro(Request $request): JsonResponse
    {
        try {
            $saldoActual = $this->calcularSaldoActual();
            if ($request->monto > $saldoActual) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo insuficiente para realizar el giro'
                ], 400);
            }

            $giro = new Giro();
            $giro->fecha = $request->fecha;
            $giro->detalle = $request->detalle;
            $giro->monto = $request->monto;
            $giro->banco_origen = $request->banco_origen ?? 'Caja';
            $giro->banco_destino = $request->banco_destino;
            $giro->numero_operacion = $this->generarNumeroOperacion('GIR');
            $giro->save();

            return response()->json([
                'success' => true,
                'message' => 'Giro registrado exitosamente',
                'data' => $giro
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function registrarDeposito(Request $request): JsonResponse
    {
        try {
            $deposito = new Deposito();
            $deposito->fecha = $request->fecha;
            $deposito->detalle = $request->detalle;
            $deposito->monto = $request->monto;
            $deposito->banco_origen = $request->banco_origen;
            $deposito->banco_destino = $request->banco_destino ?? 'Caja';
            $deposito->numero_operacion = $this->generarNumeroOperacion('DEP');
            $deposito->save();

            return response()->json([
                'success' => true,
                'message' => 'Depósito registrado exitosamente',
                'data' => $deposito
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saldoActual(): JsonResponse
    {
        return response()->json([
            'saldo' => $this->calcularSaldoActual()
        ]);
    }

    public function exportarExcel(Request $request)
    {
        // Implementar exportación
    }

    private function calcularSaldoActual(): float
    {
        $ingresos = \App\Models\Ingreso::sum('monto') ?? 0;
        $egresos = \App\Models\Egreso::sum('monto') ?? 0;
        return $ingresos - $egresos;
    }

    private function generarNumeroOperacion(string $tipo): string
    {
        return $tipo . '-' . Carbon::now()->format('YmdHis') . '-' . rand(1000, 9999);
    }
}
