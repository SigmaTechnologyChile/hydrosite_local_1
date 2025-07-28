<?php
namespace App\Http\Controllers\Org;

use App\Exports\ReadingsExport;
use App\Exports\ReadingsHistoryExport;
use App\Http\Controllers\Controller;
use App\Models\FixedCostConfig;
use App\Models\Location;
use App\Models\Member;
use App\Models\Org;
use App\Models\Reading;
use App\Models\Section;
use App\Models\Service;
use App\Models\TierConfig;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ReadingController extends Controller
{
    protected $_param;
    public $org;

    public function __construct()
    {
        $this->middleware('auth');
        $id        = \Route::current()->Parameter('id');
        $this->org = Org::find($id);
    }

    /**
     * Show the application dashboard.
     * @param int $org_id
     * @return \Illuminate\Contracts\Support\Renderable
     */
public function index($org_id, Request $request)
{
    $org = $this->org;

    if (! $org) {
        return redirect()->back()->with('error', 'Organización no encontrada.');
    }

    $sector = $request->input('sector');
    $search = $request->input('search');
    $year   = $request->input('year');
    $month  = $request->input('month');

    if ($month && strlen($month) == 1) {
        $month = '0' . $month;
    }

    $period = null;
    if ($year && $month) {
        $period = "$year-$month";
    }

    $query = DB::table('readings')
        ->join('services', 'readings.service_id', '=', 'services.id')
        ->join('members', 'services.member_id', '=', 'members.id')
        ->leftJoin('locations', 'services.locality_id', '=', 'locations.id')
        ->where('readings.org_id', $org_id);

    if ($sector && $sector != '0') {
        $query->where('services.locality_id', $sector);
    }

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('members.rut', 'like', "%{$search}%")
              ->orWhere('members.full_name', 'like', "%{$search}%");
        });
    }

    if ($period) {
        $query->where('readings.period', 'like', $period . '%');
    }

    $readings = $query->select(
            'readings.id',
            'readings.org_id',            // <== agregada
            'readings.period',
            'readings.cm3',               // nombre original
            'readings.invoice_type',
            'readings.vc_water',
            'readings.v_subs',
            'readings.total',
            'readings.payment_status',
            'services.nro',
            'services.member_id',
            'services.id as service_id',
            'members.id as member_id',
            'members.rut',
            'members.full_name',
            'readings.corte_reposicion',
            'readings.other',
            'locations.name as location_name',
           'readings.current_reading',     // <-- esto es lo que tú quieres como current
    'readings.previous_reading',   // valor anterior del medidor
          DB::raw('(
        SELECT r2.current_reading
        FROM readings r2
        WHERE r2.service_id = readings.service_id
          AND r2.period < readings.period
        ORDER BY r2.period DESC
        LIMIT 1
    ) as previous_month_reading')
)
        ->orderBy('readings.period', 'desc')
        ->paginate(20)
        ->withQueryString();




    $locations = DB::table('locations')
        ->where('org_id', $org->id)
        ->orderBy('order_by', 'ASC')
        ->get();

         //  dd("query",["data"=>$readings]);

    return view('orgs.readings.index', compact('org', 'readings', 'locations'));
}

    public function history($org_id, Request $request)
    {
        $org = $this->org;

        if (! $org) {
            return redirect()->back()->with('error', 'Organización no encontrada.');
        }

        $start_date = $request->input('start_date');
        $end_date   = $request->input('end_date');
        $sector     = $request->input('sector');
        $search     = $request->input('search');

        if ($start_date) {
            $start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->format('Y-m');
        }
        if ($end_date) {
            $end_date = \Carbon\Carbon::createFromFormat('Y-m-d', $end_date)->format('Y-m');
        }

        $readings = Reading::join('services', 'readings.service_id', 'services.id')
            ->join('members', 'services.member_id', 'members.id')
        //->leftjoin('locations','services.locality_id','locations.id')
            ->where('readings.org_id', $org_id)
            ->when($start_date && $end_date, function ($q) use ($start_date, $end_date) {
                $q->where('readings.period', '>=', $start_date)
                    ->where('readings.period', '<=', $end_date);
            })
            ->when($sector, function ($q) use ($sector) {
                $q->where('services.locality_id', $sector);
            })
            ->when($search, function ($q) use ($search) {
                $q->where('members.rut', $search)
                    ->orWhere('members.full_name', 'like', '%' . $search . '%');
            })
            ->select('readings.*', 'services.nro', 'members.rut', 'members.full_name', 'services.sector as location_name')
            ->orderBy('period', 'desc')->paginate(20)
            ->withQueryString();



        $locations = Location::where('org_id', $org->id)->orderby('order_by', 'ASC')->get();

        return view('orgs.readings.history', compact('org', 'readings', 'locations'));
    }

    public function current_reading_update($id, Request $request)
    {
        $request->validate([
            'reading_id'      => 'required|numeric',
            'current_reading' => 'required|numeric|min:0',
        ]);

        try {
            $org     = $this->org;
            $reading = Reading::findOrFail($request->reading_id);

            $this->updateReading($org, $reading, $request->only(['current_reading']));

             return redirect()->back()->with('success', 'Lectura actualizada correctamente');
        } catch (\Exception $e) {
             return redirect()->back()->with('danger', 'Error al actualizar lectura: ' . $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'reading_id'      => 'required|numeric',
            'current_reading' => 'required|numeric|min:0',
            'multas_vencidas' => 'nullable|numeric|min:0',
        ]);

        try {
            $org     = $this->org;
            $reading = Reading::findOrFail($request->reading_id);

            $this->updateReading($org, $reading, $request->all());

            return redirect()->back()->with('success', 'Actualización de lectura correcta');
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'Error al actualizar la lectura: ' . $e->getMessage());
        }
    }

    private function updateReading($org, $reading, $data)
    {
        $tier       = TierConfig::where('org_id', $org->id)->OrderBy('id', 'ASC')->get();
        $configCost = FixedCostConfig::where('org_id', $org->id)->first();

        $reading->previous_reading = $data['previous_reading'] ?? $reading->previous_reading;
        $reading->current_reading  = $data['current_reading'];

        $reading->cm3 = max(0, $reading->current_reading - $reading->previous_reading);

        $service              = Service::findOrFail($reading->service_id);
        $cargo_fijo           = $configCost->fixed_charge_penalty;
        $subsidio             = $service->meter_plan; // 0 o 1
        $porcentaje_subsidio  = $service->percentage / 100;
        $consumo_agua_potable = 0;
        $subsidioDescuento    = 0;
        $cm3                  = $reading->cm3;
        $consumoNormal        = 0;

        $tramos = [];
        foreach ($tier as $t) {
            $tramos[] = [
                'hasta'  => $t->range_to,
                'precio' => $t->value,
            ];
        }
        if (empty($tramos) || end($tramos)['hasta'] < PHP_INT_MAX) {
            $tramos[] = [
                'hasta'  => PHP_INT_MAX,
                'precio' => end($tramos)['precio'] ?? 0,
            ];
        }

        $anterior = 0;
        $restante = $cm3;

        for ($i = 0; $i < count($tramos) && $restante > 0; $i++) {
            $limite = $tramos[$i]['hasta'];
            $precio = $tramos[$i]['precio'];

            $cantidad = min($restante, $limite - $anterior);

            if ($i === 0 && $subsidio != 0) {
                $cantidadSubvencionada = min($configCost->max_covered_m3, $cantidad);
                $cantidadNormal        = $cantidad - $cantidadSubvencionada;
                $precioConSubsidio     = $precio * (1 - $porcentaje_subsidio); // esto es el 0.4 o 0.6? es lo que se descuenta o el total, deverisa ser el descuento, osea el 0.4 el que se muestra
                $consumo_agua_potable += $cantidadSubvencionada * $precioConSubsidio;
                $consumo_agua_potable += $cantidadNormal * $precio;
                $consumoNormal += $cantidad * $precio;
                $subsidioDescuento = $cantidadSubvencionada * ($precio - $precioConSubsidio);
            } else {
                $consumo_agua_potable += $cantidad * $precio;
                $consumoNormal += $cantidad * $precio;
            }

            $restante -= $cantidad;
            $anterior = $limite;
        }

        $reading->vc_water = $consumoNormal;
        $reading->v_subs   = $subsidioDescuento;

        // Manejar las multas vencidas (800 o 1600)
        $multas_vencidas = 0;
        if (isset($data['cargo_mora'])) {
            $multas_vencidas = max($multas_vencidas, $configCost->late_fee_penalty);
        }
        if (isset($data['cargo_vencido'])) {
            $multas_vencidas = max($multas_vencidas, $configCost->expired_penalty);
        }
        $reading->multas_vencidas = $multas_vencidas;

        $reading->corte_reposicion = isset($data['cargo_corte_reposicion']) ?
        ($configCost->replacement_penalty) : 0;
        $reading->other = $data['other'] ?? $reading->other;

        $subtotal_consumo_mes  = $consumo_agua_potable + $cargo_fijo;
        $reading->total_mounth = $subtotal_consumo_mes;
        $subTotal              = $subtotal_consumo_mes + $reading->multas_vencidas + $reading->corte_reposicion + $reading->other + $reading->s_previous;
        $reading->sub_total    = $subTotal;

        if ($reading->invoice_type && $reading->invoice_type != "boleta") {
            $iva            = $subTotal * 0.19;
            $reading->total = $subTotal + $iva;
        } else {
            $reading->total = $subTotal;
        }

        $reading->save();
    }

