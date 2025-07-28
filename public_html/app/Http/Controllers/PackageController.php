<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class PackageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $packages = Package::paginate(20);
        return view('packages.index',compact('packages'));
    }
    
    public function create()
    {
        return view('packages.create');
    }

    protected function store(Request $request)
    {
        $request->validate([ 
                'account_name' => ['required']
            ]);
        if(User::where('account_name', $request->input('account_name'))->count()){
            $url = 'https://'.$request->input('account_name').'.rederp.cl';   
            return \Redirect::to($url);
        }else{
            abort(404);
        }
    }
}
