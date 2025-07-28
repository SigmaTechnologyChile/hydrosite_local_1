<?php



namespace App\Http\Controllers\Org;



use App\Http\Controllers\Controller;



class MacroMedidorController extends Controller

{

    public function index($orgId = null)
    {
        return view('orgs.aguapotable.macromedidor.index', compact('orgId'));
    }

    // Guardar lectura
    public function guardar(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'frecuencia' => 'required|string',
            'lectura_anterior_extraccion' => 'required|integer',
            'lectura_actual_extraccion' => 'required|integer',
            'extraccion_total' => 'required|integer',
            'lectura_anterior_entrega' => 'required|integer',
            'lectura_actual_entrega' => 'required|integer',
            'entrega_total' => 'required|integer',
            'perdidas_total' => 'required|integer',
            'porcentaje_perdidas' => 'required|string',
            'responsable' => 'required|string',
            'observaciones' => 'nullable|string'
        ]);
        \App\Models\MacromedidorReading::create($data);
        return response()->json(['success' => true]);
    }

    // Listar lecturas
    public function listar(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\MacromedidorReading::query();
        if ($request->filled('anio')) {
            $query->whereYear('fecha', $request->input('anio'));
        }
        if ($request->filled('frecuencia') && $request->input('frecuencia') !== 'todos') {
            $query->where('frecuencia', $request->input('frecuencia'));
        }
        $registros = $query->orderByDesc('fecha')->get();
        return response()->json($registros);
    }
}
