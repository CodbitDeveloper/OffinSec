<?php

namespace App\Http\Controllers;

use App\Patrol;
use Illuminate\Http\Request;

class PatrolController extends Controller
{
    //

    public function show(Patrol $patrol)
    {
        $patrol->load("scans", "images");
        return view("patrol-details", compact("patrol"));
    }
}
