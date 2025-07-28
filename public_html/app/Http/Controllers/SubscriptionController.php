<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Org;
use App\Models\Subscription;
use App\Models\Package;

class SubscriptionController extends Controller
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
        $subscriptions = Subscription::paginate(20);
        foreach($subscriptions as $subscription){
            $subscription->package = Package::find($subscription->package_id);
            $subscription->org = Org::find($subscription->org_id);
        }
        return view('subscriptions.index',compact('subscriptions'));
    }
    
    public function create()
    {
        return view('subscriptions.create');
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
