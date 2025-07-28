<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\Org;

class SimpleFacturaController extends Controller
{
    function token($org_id){

        if($token = Token::where('org_id',$org_id)->where('expiresAt','>',date('Y-m-d H:m:s'))->first()){
            return $token->accessToken;
        }else{
            $org = Org::find($org_id);
            if($org->dte_access_email AND $org->dte_access_password){

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
                                          "email": "'.$org->dte_access_email.'",
                                          "password": "'.$org->dte_access_password.'"
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
                $token->org_id = $org_id;
                $token->accessToken = $rest->accessToken;
                $token->expiresIn = $rest->expiresIn;
                $token->expiresAt = Carbon::parse($rest->expiresAt)->format('Y-m-d H:i:s');
                $token->save();
    
                return $token->accessToken;
            }else{
                $token->accessToken = null;
                return $token->accessToken;
            }
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
}
