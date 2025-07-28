<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Mail\OrderMail;
use Illuminate\Support\Str;
use App\Models\Reading;

class PaymentController extends Controller
{
	/*WEBPAY DIRECTO*/

    function get_ws($data,$method,$type,$endpoint){
        $curl = curl_init();
        if($type=='live'){
    		$TbkApiKeyId='597048495722';
    		$TbkApiKeySecret='0f71d1c8-2a99-4dcc-af45-5e5ab608e120';
            $url="https://webpay3g.transbank.cl".$endpoint;//Live
        }else{
    		$TbkApiKeyId='597055555532';
    		$TbkApiKeySecret='579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';
            $url="https://webpay3gint.transbank.cl".$endpoint;//Testing
        }
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $method,
          CURLOPT_POST => true,
          CURLOPT_POSTFIELDS => $data,
          CURLOPT_HTTPHEADER => array(
            'Tbk-Api-Key-Id: '.$TbkApiKeyId.'',
            'Tbk-Api-Key-Secret: '.$TbkApiKeySecret.'',
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);
        \Log::info('Return response interna webpay', ["webpayresponse", $response]);
        curl_close($curl);
        //echo $response;
        return json_decode($response);
    }

 function create_order_payment($order_code)
    {
        try {
            $order = Order::where('order_code', $order_code)->first();

            // Validar que la orden exista y tenga un monto válido y esté pendiente
            if (!$order) {
                \Log::warning('Intento de crear pago para orden inexistente', ['order_code' => $order_code]);
                return redirect()->route('error-page')->with('message', 'Orden de pago no encontrada.');
            }

            if ($order->total <= 0 || $order->payment_status != 0) {
                \Log::warning('Intento de crear pago para orden con total cero o ya pagada', [
                    'order_code' => $order_code,
                    'total' => $order->total,
                    'payment_status' => $order->payment_status
                ]);
                return redirect()->route('orders-summary', $order->order_code)->with('message', 'Esta orden no puede ser procesada para pago.');
            }

            // Ya no necesitas estas variables si las obtienes directamente del objeto $order
            // $user_id = $order->id; // Este es el ID de la ORDEN, no un user_id si no hay campo específico
            // $sub_total = $order->sub_total;
            // $commission = $order->commission;
            $order_total = $order->total;

            // OrderItem ya no se usa aquí para el JSON, se espera que los items estén asociados a la orden
            // $OrderItem = OrderItem::where('order_id',$order->id)->get();
            // $item = json_encode($OrderItem); // Esto no es parte del payload de Transbank por defecto

            $return_url = \URL::to('/payment-response/'.$order->order_code);

            // Utiliza el order_code existente para buy_order para consistencia
            $buy_order = $order->order_code;
            $session_id = Str::random(10); // Generar un session_id más robusto o único

            \Log::info('Preparando Transbank request', ["buy_order" => $buy_order, "amount" => $order_total, "return_url" => $return_url]);

            $data='{
                "buy_order": "'.$buy_order.'",
                "session_id": "'.$session_id.'",
                "amount": '.$order_total.',
                "return_url": "'.$return_url.'"
            }';
            $method='POST';
            $endpoint='/rswebpaytransaction/api/webpay/v1.0/transactions';

            $response = $this->get_ws($data, $method, "sandbox", $endpoint);
            \Log::info('Return response webpay success', ["response", $response]);

            if(isset($response->token) && isset($response->url))
            {
                \Log::info('Transbank response OK', ["token" => $response->token, "url" => $response->url]);
                $order_payment = new OrderPayment;
                $order_payment->order_id = $order->id;
                $order_payment->response = json_encode($response);
                $order_payment->method = 'WebPays plus';
                $order_payment->status = 0; // 1 significa que se inició el pago con Transbank
                $order_payment->save();

                $token = $response->token;
                $url = $response->url;
                return view('webpay', compact('url', 'token'));
            }
            else
            {
                \Log::error('Transbank response ERROR', ["response" => $response, "order_code" => $order_code]);
                $order_payment = new OrderPayment;
                $order_payment->order_id = $order->id;
                $order_payment->response = json_encode($response);
                $order_payment->method = 'WebPays plus';
                $order_payment->status = 0; // 0 significa que falló el inicio del pago
                $order_payment->save();

                // Redirigir con un mensaje de error
                return redirect()->route('orders-summary', $order->order_code)->with('error', 'Error al iniciar el pago con Transbank. Intente nuevamente.');
            }
        } catch (\Exception $e) {
            \Log::error('Excepción en create_order_payment', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'order_code_attempt' => $order_code ?? 'N/A']);
            return response()->json(['message' => 'Ocurrió un error inesperado al procesar el pago: ' . $e->getMessage()], 500);
        }
    }

	function resume_order_payment($order_code)
	{
	    $order = Order::where('order_code',$order_code)->first();
	    try {
    	    $order_payment_status=0;
            //dd($_POST);

            /** Token de la transacción */
            $token = filter_input(INPUT_POST, 'token_ws');

            $request = array(
                "token" => filter_input(INPUT_POST, 'token_ws')
            );

            $data='';
    		$method='PUT';
    		$endpoint='/rswebpaytransaction/api/webpay/v1.0/transactions/'.$token;

            $response = $this->get_ws($data,$method,"sandbox",$endpoint);
            //dd($response);


            if (isset($response->response_code)) {
                $is_payment_successful = ($response->response_code == 0);

                $order->status = $is_payment_successful ? 1 : 0;
                $order->payment_status = $is_payment_successful ? 1 : 0;
                $order->payment_detail = $response->status;
                $order->payment_method_id = 4;
                $order->save(); // Persistir los cambios en la tabla 'orders'

                \Log::info('resume_order_payment: Order principal actualizada', ['order_code' => $order_code, 'payment_status' => $order->payment_status]);

                $order_payment_status = $is_payment_successful ? 1 : 0;


                if ($order->payment_status == 1) {

                    OrderItem::where('order_id', $order->id)
                             ->where('payment_status', 0)
                             ->update(['payment_status' => 1]); // Marcar como pagado

                    \Log::info('resume_order_payment: OrderItems asociados marcados como pagados', ['order_code' => $order_code]);

                    // Obtener IDs de Readings desde los OrderItems para actualizar la tabla 'readings'
                    // Esto es lo que hacía falta para que tu otra query funcione.
                    $readingIdsToUpdate = OrderItem::where('order_id', $order->id)
                                                   ->pluck('reading_id')
                                                   ->filter()
                                                   ->all();

                    if (!empty($readingIdsToUpdate)) {
                        Reading::whereIn('id', $readingIdsToUpdate)
                               ->where('payment_status', 0)
                               ->update(['payment_status' => 1]); // Marcar como pagado

                        \Log::info('resume_order_payment: Readings asociadas (vía OrderItems) marcadas como pagadas', ['order_code' => $order_code, 'reading_ids' => $readingIdsToUpdate]);
                    } else {
                        \Log::warning('resume_order_payment: No se encontraron reading_id válidos en los OrderItems para actualizar Readings', ['order_code' => $order_code]);
                    }

                } else {
                    \Log::warning('resume_order_payment: El pago de la orden principal NO fue exitoso según Transbank', ['order_code' => $order_code, 'response_code' => $response->response_code]);
                }
            } else {
                \Log::error('resume_order_payment: Transbank no devolvió "response_code" en la confirmación', ['order_code' => $order_code, 'response' => $response]);
            }

            $order_payment = new OrderPayment;
            $order_payment->order_id = $order->id;
            $order_payment->response = json_encode($response);
            $order_payment->method='WebPays plus';
            $order_payment->status=$order_payment_status;
            $order_payment->save();

    	    return redirect()->route('orders-summary',$order->order_code);
        } catch (\Exception $e) {
            dd($e);
            //return response()->json(['message'=>'payment summary not found!'], 404);
            //return redirect()->route('orders-summary',$order->order_code);
        }
	}

	/*Mailings*/
    public function sendOrderConfirmationMail($order)
    {
        try{
            Mail::to($order->email)->send(new OrderMail($order));
        } catch (\Exception $e) {
            return 0;
        }
    }
}
