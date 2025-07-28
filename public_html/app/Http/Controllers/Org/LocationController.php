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
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // No accedas a Route::current() aquí
    }

    // Endpoint para obtener clientes por sector (location_id)
    public function clientesPorSector($locationId)
    {
        $clientes = DB::table('services')
            ->where('location_id', $locationId)
            ->orderBy('order_by', 'asc')
            ->get(['id', 'numero as numero_servicio', 'nombre as name', 'telefono as phone', 'order_by as order']);
        return response()->json($clientes);
    }

    public function index($id)
    {
        $org = Org::findOrFail($id);
        $locations = Location::join('cities','locations.city_id','cities.id')
                            ->join('provinces','cities.province_id','provinces.id')
                            ->join('states','provinces.state_id','states.id')
                            ->where('org_id', $org->id)
                            ->orderBy('order_by', 'asc')
                            ->select('locations.*','cities.name_city','provinces.name_province','states.name_state')
                            ->paginate(20);

        return view('orgs.locations.index', compact('org', 'locations'));
    }

    public function create($id)
    {
        $org = Org::findOrFail($id);
        $states = State::all();
        $provinces = Province::all();
        $cities = City::all();

        return view('orgs.locations.create', compact('org', 'states', 'provinces', 'cities'));
    }

    public function store(Request $request, $id)
    {
        $org = Org::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'city_id' => 'required',
        ]);
        $lastOrderBy = Location::where('org_id', $org->id)->max('order_by');
        $newOrderBy = $lastOrderBy ? $lastOrderBy + 1 : 1;

        $location = new Location();
        $location->org_id = $org->id;
        $location->city_id = $request->city_id;
        $location->name = $request->name;
        $location->active = 1;
        $location->order_by = $newOrderBy;
        $location->save();

        return redirect()->route('orgs.locations.index', ['id' => $org->id])
            ->with('success', 'Ubicación creada con éxito');
    }

    public function show($id, $location_id)
    {
        $org = Org::findOrFail($id);
        $location = Location::findOrFail($location_id);

        return view('orgs.locations.show', compact('org', 'location'));
    }

    public function edit($id, $locationId)
    {
        $org = Org::findOrFail($id);
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
            'city_id' => 'required|exists:cities,id',
        ]);

        $location->name = $request->input('name');
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

    public function panelControlRutas($id)
    {
        $org = Org::findOrFail($id);
        $locations = Location::where('org_id', $org->id)
            ->orderBy('order_by', 'asc')
            ->get();

        return view('orgs.locations.panelcontrolrutas', compact('org', 'locations'));
    }
}