<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use App\Models\Folio;
use App\Models\Org;
use App\Models\Reading;
use App\Models\Service;

class KronController extends Controller
{
    public function readings_charge_store()
    {
        try {
            $period = date('Y-m');
            //$readings =  Reading::join('services','services.id','readings.service_id')->where('readings.folio',0)->select('readings.id','services.invoice_type')->get();
            $services =  Service::where('org_id',864)->where('active',1)->get();
            
           foreach($services as $service){
                $r = Reading::where('service_id',$service->id)->where('org_id', $service->org_id)->select('current_reading')->OrderBy('period','DESC')->first();
               
                if($r){
                    $current_reading = $r->current_reading;  
                    $period_reading = $r->period;
                }else{
                    $current_reading = 0;   
                    $period_reading = null;
                }
                if($period <> $period_reading){
                    $reading_up = new Reading();
                    $reading_up->org_id = $service->org_id;
                    $reading_up->member_id = $service->member_id;
                    $reading_up->service_id = $service->id;
                    $reading_up->previous_reading = $current_reading;
                    $reading_up->period = $period;
                    $reading_up->locality_id = $service->locality_id;
                    $reading_up->save(); 
                    
                }
              //dd($reading_up);
               /*  
                $reading_up = Reading::find($reading->id);
                $folio_one = $this->folio_new($reading->invoice_type);
                if($folio_one > 0){
                    $reading_up->folio = $folio_one;
                    $reading_up->save();                    
                }*/
            }
            
            return response()->json($services);
        } catch (\Exception $e) {
            return response()->json(['message'=>'Reading not found!'.$e], 404);
        }
    }
    
    private function folio_new($dte_type)
    {
        try {
            if($dte_type=='factura'){
                $folio =  Folio::where('codigoSii',33)->whereColumn('foliosDisponibles','>','foliosUsados')->orderby('id','asc')->first();
                if($folio){
                    $usados = $folio->foliosUsados;
                    $folio->foliosUsados = $usados+1;
                    $folio->save();  
                    //dd($folio);
                    return ($folio->desde + $usados);
                    
                }else{
                    return 0;    
                }
            }else{
                $folio =  Folio::where('codigoSii',41)->whereColumn('foliosDisponibles','>','foliosUsados')->orderby('id','asc')->first();
                if($folio){
                    $usados = $folio->foliosUsados;
                    $folio->foliosUsados = $usados+1;
                    $folio->save();  
                    //dd($folio);
                    return ($folio->desde + $usados);
                    
                }else{
                    return 0;    
                }
            }
        } catch (\Exception $e) {
            //return response()->json(['message'=>'payment not found!'.$e], 404);
            return 0;
        }
    }
}
