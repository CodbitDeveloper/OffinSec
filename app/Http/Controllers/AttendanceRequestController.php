<?php

namespace App\Http\Controllers;

use App\AttendanceRequest;
use Illuminate\Http\Request;

class AttendanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'site_id' => 'required',
            'guard_id' => 'required',
            'date_time' => 'required',
            'type' => 'required',
            'status' => 'required'
        ]);

        $requests = new AttendanceRequest();

        $requests->site_id = $request->site_id;
        $requests->guard_id = $request->guard_id;
        $date = date('Y-m-d H:i:s', strtotime($request->date_time));
        $requests->date_time = $date;
        $requests->type = $request->type;
        $requests->status = $request->status;
        //$fileName = Utils::saveBase64Image($request->image, microtime(), 'assets/images/attendances/');
        //$requests->image = $fileName;
        
        if($requests->save()){
            return response()->json([
                'error' => false,
                'Request' => $requests,
                'message' => 'Attendance request saved successfully!'
            ]);
        }

        return response()->json([
            'error' =>  true,
            'message' => 'Could not save the attendance request'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AttendanceRequest  $attendanceRequest
     * @return \Illuminate\Http\Response
     */
    public function show(AttendanceRequest $attendanceRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AttendanceRequest  $attendanceRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(AttendanceRequest $attendanceRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AttendanceRequest  $attendanceRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AttendanceRequest $attendanceRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AttendanceRequest  $attendanceRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttendanceRequest $attendanceRequest)
    {
        //
    }

    /**
     * ------------------------------------
     * Update status of attendance request
     * ------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request)
    {
        $request->validate([
            'request_id' => 'required'
        ]);

        $requests = AttendanceRequest::where('id', $request->request_id)->first();

        $requests->status = 1;

        if($requests->save()){
            $attendance = $request->generateAttendance();

            if($attendance->save()){
                return response()->json([
                    'error' => false,
                    'message' => 'Attendance request updated'
                ]);
            }

            //rollback
            return response()->json([
                'error' => true,
                'message' => 'Could not save attendance'
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'Could not update the attendance request'
        ]);
    }
}
