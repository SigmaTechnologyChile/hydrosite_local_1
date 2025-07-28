<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Org;
use App\Models\User;
use App\Models\Member;
use App\Models\Service;
use App\Models\Reading;

class OperatorController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
    public function index($id)
    {
        $org = Org::findOrFail($id);

    return view('orgs.operator.index', compact('org'));
    }

}
