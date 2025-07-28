<?php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reading;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private $comissionRate = 1600;
    private $currencyValue = 990;

    public function store(Request $request)
    {
        $readings = $request->input('readings');

        if (empty($readings)) {
            return back()->with('error', 'Debe seleccionar al menos un documento para pagar.');
        }


        $member = Member::where('rut', $request->input('dni'))->first();


        $Order = new Order;
        $Order->order_code = Str::upper(Str::random(9));
        $Order->dni = $member->rut;
        $Order->name = $member->full_name;
        $Order->email = $member->email;
        $Order->phone = $member->phone;
        $Order->status = 1;
        $Order->save();

        $qty = 0;
        $total = 0;

        foreach ($readings as $readingValue) {
            [$service_id, $reading_id] = explode('_', $readingValue);

            $service = Service::find($service_id);
            $reading = Reading::find($reading_id);

            if (!$service || !$reading) {
                continue;
            }
            $iva =  $reading->total * 0.19;
            $total_con_iva =  $reading->total + $iva;
            $item = new OrderItem;
            $item->order_id = $Order->id;
            $item->org_id = $reading->org_id;
            $item->member_id = $reading->member_id;
            $item->service_id = $reading->service_id;
            $item->reading_id = $reading->id;
            $item->locality_id = $service->locality_id;
            $item->folio = $reading->folio;
            $item->type_dte = ($service->invoice_type == 'factura') ? 'Factura' : 'Boleta';
            $item->price = $reading->total_mounth;
            $item->total =  ($reading->invoice_type == 'factura')? $total_con_iva :$reading->total;
            $item->status = 1;
            $item->payment_method_id = 4;
            $item->description = "Pago de servicio nro <b>" . Str::padLeft($service->nro, 5, 0) . "</b>, Periodo <b>" . $reading->period . "</b>, lectura <b>" . $reading->id . "</b>";
            $item->save();

            $qty++;
            $total += $reading->total;
        }

        // Actualizamos cantidad y total
        $Order->qty = $qty;
                $iva =  $total * 0.19;
                $total_con_iva =  $total + $iva;

        $Order->total =  ($reading->invoice_type == 'factura')? $total_con_iva :$total;
       // $Order->total = $total;
        $Order->save();


        return redirect()->route('checkout-order-code', [$Order->order_code]);
    }


    public function show($order_code)
    {
        $order = Order::where('order_code', $order_code)->first();
        //$order = Order::findOrFail($id);
        $items = OrderItem::where('order_id', $order->id)->get();

        //dd($items);
        return view('checkout', ['order' => $order, 'items' => $items]);
    }

    public function update(Request $request, $order_code)
    {
        //dd($request->input());

        $ticket_ids = $request->input('ticket_id');
        //$ticket_dnis         = $request->input('ticket_dni');
        $ticket_first_names = $request->input('ticket_firstName');
        $ticket_last_names = $request->input('ticket_lastName');
        $ticket_emails = $request->input('ticket_email');
        //$ticket_phones      = $request->input('ticket_phone');

        for ($i = 0; $i < count($ticket_ids); $i++) {
            $ticket_id = @$ticket_ids[$i];
            $order_ticket = OrderTicket::findOrFail($ticket_id);
            //$order_ticket->dni         = $ticket_dnis[$i];
            $order_ticket->first_name = $ticket_first_names[$i];
            $order_ticket->last_name = $ticket_last_names[$i];
            $order_ticket->email = $ticket_emails[$i];
            //$order_ticket->phone       = $ticket_phones[$i];
            $order_ticket->save();
        }

        //$orderU = Order::findOrFail($order_id);
        $orderU = Order::where('order_code', $order_code)->first();
        $orderU->validate_tickets = 1;
        $orderU->status = 2;
        $orderU->save();

        return redirect()->route('checkout-order-code', [$order_code]);
    }

    public function recovery_update($order_code)
    {
        $orderU = Order::where('order_code', $order_code)->first();
        $orderU->validate_tickets = 0;
        $orderU->save();

        return redirect()->route('checkout-order-code', [$order_code]);
    }
}
