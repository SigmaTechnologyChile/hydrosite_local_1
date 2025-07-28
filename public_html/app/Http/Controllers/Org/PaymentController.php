<?php

namespace App\Http\Controllers\Org;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Org;
use App\Models\Member;
use App\Models\Service;
use App\Models\Reading;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsHistoryExport;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
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
     * @param int $org_id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $org_id)
    {
        $org = $this->org;

        $year = $request->input('year');
        $month = $request->input('month');
        $sector = $request->input('sector');
        $search = $request->input('search');

        if (!$org) {
            return redirect()->route('orgs.index')->with('error', 'Organización no encontrada.');
        }


        // Filtrar los miembros y servicios usando la búsqueda
            $members = Reading::join('members', 'readings.member_id', 'members.id')
             ->join('services', 'services.id', 'readings.service_id')
          //  ->join('services', 'services.member_id', 'readings.member_id')
                ->where('services.org_id', $org->id)
                ->where('readings.payment_status', 0)
                ->when($year, function($q) use ($year) {
                        $q->where('readings.period','like',$year.'%');
                })
                ->when($month, function($q) use ($month) {
                        $q->where('readings.period','like','%'.$month);
                })
                ->when($sector, function($q) use ($sector) {
                        $q->where('services.locality_id',$sector);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        // Especificamos de qué tabla debe venir cada columna
                        $q->where('members.first_name', 'like', "%{$search}%")
                          ->orWhere('members.last_name', 'like', "%{$search}%")
                          ->orWhere('members.rut', 'like', "%{$search}%")
                          ->orWhere('members.address', 'like', "%{$search}%");
                    });
                })
                ->select(
               'members.id',
        'members.rut',
        'members.first_name',
        'members.last_name',
        'members.address',
        'members.phone',
        'members.email',
        DB::raw('COUNT(readings.id) as qrx_serv'), // ✅ Usar DISTINCT

        DB::raw('COUNT(DISTINCT readings.id) as qrx_readings'),
        DB::raw('SUM(readings.total) as total_amount') )     ->groupBy('members.id',
        'members.rut',
        'members.first_name',
        'members.last_name',
        'members.address',
        'members.phone',
        'members.email'
                )
                ->paginate(20)
                  ->withQueryString();

        $locations = Location::where('org_id', $org->id)->get();
        return view('orgs.payments.index', compact('org', 'members', 'locations'));
    }

     // Mostrar el formulario de pago
    public function create($org_id)
    {
        // Obtener la organización, servicios, etc.
        $org = Org::findOrFail($org_id);
        $services = Service::all();
        return view('orgs.payments.create', compact('org', 'services'));
    }

    // Procesar el pago
    /*public function store(Request $request, $org_id)
    {
        $org = Org::findOrFail($org_id);
        $user = Member::where('rut', $request->input('dni'))->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        $payment_method_id = $request->input('payment_method_id');
        if($payment_method_id == 1 OR $payment_method_id == 2 OR $payment_method_id == 3){
            $payment_status = 1;
        }else{
            $payment_status= 0;
        }

        // Crear la nueva orden
        $order = new Order();
        $order->order_code = Str::upper(Str::random(9));
        $order->org_id = $org_id;
        $order->dni = $request->input('dni');
        $order->name = $user->first_name . ' ' . $user->last_name;
        $order->email = $user->email;
        $order->phone = $user->phone;
        $order->status = 1; // Estado de la orden (pendiente)
        $order->payment_method_id = $payment_method_id;
        $order->payment_status = $payment_status;
        $order->save();

        $order_id = $order->id;
        $docs = $request->input('doc');
        $services = $request->input('services');

        //dd($services);
        $qty=0;
        $sum=0;
        foreach($services as $key=>$service){
            $service_id = $service;
            $service = Service::find($service_id);
            $reading = Reading::where('service_id',$service_id)->where('payment_status',0)->orderby('period','DESC')->first();

            $qty++;
            $order_items = new OrderItem;
            $order_items->order_id = $order_id;
            $order_items->org_id = $reading->org_id;
            $order_items->member_id = $reading->member_id;
            $order_items->service_id = $reading->service_id;
            $order_items->reading_id  = $reading->id;
            $order_items->locality_id = $service->locality_id;
            $order_items->folio=$reading->folio;
            $order_items->type_dte = ($service->invoice_type=='factura' ? 'Factura' :'Boleta');
            $order_items->price=$reading->total_mounth;
            $order_items->total=$reading->total;
            $order_items->status=1;
            $order_items->payment_method_id = $payment_method_id;
            $order_items->description="Pago de servicio nro <b>".Str::padLeft($service->nro,5,0)."</b> , Periodo <b>".$reading->period."</b>, lectura <b>".$reading->id."</b>";
            $order_items->payment_status = $payment_status;
            $order_items->save();
            $sumTotal=+$reading->total;

            $reading->payment_status = $payment_status;
            $reading->save();
        }

        $orderU = Order::findOrFail($order_id);
        $orderU->qty = $qty;
        $orderU->total = $sumTotal;
        $orderU->save();

        // Redirigir a la página de detalles de la orden
        return redirect()->route('checkout-order-code', [$order->order_code]);
    }*/

    public function update(Request $request, $id, $reading_id)
    {
        $request->validate([
        'current_reading' => 'required|numeric|min:0'
        ]);

            // Se utiliza el parámetro $id en lugar de $org_id
            $reading = Reading::findOrFail($reading_id);
            $reading->current_reading = $request->current_reading;
            $reading->save();

        return response()->json([
            'success' => true,
            'new_reading' => $reading->current_reading
            ]);
    }

    public function showServices($orgId, $rut)
    {
       // dd($rut);
        // Obtener la organización usando Query Builder
        $org = $this->org;

        if (!$org) {
            abort(404, 'Organization not found');
        }
        $member = Member::where('rut',$rut)->first();
        if (!$member) {
            abort(404, 'Member not found');
        }

        // Buscar una posible orden asociada al miembro y la organización
        $order = Order::where('dni', $member->rut)->first();


        // Si no hay una orden, crear un código de orden aleatorio
        $order_code = $order ? $order->order_code : Str::upper(Str::random(9));

        // Subconsulta para obtener la última lectura por servicio
        $latestReadings = DB::table('readings')->select('service_id', DB::raw('MAX(id) as latest_id'))->groupBy('service_id');


  $members = Member::where('rut',$rut)->first();



$services = Reading::join('services','services.id','readings.service_id')
                    ->where('readings.member_id', $members->id)
                    ->where('payment_status', 0)
                    ->where('total', '>', 0)
                    ->select(
                        'service_id',
                        'services.nro',
                        'services.sector',
                        DB::raw('SUM(total) as total_sum'),
                        DB::raw('MAX(payment_status) as payment_status') // asumiendo 0 = pendiente, 1 = pagado
                    )
                    ->groupBy('service_id', 'services.sector',  'services.nro')
                    ->orderBy('service_id', 'DESC')
                    ->get();

        // Pasar la organización, los servicios y el RUT a la vista
        return view('orgs.payments.services', compact('order_code','org', 'member','services', 'rut'));
    }

    public function history(Request $request, $org_id)
    {
        $org = $this->org;

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $sector = $request->input('sector');
        $search = $request->input('search');

        if (!$org) {
            return redirect()->route('orgs.index')->with('error', 'Organización no encontrada.');
        }

        $order_items = OrderItem::join('orders','order_items.order_id','orders.id')
            ->join('readings', 'order_items.reading_id', 'readings.id')
            ->join('members', 'order_items.member_id', 'members.id')
            ->join('services', 'order_items.service_id', 'services.id')
            ->where('readings.org_id', $org_id)
            ->when($start_date && $end_date, function($q) use ($start_date, $end_date) {
                $q->whereDate('order_items.created_at', '>=', $start_date)
                  ->whereDate('order_items.created_at', '<=', $end_date);
            })
            ->when($sector, function($q) use ($sector) {
                    $q->where('services.locality_id',$sector);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    // Especificamos de qué tabla debe venir cada columna
                    $q->where('members.first_name', 'like', "%{$search}%")
                      ->orWhere('members.last_name', 'like', "%{$search}%")
                      ->orWhere('members.rut', 'like', "%{$search}%")
                      ->orWhere('members.address', 'like', "%{$search}%");
                });
            })
            ->select('order_items.*','orders.order_code')
            ->orderby('order_items.id','DESC')
            ->paginate(20)
                 ->withQueryString();

        foreach ($order_items as $item) {
            $item->member = Member::find($item->member_id);
            $item->service = Service::find($item->service_id);
            $item->reading = Reading::find($item->reading_id);
            $item->location = Location::find($item->locality_id);
            $item->payment_method = PaymentMethod::find($item->payment_method_id);
        }
        $locations = Location::where('org_id', $org->id)->get();

        return view('orgs.payments.history', compact('org', 'order_items','locations'));
    }

    public function export()
    {
        return Excel::download(new PaymentsExport, 'pagos-'.date('Ymdhis').'.xlsx');
    }

    public function exportHistory($id)
    {
        return Excel::download(new PaymentsHistoryExport, 'pagos-History-' . date('Ymdhis') . '.xlsx');
    }
}
