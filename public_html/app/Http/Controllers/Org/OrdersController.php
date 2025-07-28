<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Service;
use App\Models\Reading;
use App\Models\Member;
use App\Models\Org;
use Illuminate\Support\Str;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Http\Controllers\Org\DB;
use App\Http\Controllers\Org\Log;

class OrdersController extends Controller
{
    protected $_param;
    public $org;

    public function __construct()
    {
        $this->middleware('auth');
        $id = \Route::current()->Parameter('id');
        $this->org = Org::find($id);
    }


    public function store(Request $request, $org_id)
    {
        //tienes que revisar store para corregir show(la vista del voucher, es problema de como se guarda la orden)
        $validated = $request->validate([
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
            'payment_method_id' => 'required|string|in:1,2,3',
        ]);
        if (!$request->has('services') || empty($request->services)) {
            return redirect()->back()->with('error', 'Debes seleccionar al menos un servicio');
        }

        // $member = Member::where('rut', $request->input('dni'))->first();
        $firstService = Service::find($validated['services'][0]);
        if (!$firstService) {
            return redirect()->back()->with('error', 'No se encontró el servicio seleccionado');
        }

        $member = Member::find($firstService->member_id);

        if (!$member) {
            return redirect()->back()->with('error', 'No se encontró el socio asociado al servicio');
        }

        $payment_method_id = $request->input('payment_method_id');
        $payment_status = in_array($payment_method_id, [1, 2, 3]) ? 1 : 0;

        $order = new Order();
        $order->order_code = Str::upper(Str::random(9));
        $order->dni = $member->rut;
        $order->name = $member->first_name . ' ' . $member->last_name;
        $order->email = $member->email;
        $order->phone = $member->phone;
        $order->status = 1;
        $order->payment_method_id = $payment_method_id;
        $order->payment_status = $payment_status;
        $order->save();

        $order_id = $order->id;
        $services = $request->input('services');

        $sumTotal = 0;
        $qty = 0;

        foreach ($services as $serviceId) {


                $service = Service::where('id', $serviceId)
                    ->first();
                    $readings = Reading::where('readings.service_id',$service->id)
                    ->where('readings.member_id', $member->id)
                    ->where('payment_status', 0)
                    ->get();

            foreach ($readings as $reading) {
                $qty++;
                $iva =  $reading->total * 0.19;
                $total_con_iva =  $reading->total + $iva;
                $orderItem = new OrderItem;
                $orderItem->order_id = $order_id;
                $orderItem->org_id = $reading->org_id;
                $orderItem->member_id = $reading->member_id;
                $orderItem->service_id = $reading->service_id;
                $orderItem->reading_id = $reading->id;
                $orderItem->locality_id = $reading->locality_id;
                $orderItem->folio = $reading->folio;
                $orderItem->type_dte = ($reading->invoice_type == 'factura') ? 'Factura' : 'Boleta';
                $orderItem->price = $reading->total;
                $orderItem->total =  ($reading->invoice_type == 'factura')? $total_con_iva :$reading->total;
                $orderItem->status = 1;
                $orderItem->payment_method_id = $payment_method_id;
                $orderItem->description = "Pago de servicio nro <b>" . Str::padLeft($service->nro, 5, 0) . "</b>, Periodo <b>" . $reading->period . "</b>, lectura <b>" . $reading->id . "</b>";
                $orderItem->payment_status = $payment_status;
                $orderItem->save();

                $sumTotal += $reading->total;

                $reading->payment_status = $payment_status;
                $reading->save();
            }
        }

        $order->qty = $qty;
      //  $order->total = $sumTotal;
        $iva =  $sumTotal * 0.19;
                $total_con_iva =  $sumTotal + $iva;

        $order->total =  ($reading->invoice_type == 'factura')? $total_con_iva :$sumTotal;
        $order->save();

        return redirect()->route('orgs.orders.show', ['id' => $org_id, 'order_code' => $order->order_code]);
    }


    public function show($org_id, $order_code)
    {
        //dd($order_code);
        //dd($request->all());
        $org = Org::findOrFail($org_id);
        //$order = Order::where('order_code', $order_code)->firstOrFail();
        // Carga la orden con la relación 'items'
        $order = Order::with('items')->where('order_code', $order_code)->firstOrFail();

        // Obtiene los ítems de la orden
        $items = $order->items;

        return view('orgs.orders.show', compact('org', 'order', 'items'));

    }

    private function getPaymentMethodId($paymentMethod)
    {
        if ($paymentMethod = PaymentMethod::where('title', $paymentMethod)->first()) {
            return $paymentMethod->id;
        } else {
            return 0;
        }
    }

    private function createOrderItems($order, $services)
    {
        $total = 0;
        foreach ($services as $service) {
            dd($service->total_amount, $service->price);
            // Verifica si el servicio tiene un precio válido
            $price = $service->total_amount ?? $service->price ?? 0;

            // Si el precio es 0, puedes optar por lanzar un error o hacer algo específico
            if ($price == 0) {
                return redirect()->back()->with('error', 'El servicio ' . $service->sector . ' no tiene un precio válido.');
            }

            $item = new OrderItem();
            $item->order_id = $order->id;
            $item->org_id = $order->org_id;
            $item->service_id = $service->id;
            $item->doc_id = $service->doc_id ?? 1;
            $item->description = "Pago de servicio: " . $service->sector;
            $item->price = $price;
            $item->qty = 1;
            $item->total = $price;
            $item->status = 1;
            $item->save();

            $total += $price;
        }


        $order->qty = $services->count();
        $order->total = $total;
        $order->save();
    }
}
