<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prueba;

class PruebaController extends Controller
{
    public function index()
    {
        $pruebas = Prueba::all();
        return view('prueba', compact('pruebas'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);
        Prueba::create(['nombre' => $request->nombre]);
        return redirect()->route('prueba.index');
    }
}
