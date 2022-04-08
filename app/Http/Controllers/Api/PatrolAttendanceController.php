<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePatrolAttendanceRequest;
use App\PatrolAttendance;

class PatrolAttendanceController extends Controller
{
    //
    public function store(StorePatrolAttendanceRequest $request)
    {
        
        $patrolAttendance = PatrolAttendance::create([
            "site_id" => $request->site_id,
            "user_id" => $request->user_id
        ]);

        $patrolAttendance->lines()->createMany($request->attendance);

        return response()->json([
            "data" => $patrolAttendance
        ]);
    }
}
