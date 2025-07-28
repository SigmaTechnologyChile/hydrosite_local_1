<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory\Response;

use App\Models\State;
use App\Models\Province;
use App\Models\City;
use App\Models\Member;

class AjaxController extends Controller
{
    public function state()
    {
        $states = State::all();
        return response()->json($states);
    }

    public function provinces($state_id)
    {
        $states = Province::where('state_id', $state_id)->get();
        return response()->json($states);
    }

    public function cities($province_id)
    {
        $cities = City::where('province_id', $province_id)->get();
        return response()->json($cities);
    }
    public function citiesByState($stateId)
    {
        try {
            $cities = City::join('provinces', 'cities.province_id', '=', 'provinces.id')
                ->where('provinces.state_id', $stateId)
                ->select('cities.id', 'cities.name_city as name')
                ->orderBy('cities.name_city', 'ASC')
                ->get();

            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar comunas'], 500);
        }
    }

    public function checkRut(Request $request)
    {
        $rut = $request->input('rut');
       // $rutLimpio = preg_replace('/[^0-9kK]/', '', strtoupper($rut));
       $rutLimpio = preg_replace('/[^0-9kK\-]/', '', strtoupper($rut));
        $exists = Member::where('rut', $rutLimpio)->exists();

        return response()->json(['exists' => $exists]);
    }
}
