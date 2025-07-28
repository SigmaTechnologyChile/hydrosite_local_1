<?php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Org;
use App\Models\Reading;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        // Asegúrate de tener una relación del usuario con la organización
        $org = $user->org_id;

        return view('dashboard', compact('org'));
    }
    public function dashboard()
    {
        $user = Auth::user();

        $org = null;
        if ($user) {
            $org = $user->org_id;
            // dd($org);
            if ($user->isSuperAdmin()) {
                return redirect()->route('orgs.index', ['org' => $org]);
            } elseif($user->isAdmin()) {
                return redirect()->route('orgs.dashboard', ['id' => $org]);
            } elseif($user->isCrc()) {
                return redirect()->route('orgs.libro.caja', ['id' => $org]);

            } elseif($user->isOperator()) {
                return redirect()->route('orgs.operator.index', ['id' => $org]);
            }
        }
    }

    public function profile()
    {

        return view('profile');
    }

    public function orgs()
    {
        return view('orgs.index');
    }

    protected function access(Request $request)
    {
        $request->validate([
            'account_name' => ['required'],
        ]);
        if (User::where('account_name', $request->input('account_name'))->count()) {
            $url = 'https://' . $request->input('account_name') . '.rederp.cl';
            return \Redirect::to($url);
        } else {
            abort(404);
        }
    }

    protected function debt($rut)
    {
        if (Member::where('rut', $rut)->exists()) {

            $member = Member::where('rut', $rut)->first();

            if (! $member) {
                // Manejo de error si no existe
                abort(404, 'Miembro no encontrado');
            }

// Lecturas impagas del miembro, con datos del servicio
            $readings = Reading::with('service') // ← carga el servicio asociado
                ->where('member_id', $member->id)
                ->where('payment_status', 0)
                ->where('total', '>', 0)
                ->orderBy('period', 'DESC')
                ->get();

// Obtener organizaciones del miembro
            $orgs = $member->orgs;

            return view('accounts.debts', compact('rut', 'readings', "orgs"));
        } else {
            abort(403, 'Rut No encontrado en el sistema, intente con otro o puede comunicarse con soporte@hydrosite.cl');
        }
    }

    public function redirectCrearUsuario($id)
    {
        $org  = Org::where('id', $id)->first();
        $orgs = null;
        if (Auth::user()->perfil_id == 0) {
            $orgs = Org::select('id', 'name')->get();
        }
        return view('orgs.accounts.crearUsuario', compact('org', 'orgs'));
    }

    public function crearUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'rut'       => ['required', 'regex:/^\d{7,8}-[\dkK]$/i', 'unique:users,rut'],
            'password'  => ['required', 'confirmed', 'min:8'],
            'perfil_id' => ['required', 'in:1,3,5'],
            'plan_id'      => ['nullable', 'in:0,1,2'],
            'org_id'    => ['required', 'exists:orgs,id'],
        ]);
 \Log::info("creacion",["data" => $request]);
        \Log::info('Plan recibido', ['plan_id' => $request->input('plan_id')]);
        if ($request->perfil_id == '1') {
            $validator->addRules([
                'plan_id' => ['required', 'in:0,1,2'],
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Error al crear usuario. Revisa los campos.');
        }
       // dd("plan", ["data" => $request]);

          try {

    User::create([
        'name'      => $request->input('name'),
        'email'     => $request->input('email'),
        'rut'       => $request->input('rut'),
        'password'  => Hash::make($request->input('password')),
        'perfil_id' => $request->input('perfil_id'),
        'org_id'    => $request->input('org_id'),
        'plan_id' => $request->filled('plan_id') ? (int) $request->input('plan_id') : 0,
    ]);

    $org = Auth::user()->org_id;

    return redirect()
        ->route('orgs.accounts.crearUsuario', ['id' => $org])
        ->with('success', '✅ Usuario creado correctamente.');
} catch (\Exception $e) {
    \Log::error('Error al crear usuario: ' . $e->getMessage());
    return redirect()->back()->with('error', '❌ Ocurrió un error al crear el usuario.')->withInput();
}
        //return redirect()->route('orgs.accounts.crearUsuario', ['id' => $org])->with('success', 'Usuario creado correctamente.');
    }

}
