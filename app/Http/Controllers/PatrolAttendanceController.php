<?php

namespace App\Http\Controllers;

use App\PatrolAttendance;
use Illuminate\Http\Request;

class PatrolAttendanceController extends Controller
{
    //
    public function show(PatrolAttendance $patrolAttendance)
    {
        $patrolAttendance->load("lines", "lines.security_guard", "lines.reliever");

        return view('patrol-attendance-details', compact('patrolAttendance'));
    }
}
