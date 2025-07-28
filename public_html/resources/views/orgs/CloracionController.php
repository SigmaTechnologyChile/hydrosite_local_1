<?php
namespace App\Http\Controllers\Org;
use App\Http\Controllers\Controller;



class CloracionController extends Controller{
    public function index()
    {
        return view('orgs.aguapotable.macromedidor.index');
    }
}
