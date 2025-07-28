<?php

namespace App\Http\Controllers;

use App\Models\Conciliacion;
use App\Models\Movimiento;
use Illuminate\Http\Request;

class ConciliacionController extends Controller
{
    public function index()
    {
        $conciliaciones = Conciliacion::with('movimiento')->get();
        return view('orgs.contable.conciliaciones', compact('conciliaciones'));
    }

    public function create()
    {
        $movimientos = Movimiento::all();
        return view('orgs.contable.conciliaciones-create', compact('movimientos'));
    }

    public function store(Request $request)
    {
                $request->validate([
            'movimiento_id' => 'required|exists:movimientos,id',
            'fecha_conciliacion' => 'required|date',
            'estado' => 'required|in:conciliado,pendiente',
        ]);

        Conciliacion::create($request->all());
        return redirect()->route('conciliaciones.index')->with('success', 'Conciliación registrada correctamente.');
    }

    public function edit($id)
    {
        $conciliacion = Conciliacion::findOrFail($id);
        $movimientos = Movimiento::all();
        return view('orgs.contable.conciliaciones-edit', compact('conciliacion', 'movimientos'));
    }

    public function update(Request $request, $id)
    {
        $conciliacion = Conciliacion::findOrFail($id);

        $request->validate([
            'movimiento_id' => 'required|exists:movimientos,id',
            'fecha_conciliacion' => 'required|date',
            'estado' => 'required|in:conciliado,pendiente',
        ]);

        $conciliacion->update($request->all());
        return redirect()->route('conciliaciones.index')->with('success', 'Conciliación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $conciliacion = Conciliacion::findOrFail($id);
        $conciliacion->delete();
        return redirect()->route('conciliaciones.index')->with('success', 'Conciliación eliminada correctamente.');
    }
}