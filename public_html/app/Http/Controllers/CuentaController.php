<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    public function index()
    {
        $cuentas = Cuenta::all();
        return view('orgs.contable.cuentas', compact('cuentas'));
    }

    public function create()
    {
        return view('orgs.contable.cuentas-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:cuentas,nombre',
            'tipo' => 'required|in:caja,corriente,ahorro',
        ]);

        Cuenta::create($request->all());
        return redirect()->route('cuentas.index')->with('success', 'Cuenta creada correctamente.');
    }

    public function edit($id)
    {
        $cuenta = Cuenta::findOrFail($id);
        return view('orgs.contable.cuentas-edit', compact('cuenta'));
    }

    public function update(Request $request, $id)
    {
        $cuenta = Cuenta::findOrFail($id);

        $request->validate([
            'nombre' => 'required|unique:cuentas,nombre,' . $id,
            'tipo' => 'required|in:caja,corriente,ahorro',
        ]);

        $cuenta->update($request->all());
        return redirect()->route('cuentas.index')->with('success', 'Cuenta actualizada correctamente.');
    }

    public function destroy($id)
    {
        $cuenta = Cuenta::findOrFail($id);
        $cuenta->delete();
        return redirect()->route('cuentas.index')->with('success', 'Cuenta eliminada correctamente.');
    }
}