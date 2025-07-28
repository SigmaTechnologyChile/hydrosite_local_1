<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use App\Models\Token;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\Member;
use App\Models\Service;
use App\Models\Reading;

use App\Mail\OrderMail;

class DteController extends Controller
{
    function token(){
        
        if($token = Token::where('expiresAt','>',now())->firstOrFail()){
            return $token->accessToken;
        }else{
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.simplefactura.cl/token',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => '{
                                      "email": "demo@chilesystems.com",
                                      "password": "Rv8Il4eV"
                                    }',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              )
            ));
            
            $response = curl_exec($curl);
            
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);
            
            if (isset($error_msg)) {
                //dd($error_msg);
            }
            $rest = json_decode($response);
            
            $token = new Token();
            $token->accessToken = $rest->accessToken;
            $token->expiresIn = $rest->expiresIn;
            $token->expiresAt = Carbon::parse($rest->expiresAt)->format('Y-m-d H:i:s');
            $token->save();

            return $token->accessToken;
        }
    }
    
    function get_ws($data,$apikey,$method,$type,$endpoint){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.simplefactura.cl/'.$endpoint,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $method,
          CURLOPT_POSTFIELDS => $data,
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$apikey,
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        
        if (isset($error_msg)) {
            dd($error_msg);
        }

        return json_decode($response);
    }	
	
	function folio_create()
	{
	    try {
            $data='{
                      "credenciales": {
                        "rutEmisor": "76269769-6",
                        "nombreSucursal": "Casa Matriz"
                      },
                      "cantidad": 1,
                      "codigoTipoDte": 41
                    }';
            $endpoint="folios/solicitar";
            $method="POST";
            $response = $this->get_ws($data,$this->token(),$method,"sandbox",$endpoint);
            dd($response);
        } catch (\Exception $e) {
            return response()->json(['message'=>'payment not found!'.$e], 404);
        }
	}
	
	function dte_boleta_create($reading_id)
	{
	    try {
    	    $reading = Reading::find($reading_id);
    
            if($reading->total > 0 AND $reading->folio > 0){
                
                $user_id = $reading->id;
                $sub_total = $reading->sub_total;
                $commission = $reading->commission;
                $order_total = $reading->total;
                $data='{
                    "Documento": {
                            "Encabezado": {
                                "IdDoc": {
                                    "TipoDTE": 41,
                                    "FchEmis": "2023-04-21",
                                    "FchVenc": "2023-05-21",
                                    "IndServicio":3
                                },
                                "Emisor": {
                                    "RUTEmisor": "76269769-6",
                                    "RznSocEmisor": "Chilesystems",
                                    "GiroEmisor": "Desarrollo de software",
                                    "DirOrigen": "Calle 7 numero 3",
                                    "CmnaOrigen": "Santiago"
                                },
                                "Receptor": {
                                    "RUTRecep":"17246644-3",
                                    "RznSocRecep": "Proveedor Test",
                                    "DirRecep": "calle 12",
                                    "CmnaRecep": "Paine",
                                    "CiudadRecep": "Santiago"
                                },
                                "Totales": {
                                    "MntExe": "990",
                                    "MntTotal": "990"
                                }
                            },
                            "Detalle": [{
                                "NroLinDet": "1",
                                "NmbItem": "Alfajor",
                                "QtyItem": "1",
                                "UnmdItem": "un",
                                "PrcItem": "990",
                                "MontoItem": "990",
                                "IndExe":1
                            }]
                        }
                }';
                $endpoint="/invoiceV2/Casa_Matriz";
                $method="POST";
                $response = $this->get_ws($data,$method,"sandbox",$endpoint);
                dd($response);
            }else{
                return redirect()->route('orders-summary',$order->order_code);
            }
        } catch (\Exception $e) {
            return response()->json(['message'=>'payment not found!'.$e], 404);
        }
	}
	
	function dte_factura_create($reading_id)
	{
	    try {
    	    $order = Order::where('order_code',$order_code)->first();
    
            if($order->total > 0 AND $order->payment_status==0){
                
                $user_id = $order->id;
                $sub_total = $order->sub_total;
                $commission = $order->commission;
                $order_total = $order->total;
                
                $OrderItem = OrderItem::where('order_id',$order->id)->get();

                $data='{
                    "Documento": {
                        "Encabezado": {
                            "IdDoc": {
                                "TipoDTE": 33,
                                "FchEmis": "2023-10-19",
                                "FmaPago": 1,
                                "FchVenc": "2023-10-19"
                            },
                            "Emisor": {
                                "RUTEmisor": "76269769-6",
                                "RznSoc": "Chilesystems",
                                "GiroEmis": "Desarrollo de software",
                                "Telefono": [
                                    "912345678"
                                ],
                                "CorreoEmisor": "mvega@chilesystems.com",
                                "Acteco": [
                                    620200
                                ],
                                "DirOrigen": "Calle 7 numero 3",
                                "CmnaOrigen": "Santiago",
                                "CiudadOrigen": "Santiago"
                            },
                            "Receptor": {
                                "RUTRecep": "10422710-4",
                                "RznSocRecep": "McL",
                                "GiroRecep": "test",
                                "CorreoRecep": "amamani@chilesystems.com",
                                "DirRecep": "calle 12",
                                "CmnaRecep": "Paine",
                                "CiudadRecep": "Santiago"
                            },
                            "Transporte": {
                                "DirDest": "Los Pensamientos 2307",
                                "CmnaDest": "Providencia",
                                "CiudadDest": "Santiago"
                            },
                            "Totales": {
                                "MntNeto": "84",
                                "TasaIVA": "19",
                                "IVA": "16",
                                "MntTotal": "100"
                            }
                        },
                        "Detalle": [
                            {
                                "NroLinDet": "1",
                                "CdgItem": [
                                    {
                                        "TpoCodigo": "ALFA",
                                        "VlrCodigo": "123"
                                    }
                                ],
                                "NmbItem": "Producto Test",
                                "QtyItem": "1",
                                "UnmdItem": "un",
                                "PrcItem": "100",
                                "MontoItem": "100"
                            }
                        ],
                        "Referencia": [
                            {
                                "NroLinRef": 1,
                                "TpoDocRef": "802",
                                "FolioRef": "1121121",
                                "FchRef": "2023-09-07",
                                "RazonRef": "Razon de referencia"
                            }
                        ],
                        "DscRcgGlobal": [
                            {
                                "NroLinDR": "1",
                                "GlosaDR": "Descuento Global Test",
                                "TpoMov": 1,
                                "TpoValor": 2,
                                "ValorDR": "16"
                            }
                        ]
                    },
                    "Observaciones": "NOTA AL PIE DE PAGINA",
                    "TipoPago": "30 dias"
                }';
                    $endpoint="dte/generar";
                    $method="POST";
                    $response = $this->get_ws($data,$method,"sandbox",$endpoint);
                    dd($response);
            }else{
                return redirect()->route('orders-summary',$order->order_code);
            }
        } catch (\Exception $e) {
            return response()->json(['message'=>'payment not found!'.$e], 404);
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
