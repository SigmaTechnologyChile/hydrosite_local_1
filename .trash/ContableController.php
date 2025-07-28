<?php



namespace App\Http\Controllers\Org;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;



class ContableController extends Controller

{

public function index($id)
{
    return view('orgs.contable.index', [
        'orgId' => $id,
        'mostrarLibroCaja' => false,
    ]);
}

public function mostrarLibroCaja($id)
{
    return view('orgs.contable.index', [
        'orgId' => $id,
        'mostrarLibroCaja' => true,
    ]);
}

}
