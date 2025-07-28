<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaCuenta;
use Illuminate\Http\Request;

class AuditoriaCuentaController extends Controller
{
    public function index()
    {
        $auditorias = AuditoriaCuenta::with('cuenta')->orderBy('modificado_en', 'desc')->get();
        return view('orgs.contable.auditoria-cuentas', compact('auditorias'));
    }

    public function show($id)
    {
        $auditoria = AuditoriaCuenta::with('cuenta')->findOrFail($id);
        return view('orgs.contable.auditoria-cuentas-show', compact('auditoria'));
    }
}