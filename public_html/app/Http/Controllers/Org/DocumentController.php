<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Org;
use App\Models\Document;

class DocumentController extends Controller
{
    protected $_param;
    public $org;
    
    public function __construct()
    {
        $this->middleware('auth');
        // El parámetro 'id' debe obtenerse dentro de los métodos, no en el constructor
        $this->org = null;
    }
    
    public function index()
    {
        $org = $this->org;
        $documents = Document::where('org_id',$org->id)->paginate(20);
        return view('orgs.documents.index',compact('org','documents'));
    }
    
    public function create()
    {
        return view('orgs.documents.create');
    }
}
