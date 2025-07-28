<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalisisController extends Controller
{
    public function index($org)
    {
        return view('orgs.aguapotable.analisis.index', compact('org'));
    }
}
