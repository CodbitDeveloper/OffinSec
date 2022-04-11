<?php

namespace App\Http\Controllers\Api;

use App\Guard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Site;

class SiteController extends Controller
{
    //
    public function scannableAreas(Site $site)
    {
        return response()->json([
            "data" => $site->scannable_areas
        ]);
    }

    public function patrols(Site $site)
    {
        $patrols = $site->patrols()->with("images", "scans")->get();
        return response()->json([
            "data" => $patrols
        ]);
    }

    public function guards(Site $site)
    {
        $guards = Guard::whereHas("duty_rosters", function($q) use ($site){
            $q->where("site_id", $site->id);
        })->get();

        return response()->json([
            "data" => $guards
        ]);
    }

    public function searchGuards(Request $request)
    {
        $term = $request->q;
        $guards = Guard::whereRaw("CONCAT(firstname, ' ', lastname) LIKE '%$term%'")->select("id", "firstname", "lastname", "guard_number")->limit(20)->get();
        return response()->json([
            "data" => $guards
        ]);
    }
}
