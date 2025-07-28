<?php



namespace App\Http\Controllers\Org;



use App\Http\Controllers\Controller;



class MacroMedidorController extends Controller

{

 public function index($orgId)
{
    // Puedes usar el modelo Organization si necesitas validar o cargar datos
    return view('orgs.aguapotable.macromedidor.index', compact('orgId'));
}
}
