<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MacromedidorController extends Controller
{
    public function historial()
    {
        return view('orgs.aguapotable.macromedidor.historial');
    }
}