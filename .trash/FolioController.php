<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SimpleFacturaController;
use Illuminate\Http\Request;
use App\Models\Org;
use App\Models\Folio;
use App\Models\Document;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HistoryFolioExport;

class FolioController extends Controller
{
    protected $_param;
    public $org;

    public function __construct()
    {
        $this->middleware('auth');
        $id = \Route::current()->Parameter('id');
        $this->org = Org::find($id);
    }

	function index($org_id, Request $request)
	{
	    $org = $this->org;
        $search = $request->input('search');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

	    $folios = Folio::where('org_id',$org_id)
	                        ->when($search, function($q) use ($search) {
                                    $q->where('message', 'like', '%' . $search . '%')
                                    ->orWhere('tipoDte', 'like', '%' . $search . '%');
                            })
                            ->when($start_date, function ($q) use ($start_date) {
                                $q->whereDate('created_at', '>=', $start_date.' 00:00:00');
                            })
                            ->when($end_date, function ($q) use ($end_date) {
                                $q->whereDate('created_at', '<=', $end_date.' 00:00:00');
                            })
                    	    ->OrderBy('id','DESC')
                    	    ->paginate(20)
                                 ->withQueryString();
	    return view('orgs.folios.index',compact('org','folios'));
	}

	function create($org_id)
	{
	    $org = $this->org;
	    return view('orgs.folios.create',compact('org'));
	}

	function store($org_id,Request $request)
	{
	    //$org_id = $request->input('org_id');
	    $qxt = $request->input('qxt');
	    $typedte = $request->input('typeDte');

	    try {

	        $token = (new SimpleFacturaController)->token($org_id);

	        if($token){
                $data='{
                          "credenciales": {
                            "rutEmisor": "76269769-6",
                            "nombreSucursal": "Casa Matriz"
                          },
                          "cantidad": '.$qxt.',
                          "codigoTipoDte": '.$typedte.'
                        }';
                $endpoint="folios/solicitar";
                $method="POST";

                $response = (new SimpleFacturaController)->get_ws($data,$token,$method,"sandbox",$endpoint);

                $desde = $response->data->desde;
                $hasta = $response->data->hasta;

                $folio = new Folio();
                $folio->org_id = $org_id;
                $folio->message = $response->message;
                $folio->codigoSii = $response->data->codigoSii;
                $folio->fechaIngreso = $response->data->fechaIngreso;
                $folio->fechaCaf = $response->data->fechaCaf;
                $folio->desde = $response->data->desde;
                $folio->hasta = $response->data->hasta;
                $folio->fechaVencimiento = $response->data->fechaVencimiento;
                $folio->tipoDte = $response->data->tipoDte;
                $folio->foliosDisponibles = $response->data->foliosDisponibles;
                $folio->ambiente = $response->data->ambiente;
                $folio->errors = $response->errors;;
                $folio->save();

                for($x=$desde;$x<=$hasta;$x++){
                    $dte = new Document();
                    $dte->org_id = $org_id;
                    $dte->folio = $x;
                    $dte->folio = $x;
                    $dte->save();
                }
                //dd($response);
                return redirect()->route('orgs.folios.index',$org_id)->with('success', $response->message);
	        }else{
	            return redirect()->route('orgs.folios.index',$org_id)->with('warning', 'Token no encontrado o validado!, Solicite soporte!');
	        }
        } catch (\Exception $e) {
            //return response()->json(['message'=>'payment not found!'.$e], 404);

            return redirect()->route('orgs.folios.index',$org_id)->with('danger', 'not found!'.$e);
        }
	}
	public function export()
    {
        return Excel::download(new HistoryFolioExport, 'Historial de folios-'.date('Ymdhis').'.xlsx');
    }
}
