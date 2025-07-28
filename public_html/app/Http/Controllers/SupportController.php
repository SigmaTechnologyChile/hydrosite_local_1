<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Support;
class SupportController extends Controller
{


    public function create()
    {
        return view('createSupport');
    }

    public function store(Request $request)
    {
        $user_id = Auth::id();
        $type = $request->input('type');
        $description = $request->input('description');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $code = uniqid();

        $support = new Support();
        $support->type=$type;
        $support->description = $description;
        $support->first_name = $first_name;
        $support->last_name = $last_name;
        $support->email = $email;
        $support->phone = $phone;
        $support->code = $code;
        $support->created_by = $user_id;
        $support->updated_by = 0;
        $support->save();

        return view('supportSuccess');
    }

}
