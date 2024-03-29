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

    public function getPatrolsForClient(Request $request, Site $site)
    {
        $date = date('Y-m-d', strtotime($request->date));
        $patrols = Patrol::where("site_id", $site->id)->whereDate("created_at", $date)->withCount("scans")->get();

        return $patrols->toJson();
    }
}
