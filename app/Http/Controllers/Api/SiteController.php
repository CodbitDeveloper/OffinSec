<?php

namespace App\Http\Controllers\Api;

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
}
