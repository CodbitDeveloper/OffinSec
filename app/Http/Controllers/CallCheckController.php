<?php

namespace App\Http\Controllers;

use App\CallCheck;
use App\Site;
use Illuminate\Http\Request;

class CallCheckController extends Controller
{
    //
    public function index()
    {
        $callChecks = CallCheck::with("site", "user");
        $user = request()->user();
        if($user->role == "user"){
            $callChecks = $callChecks->where("user_id", $user->id);
        }else if($user->role == "zone_supervisor"){
            $callChecks = $callChecks->whereHas("site", function($q) use ($user){
                $q->whereHas("zone", function($r) use ($user){
                    $r->where("user_id", $user->id);
                });
            });
        }

        $callChecks = $callChecks->get();
        $sites = Site::all();
        return view('call-checks', compact('callChecks', 'sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "site_id" => "required",
            "user_id" => "required",
            "report" => "required"
        ]);

        $callCheck = CallCheck::create($request->all());

        if($request->ajax()){
            return response()->json([
                "error" => false,
                "data" => $callCheck
            ]);
        }
        
        return redirect(url()->previous())->with("success", "Call check successfully saved");
    }
}
