<?php
namespace App\Http\Controllers\Org;

use App\Exports\MembersExport;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Member;
use App\Models\Org;
use App\Models\OrgMember;
use App\Models\Service;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class MemberController extends Controller
{
    protected $_param;
    public $org;

    public function __construct()
    {
        $this->middleware('auth');
        $id = \Route::current()->Parameter('id');
        $this->org = Org::find($id);
    }

    public function index(Request $request)
    {

        $org = $this->org;
        $sector = $request->input('sector');
        $search = $request->input('search');

        $members = Member::join('orgs_members', 'members.id', 'orgs_members.member_id')
            ->leftjoin('services', 'members.rut', 'services.rut')
            ->where('orgs_members.org_id', $org->id)
            ->when($sector, function ($q) use ($sector) {
                $q->where('services.locality_id', $sector);
            })
            ->when($search, function ($q) use ($search) {
                $q->where('members.rut', $search)
                    ->orWhere('members.first_name', 'like', '%' . $search . '%')
                    ->orWhere('members.last_name', 'like', '%' . $search . '%')
                    ->orWhere('members.full_name', 'like', '%' . $search . '%');
            })
            ->select(
                'members.id',
                'members.rut',
                'members.full_name',
                'members.first_name',
                'members.last_name',
                'members.gender',
                'members.city_id',
                'members.commune',
                'members.address',
                'members.partner',
                'members.phone',
                'members.mobile_phone',
                'members.email',
                'members.active',
                'members.created_at',
                'members.updated_at',
                'members.deleted_at',
                DB::raw('COUNT(*) as qrx_serv')
            )
            ->groupBy(
                'members.id',
                'members.rut',
                'members.full_name',
                'members.first_name',
                'members.last_name',
                'members.gender',
                'members.city_id',
                'members.commune',
                'members.address',
                'members.partner',
                'members.phone',
                'members.mobile_phone',
                'members.email',
                'members.active',
                'members.created_at',
                'members.updated_at',
                'members.deleted_at'
            )
            ->paginate(20);

        $locations = Location::where('org_id', $org->id)->OrderBy('order_by', 'ASC')->get();
        return view('orgs.members.index', compact('org', 'members', 'locations'));
    }

    public function create()
    {
        $org = $this->org;
        $states = State::all();
        $locations = Location::where('org_id', $org->id)->OrderBy('order_by', 'ASC')->get();
        return view('orgs.members.create', compact('org', 'states', 'locations'));
    }

    public function store(Request $request, $orgId)
    {

        $org = $this->org;

        $rules = [
            'rut' => [
                'required',
                'string',
                'unique:members,rut',
                function ($attribute, $value, $fail) {
                   // $rutLimpio = preg_replace('/[^0-9kK]/', '', strtoupper($value));
                    $rutLimpio = preg_replace('/[^0-9kK\-]/', '', strtoupper($value));
                    if (Member::where('rut', $rutLimpio)->exists()) {
                        $fail('Este RUT ya está registrado en el sistema.');
                        return;
                    }

                    if (strlen($rutLimpio) < 8 || strlen($rutLimpio) > 9) {
                        $fail('El RUT debe tener entre 8 y 9 caracteres (incluyendo dígito verificador).');
                        return;
                    }

                    if (!preg_match('/^[0-9]{7,8}[0-9kK]$/', $rutLimpio)) {
                        $fail('El RUT tiene un formato inválido. Debe ser: 12345678-9 o 1234567-8');
                        return;
                    }

                    $cuerpo = substr($rutLimpio, 0, -1);

                    if (!ctype_digit($cuerpo)) {
                        $fail('El cuerpo del RUT debe contener solo números.');
                        return;
                    }

                    $numeroCuerpo = intval($cuerpo);
                    if ($numeroCuerpo < 1000000) {
                        $fail('El RUT debe ser mayor a 1.000.000.');
                        return;
                    }

                    $suma = 0;
                    $multiplo = 2;

                    for ($i = strlen($cuerpo) - 1; $i >= 0; $i--) {
                        $suma += intval($cuerpo[$i]) * $multiplo;
                        $multiplo = $multiplo < 7 ? $multiplo + 1 : 2;
                    }

                    $dvEsperado = 11 - ($suma % 11);

                    if ($dvEsperado == 11) {
                        $dvCalculado = '0';
                    } elseif ($dvEsperado == 10) {
                        $dvCalculado = 'K';
                    } else {
                        $dvCalculado = (string) $dvEsperado;
                    }

                },
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required|string|max:255',
            'state' => 'required|exists:states,id',
            'commune' => 'required|string|max:100',
            'mobile_phone' => 'required|string|max:15',
            'phone' => 'nullable|string|max:15',
            'partner' => 'required',
            'gender' => 'required|string',
            'activo' => 'nullable|boolean',
        ];


        if ($request->has('activo') && $request->input('activo') == 1) {
            $rules = array_merge($rules, [
                'locality_id' => 'required|numeric',
                'service_state' => 'required|exists:states,id',
                'service_commune' => 'required|string|max:100',
                'service_address' => 'required|string|max:255',
                'meter_plan' => 'required|in:1,0',
                'percentage' => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:100',
                    function ($attribute, $value, $fail) use ($request) {
                        $meterPlan = $request->input('meter_plan');
                        if ($meterPlan == '1' && ($value < 1 || $value > 100)) {
                            $fail('Cuando MIDEPLAN es "Sí", el porcentaje debe ser entre 1 y 100.');
                        }
                        if ($meterPlan == '0' && $value != 0) {
                            $fail('Cuando MIDEPLAN es "No", el porcentaje debe ser 0.');
                        }
                    }
                ],
                'meter_type' => 'required',
                'meter_number' => 'required',
                'invoice_type' => 'required',
                'diameter' => 'required',
                'observations' => 'nullable|string',
            ]);
        }

        $validated = $request->validate($rules);
        $state = State::findOrFail($validated['state']);
        $stateName = $state->name_state;

        $rutLimpio = preg_replace('/[^0-9kK]/', '', strtoupper($validated['rut']));


        $member = new Member();

        $rutLimpio = preg_replace('/[^0-9kK]/', '', strtoupper($validated['rut']));

        $cuerpo = substr($rutLimpio, 0, -1);
        $dv = substr($rutLimpio, -1);
        $rutFormateado = $cuerpo . '-' . $dv;
        $member->rut = $rutFormateado;
        $member->first_name = strtoupper($validated['first_name']);
        $member->last_name = strtoupper($validated['last_name']);
        $member->full_name = $member->first_name . ' ' . $member->last_name;
        $member->city_id = $validated['state'];
        $member->commune = $validated['commune'];
        $member->gender = $validated['gender'];
        $member->email = $validated['email'];
        $member->address = strtoupper($validated['address']);
        $member->partner = $validated['partner'];

        $member->phone = '+56' . ltrim($validated['phone'], '0');
        $member->mobile_phone = '+56' . ltrim($validated['mobile_phone'], '0');
        $member->active = $validated['activo'] ?? 1;
        $member->save();


        $orgMember = new OrgMember();
        $orgMember->org_id = $org->id;
        $orgMember->member_id = $member->id;
        $orgMember->save();

        if ($request->has('activo') && $request->input('activo') == 1) {
            DB::transaction(function () use ($org, $member, $validated, $request) {

                $lastServiceNro = Service::where('org_id', $org->id)
                    ->lockForUpdate()
                    ->max('nro');


                $newNroNumber = $lastServiceNro ? (int) $lastServiceNro + 1 : 1;
                $newNro = (string) $newNroNumber;


                while (Service::where('nro', $newNro)->where('org_id', $org->id)->exists()) {
                    $newNroNumber++;
                    $newNro = (string) $newNroNumber;
                }

                $serviceState = State::findOrFail($validated['service_state']);
                $serviceStateName = $serviceState->name_state;


                $rutLimpio = preg_replace('/[^0-9kK]/', '', strtoupper($validated['rut']));

                $cuerpo = substr($rutLimpio, 0, -1);
                $dv = substr($rutLimpio, -1);
                $rutFormateado = $cuerpo . '-' . $dv;

                $service = new Service();
                $service->member_id = $member->id;
                $service->org_id = $org->id;
                $service->nro = $newNro;
                $service->rut = $rutFormateado;
                $service->commune = $validated['service_commune'];
                $service->address = strtoupper($validated['service_address']);
                $service->state = $serviceStateName;
                $service->locality_id = $validated['locality_id'];
                $service->meter_plan = $validated['meter_plan'];
                $service->percentage = $validated['percentage'];
                $service->meter_type = $validated['meter_type'];
                $service->meter_number = $validated['meter_number'];
                $service->invoice_type = $validated['invoice_type'];
                $service->diameter = $validated['diameter'];
                $service->observations = $request->input('observations');
                $service->active = 1;
                $service->save();
            });
        }

        return redirect()->route('orgs.members.index', $org->id)->with('success', 'Socio creado correctamente.');
    }


    public function dashboard($id)
    {
        $org = $this->org;
        return view('orgs.dashboard', compact('org'));
    }

    public function edit($orgId, $memberId)
    {
        $org = $this->org;
        $member = Member::findOrFail($memberId);
        $city = null;
        if ($member->city_id) {
            $city = State::find($member->city_id);
        }


        $services = Service::where('member_id', $memberId)
            ->leftJoin('locations', 'services.locality_id', '=', 'locations.id')
            ->select(
                'services.*',
                'locations.name as sector_name'
            )
            ->get();

        $states = State::all();

        return view('orgs.members.edit', compact('org', 'member', 'services', 'states', 'city'));
    }

    public function update(Request $request, $orgId, $memberId)
    {

        $validated = $request->validate([
            'rut' => 'required|unique:members,rut,' . $memberId,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'gender' => 'required|in:MASCULINO,FEMENINO,OTRO',
            'state' => 'required',
            'commune' => 'required|string|max:100',
            'mobile_phone' => 'required|string|max:9',
            'phone' => 'nullable|string|max:9',
        ]);

        $org = Org::findOrFail($orgId);
        $member = Member::findOrFail($memberId);

        $rutLimpio = preg_replace('/[^0-9kK]/', '', strtoupper($validated['rut']));
        $cuerpo = substr($rutLimpio, 0, -1);
        $dv = substr($rutLimpio, -1);
        $member->rut = $cuerpo . '-' . $dv;


        $member->first_name = strtoupper($validated['first_name']);
        $member->last_name = strtoupper($validated['last_name']);
        $member->full_name = $member->first_name . ' ' . $member->last_name;
        $member->address = $validated['address'];
        $member->email = $validated['email'];
        $member->gender = $validated['gender'];
        $member->city_id = $validated['state'];
        $member->commune = $validated['commune'];
        $member->mobile_phone = '+56' . ltrim($validated['mobile_phone'], '0');
        $member->phone = '+56' . ltrim($validated['phone'], '0');

        $member->save();

        return redirect()->route('orgs.members.index', $org->id)->with('success', 'Miembro actualizado correctamente.');

    }


    public function transferService(Request $request, $orgId, $memberId, $serviceId)
    {
        try {

            $request->validate([
                'new_rut' => 'required|exists:members,rut',
            ]);

            $org = Org::findOrFail($orgId);
            $member = Member::findOrFail($memberId);

            $service = Service::where('id', $serviceId)
                ->where('member_id', $memberId)
                ->first();

            if (!$service) {
                return redirect()->back()->with('error', 'Servicio no encontrado o no asociado a este miembro.');
            }

            $newMember = Member::where('rut', $request->new_rut)->firstOrFail();

            $service->member_id = $newMember->id;
            $service->save();

            return redirect()->route('orgs.members.services.index', [$orgId, $newMember->id])
                ->with('success', 'Servicio transferido correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al transferir el servicio. Inténtalo de nuevo.');
        }
    }

 public function editService($orgId, $memberId, $serviceId)
    {
        $org = Org::findOrFail($orgId);
        $member = Member::findOrFail($memberId);

        $service = Service::where('services.id', $serviceId)
            ->where('services.member_id', $memberId)
            ->leftJoin('locations', 'services.locality_id', '=', 'locations.id')
            ->select(
                'services.*',
                'locations.name as sector_name'
            )
            ->first();

        if (!$service) {
            return redirect()->back()->with('error', 'Servicio no encontrado.');
        }

        $sectors = Location::where('org_id', $orgId)->get();
        $states = State::orderBy('name_state')->get();


        $serviceStateId = null;
        if ($service->state) {
            $serviceState = State::where('name_state', $service->state)->first();
            $serviceStateId = $serviceState ? $serviceState->id : null;
        }

        $city = null;
        if ($member->city_id) {
            $city = State::find($member->city_id);
        }

        return view('orgs.members.editservice', compact('org', 'member', 'service', 'sectors', 'states', 'city', 'serviceStateId'));
    }

    public function updateService(Request $request, $orgId, $memberId, $serviceId)
    {
        $validated = $request->validate([
            'sector' => 'required|exists:locations,id',
            'meter_plan' => 'required|in:0,1',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'meter_type' => 'required|in:analogico,digital',
            'meter_number' => 'required|string',
            'invoice_type' => 'required|in:boleta,factura',
            'diameter' => 'required|in:1/2,3/4',
            'service_state' => 'required|exists:states,id',
            'service_commune' => 'required|string|max:100',
            'service_address' => 'required|string|max:255',
        ]);

        $service = Service::findOrFail($serviceId);

        $location = Location::findOrFail($validated['sector']);


        $serviceState = State::findOrFail($validated['service_state']);
        $serviceStateName = $serviceState->name_state;

        $service->state = $serviceStateName;
        $service->locality_id = $validated['sector'];
        $service->sector = $location->name;
        $service->commune = $validated['service_commune'];
        $service->address = strtoupper($validated['service_address']);
        $service->meter_plan = (int) $validated['meter_plan'];

        if ($validated['meter_plan'] == 1) {
            $service->percentage = $validated['percentage'] ?? 0;
        } else {
            $service->percentage = 0;
        }

        $service->meter_type = $validated['meter_type'];
        $service->meter_number = $validated['meter_number'];
        $service->invoice_type = $validated['invoice_type'];
        $service->diameter = $validated['diameter'];
        $service->active = $request->has('active') ? 1 : 0;

        $service->save();

        return redirect()->route('orgs.members.edit', [$orgId, $memberId])
            ->with('success', 'Servicio actualizado correctamente.');
    }

    public function toggleStatus($orgId, $memberId, $serviceId)
    {

        $service = Service::findOrFail($serviceId);


        $service->active = !$service->active;
        $service->save();


        return redirect()->route('orgs.members.edit', [$orgId, $memberId])
            ->with('success', 'Estado del servicio actualizado exitosamente.');
    }

    public function export()
    {
        return Excel::download(new MembersExport, 'Members-' . date('Ymdhis') . '.xlsx');
    }
}
