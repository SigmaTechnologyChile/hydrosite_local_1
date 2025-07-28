<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Org;
use App\Models\Location;
use App\Models\Service;
use App\Models\State;
use App\Models\Province;
use App\Models\City;
use App\Exports\LocationsExport;
use Maatwebsite\Excel\Facades\Excel;


class LocationController extends Controller
{
    protected $_param;
    public $org;

    public function __construct()
    {
        $this->middleware('auth');
        $id = \Route::current()->Parameter('id');
        $this->org = Org::find($id);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $org = $this->org;
         // Obtener todas las ubicaciones/sectores de esta organización con paginación
        $locations = Location::join('cities','locations.city_id','cities.id')
                            ->join('provinces','cities.province_id','provinces.id')
                            ->join('states','provinces.state_id','states.id')
                            ->where('org_id', $org->id)
                            ->orderBy('order_by', 'asc')
                            ->select('locations.*','cities.name_city','provinces.name_province','states.name_state')
                            ->paginate(20); // Muestra 20 elementos por página

        // Retornar la vista con los datos necesarios
        return view('orgs.locations.index', compact('org', 'locations'));
    }

    public function create()
    {
        $org = $this->org;

        $states = State::all();
        $provinces = Province::all();
        $cities = City::all();

        return view('orgs.locations.create', compact('org', 'states', 'provinces', 'cities'));
    }

    public function store(Request $request)
    {
        $org = $this->org;

        $request->validate([
            'name' => 'required|string',
            'city_id' => 'required',
        ]);
        // Obtener el último valor de 'order_by' para esta organización y sumarle 1
        $lastOrderBy = Location::where('org_id', $org->id)->max('order_by');
        $newOrderBy = $lastOrderBy ? $lastOrderBy + 1 : 1; // Si no hay ubicaciones, el valor comienza desde 1

        $location = new Location();
        $location->org_id = $org->id;
        $location->city_id = $request->city_id; // O puede venir del formulario
        $location->name = $request->name;
        $location->active = 1;
        $location->order_by = $newOrderBy;
        $location->save();

        return redirect()->route('orgs.locations.index', ['id' => $org->id])
            ->with('success', 'Ubicación creada con éxito');
    }

    public function show($id, $location_id)
    {
        $org = $this->org;
        $location = Location::findOrFail($location_id);

        return view('orgs.locations.show', compact('org', 'location'));
    }
    public function edit($id, $locationId)
    {
        $org = $this->org;
        $location = Location::findOrFail($locationId);

        $states = State::all();
        $provinces = Province::all();
        $cities = City::all();

        return view('orgs.locations.edit', compact('org', 'location', 'states', 'provinces', 'cities'));
    }

    public function update(Request $request, $id, $locationId)
    {

        $org = Org::findOrFail($id);
        $location = Location::findOrFail($locationId);


        $validated = $request->validate([
            'name' => 'required|string|max:255',
            //'state_id' => 'required|exists:states,id',
            //'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
        ]);


        $location->name = $request->input('name');
        //$location->state_id = $request->input('state_id');
        //$location->province_id = $request->input('province_id');
        $location->city_id = $request->input('city_id');
        $location->save();


        return redirect()->route('orgs.locations.index', $org->id)
            ->with('success', 'Sector actualizado correctamente');
    }




    public function getProvinces($stateId)
    {

        $provinces = Province::where('state_id', $stateId)->get();
        return response()->json($provinces);
    }

    public function getCities($provinceId)
    {

        $cities = City::where('province_id', $provinceId)->get();
        return response()->json($cities);
    }

    public function export()
    {
        return Excel::download(new LocationsExport, 'Sectores-'.date('Ymdhis').'.xlsx');
    }

}