public function dte($id, $readingId)
{
    \Log::info('Recibiendo solicitud para DTE con ID org: ' . $id . ' y readingId: ' . $readingId);

    // Obtener organización
    $org = DB::table('orgs')->where('id', $id)->first();
    if (!$org) {
        abort(404, 'Organización no encontrada');
    }

    // Obtener lectura con validación de organización
    $reading = DB::table('readings')
        ->where('id', $readingId)
        ->where('org_id', $id)
        ->first();

    if (!$reading) {
        abort(404, 'Lectura no encontrada o no pertenece a esta organización');
    }

    // Obtener servicio asociado
    $service = DB::table('services')
        ->where('id', $reading->service_id)
        ->first();

    if (!$service) {
        abort(404, 'Servicio no encontrado');
    }

    // Obtener miembro asociado
    $member = DB::table('members')
        ->where('id', $service->member_id)
        ->first();

    if (!$member) {
        abort(404, 'Miembro no encontrado');
    }

    // Añadir datos de miembro y servicio al objeto lectura
    $reading->member = $member;
    $reading->service = $service;

    // Obtener configuración de tramos
    $tier = DB::table('tier_config')
        ->where('org_id', $id)
        ->orderBy('id', 'ASC')
        ->get();


    if ($tier->isEmpty()) {
        \Log::error("No se encontraron secciones para la organización con ID: {$id}");
        abort(404, 'No se encontraron secciones.');
    }

    // Obtener configuración de costos fijos
    $configCost = DB::table('fixed_costs_config')
        ->where('org_id', $org->id)
        ->first();

    if (!$configCost) {
        \Log::error("No se encontró configuración de costos fijos para la organización con ID: {$org->id}");
        abort(404, 'Configuración de costos fijos no encontrada.');
    }

    // Obtener lectura anterior
    $readingAnterior = DB::table('readings')
        ->where('member_id', $member->id)
        ->where('service_id', $service->id)
        ->where('period', '<', $reading->period)
        ->orderBy('period', 'desc')
        ->first();

    // Cálculo de tramos (igual que antes)
    $consumo = $reading->cm3;
    $detalle_sections = [];
    $consumo_restante = $consumo;
    $anterior = 0;

     foreach ($tier as $index => $tierConfig) {
            if ($consumo_restante <= 0) {
                // Si no queda consumo, este tramo tendrá 0 m3
                $tierConfig->section            = $tierConfig->range_from . " Hasta " . $tierConfig->range_to;
                $tierConfig->m3                 = 0;
                $tierConfig->precio             = $tierConfig->value;
                $tierConfig->total              = 0;
                $tierConfig->total_sin_subsidio = 0;
                $tierConfig->subsidio_aplicado  = 0;
            } else {
                $limite_tramo = $tierConfig->range_to;

                // Si es el último tramo, asignar todo el consumo restante
                $es_ultimo_tramo = ($index == count($tier) - 1);

                if ($es_ultimo_tramo) {
                    // En el último tramo, asignar todo el consumo restante
                    $m3_en_este_tramo = $consumo_restante;
                } else {
                    // En tramos intermedios, calcular la capacidad del tramo
                    $capacidad_tramo  = $limite_tramo - $anterior;
                    $m3_en_este_tramo = min($capacidad_tramo, $consumo_restante);
                }

                $tierConfig->section = $tierConfig->range_from . " Hasta " . $tierConfig->range_to;
                $tierConfig->m3      = $m3_en_este_tramo;
                $tierConfig->precio  = $tierConfig->value;

                // SIEMPRE mostrar el precio completo sin subsidio en la tabla de tramos
                $tierConfig->total = $m3_en_este_tramo * $tierConfig->value;

                // Reducir el consumo restante
                $consumo_restante -= $m3_en_este_tramo;
                $anterior = $limite_tramo;

                \Log::info("Tramo {$tierConfig->range_from}-{$tierConfig->range_to}: {$m3_en_este_tramo} m3, Total sin subsidio: {$tierConfig->total}, Restante: {$consumo_restante}");
            }

            $detalle_sections[] = $tierConfig;
        }

        // Calcular el total de todos los tramos (sin subsidio aplicado)


    // Cálculos de montos (igual que antes)
    $total_tramos_sin_subsidio = array_sum(array_column($detalle_sections, 'total'));
    $cargo_fijo = $configCost->fixed_charge_penalty;
    $consumo_agua_potable = $total_tramos_sin_subsidio;
    $subsidio_descuento = $reading->v_subs ?? 0;
    $subtotal_consumo = $consumo_agua_potable + $cargo_fijo - $subsidio_descuento;

    $subtotal_con_cargos = $subtotal_consumo +
        ($reading->multas_vencidas ?? 0) +
        ($reading->corte_reposicion ?? 0) +
        ($reading->other ?? 0) +
        ($reading->s_previous ?? 0);

    // Determinar tipo de documento
    $routeName = \Route::currentRouteName();
    $docType = str_contains($routeName, 'factura') ? 'factura' : 'boleta';

    if ($docType === 'factura') {
        $iva = $subtotal_con_cargos * 0.19;
        $total_con_iva = $subtotal_con_cargos + $iva;
        return view('orgs.factura', compact('reading', 'org', 'detalle_sections', 'tier', 'configCost', 'subtotal_consumo', 'subtotal_con_cargos', 'iva', 'total_con_iva', 'consumo_agua_potable', 'subsidio_descuento', 'readingAnterior'));
    } else {
        return view('orgs.boleta', compact('reading', 'org', 'detalle_sections', 'tier', 'configCost', 'subtotal_consumo',  'consumo_agua_potable', 'subsidio_descuento', 'readingAnterior'));
    }
}

      public function multiBoletaPrint($id, $readingId)
    {
        \Log::info('Recibiendo solicitud para DTE con ID org: ' . $id . ' y readingId: ' . $readingId);

        $org              = Org::findOrFail($id);
        $reading          = Reading::findOrFail($readingId);
        $reading->member  = Member::findOrFail($reading->member_id);
        $reading->service = Service::findOrFail($reading->service_id);
        $tier             = TierConfig::where('org_id', $id)->OrderBy('id', 'ASC')->get();
        $configCost       = FixedCostConfig::where('org_id', $org->id)->first();

        $readingAnterior = Reading::where('member_id', $reading->member_id)
            ->where('service_id', $reading->service_id)
            ->where('period', '<', $reading->period)
            ->orderBy('period', 'desc')
            ->first();

        if ($tier->isEmpty()) {
            \Log::error("No se encontraron secciones para la organización con ID: {$id}");
            abort(404, 'No se encontraron secciones.');
        }

        if (! $configCost) {
            \Log::error("No se encontró configuración de costos fijos para la organización con ID: {$org->id}");
            abort(404, 'Configuración de costos fijos no encontrada.');
        }

        // Obtener datos del servicio para el subsidio
        $service             = Service::findOrFail($reading->service_id);
        $subsidio            = $service->meter_plan; // 0 o 1
        $porcentaje_subsidio = $service->percentage / 100;

        // Asegurándonos de que el consumo es mayor que 0
        $consumo = $reading->cm3;
        \Log::info("Consumo inicial: " . $consumo);

        $detalle_sections = [];
        $consumo_restante = $consumo;
        $anterior         = 0;

        foreach ($tier as $index => $tierConfig) {
            if ($consumo_restante <= 0) {
                // Si no queda consumo, este tramo tendrá 0 m3
                $tierConfig->section            = $tierConfig->range_from . " Hasta " . $tierConfig->range_to;
                $tierConfig->m3                 = 0;
                $tierConfig->precio             = $tierConfig->value;
                $tierConfig->total              = 0;
                $tierConfig->total_sin_subsidio = 0;
                $tierConfig->subsidio_aplicado  = 0;
            } else {
                $limite_tramo = $tierConfig->range_to;

                // Si es el último tramo, asignar todo el consumo restante
                $es_ultimo_tramo = ($index == count($tier) - 1);

                if ($es_ultimo_tramo) {
                    // En el último tramo, asignar todo el consumo restante
                    $m3_en_este_tramo = $consumo_restante;
                } else {
                    // En tramos intermedios, calcular la capacidad del tramo
                    $capacidad_tramo  = $limite_tramo - $anterior;
                    $m3_en_este_tramo = min($capacidad_tramo, $consumo_restante);
                }

                $tierConfig->section = $tierConfig->range_from . " Hasta " . $tierConfig->range_to;
                $tierConfig->m3      = $m3_en_este_tramo;
                $tierConfig->precio  = $tierConfig->value;

                // SIEMPRE mostrar el precio completo sin subsidio en la tabla de tramos
                $tierConfig->total = $m3_en_este_tramo * $tierConfig->value;

                // Reducir el consumo restante
                $consumo_restante -= $m3_en_este_tramo;
                $anterior = $limite_tramo;

                \Log::info("Tramo {$tierConfig->range_from}-{$tierConfig->range_to}: {$m3_en_este_tramo} m3, Total sin subsidio: {$tierConfig->total}, Restante: {$consumo_restante}");
            }

            $detalle_sections[] = $tierConfig;
        }

        // Calcular el total de todos los tramos (sin subsidio aplicado)
        $total_tramos_sin_subsidio = array_sum(array_column($detalle_sections, 'total'));

        \Log::info("Total de tramos sin subsidio: {$total_tramos_sin_subsidio}");
        \Log::info("vc_water de reading (con subsidio aplicado): {$reading->vc_water}");
        \Log::info("v_subs de reading (subsidio a descontar): {$reading->v_subs}");

        // Valores fijos
        $cargo_fijo           = $configCost->fixed_charge_penalty;
        $consumo_agua_potable = $total_tramos_sin_subsidio; // Usar el total de tramos sin subsidio
        $subsidio_descuento   = $reading->v_subs ?? 0;      // Subsidio ya calculado

        $subtotal_consumo = $consumo_agua_potable + $cargo_fijo - $subsidio_descuento;

        // Verificando el subtotal
        \Log::info("Consumo agua potable (tramos sin subsidio): " . $consumo_agua_potable);
        \Log::info("Cargo fijo: " . $cargo_fijo);
        \Log::info("Subsidio a descontar: " . $subsidio_descuento);
        \Log::info("Subtotal de consumo (después de restar subsidio): " . $subtotal_consumo);

        $subtotal_con_cargos = $subtotal_consumo +
            ($reading->multas_vencidas ?? 0) +
            ($reading->corte_reposicion ?? 0) +
            ($reading->other ?? 0) +
            ($reading->s_previous ?? 0);

        // Definir el IVA solo si el tipo de documento es factura
        $iva           = 0;
        $total_con_iva = $subtotal_con_cargos;

$routeName = \Route::currentRouteName();
\Log::info('Ruta actual detectada: ' . $routeName);

if ($routeName === 'orgs.multiBoletaPrint') {
    $docType = 'boleta';
} elseif ($routeName === 'orgs.multiFacturaPrint') {
    $docType = 'factura';
    $iva = $subtotal_con_cargos * 0.19;
    $total_con_iva = $subtotal_con_cargos + $iva;
} else {
    $docType = 'boleta'; // Por defecto
}

        \Log::info('Tipo de documento seleccionado: ' . $docType);
        \Log::info("IVA Calculado: {$iva}");
        \Log::info("Total con IVA: {$total_con_iva}");

        switch (strtolower($docType)) {
            case 'boleta':
                \Log::info('Entrando a la vista de Boleta');
                return view('orgs.multiBoletaPrint', compact('reading', 'org', 'detalle_sections', 'tier', 'configCost', 'subtotal_consumo', 'total_con_iva', 'consumo_agua_potable', 'subsidio_descuento', 'readingAnterior'));

            case 'factura':
                \Log::info('Entrando a la vista de Factura');
                return view('orgs.multiFacturaPrint', compact('reading', 'org', 'detalle_sections', 'tier', 'configCost', 'subtotal_consumo', 'subtotal_con_cargos', 'iva', 'total_con_iva', 'consumo_agua_potable', 'subsidio_descuento', 'readingAnterior'));

            default:
                abort(404, 'Tipo de documento no reconocido: ' . $docType);
        }
    }

    /*Export Excel*/
    public function export()
    {
        return Excel::download(new ReadingsExport, 'Reading-' . date('Ymdhis') . '.xlsx');
    }

    public function exportHistory($id)
    {
        return Excel::download(new ReadingsHistoryExport, 'Readings-History-' . date('Ymdhis') . '.xlsx');
    }

}
