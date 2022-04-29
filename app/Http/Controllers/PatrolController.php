<?php

namespace App\Http\Controllers;

use App\Client;
use App\Patrol;
use App\Site;
use Illuminate\Http\Request;

class PatrolController extends Controller
{
    //

    public function show(Request $request, Patrol $patrol)
    {
        $patrol->load("scans", "images");
        if($request->ajax()){
            return $patrol->toJson();
        }
        return view("patrol-details", compact("patrol"));
    }

    public function getPatrolForClient(Request $request, Site $site)
    {
        $patrols = Patrol::where("site_id", $site->id)->withCount("scans")->get();

        return $patrols->toJson();
    }
}
