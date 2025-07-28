<?php



namespace App\Http\Controllers\Org;



use App\Http\Controllers\Controller;

use App\Models\Location;



class OperatorController extends Controller

{

    public function index($orgId)

    {

        // Consulta corregida (usa el nombre correcto de la columna)
//
        $locations = Location::where('org_id', $orgId) // Ajusta este nombre

                            ->orderBy('name')

                            ->get();



        return view('orgs.operator.index', [

      'orgId' => $orgId,

            'locations' => $locations

        ]);

    }

}
