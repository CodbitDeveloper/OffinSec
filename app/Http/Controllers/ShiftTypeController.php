<?php

namespace App\Http\Controllers;

use App\Shift_Type;
use Illuminate\Http\Request;

class ShiftTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shift_type = Shift_Type::all();

        return response()->json($shift_type, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('shift_type');
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
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        $shift_type = new Shift_Type();

        $shift_type->name = $request->name;
        $shift_type->start_time = $request->start_time;
        $shift_type->end_time = $request->end_time;

        if(Shift_Type::where('name', $request->name)->get()->count() > 0) {
            return response()->json([
                'error' => true,
                'message' => 'Shift type name already exists'
            ]);
        }

        if(Shift_Type::where([['start_time', $request->start_time], ['end_time', $request->end_time]])->get()->count() > 0) {
            return response()->json([
                'error' => true,
                'message' => 'Start or end time already allocated'
            ]);
        }

        if($shift_type->save()){
            return response()->json([
                'error' => false,
                'data' => $shift_type,
                'message' => 'Shift Type Created Successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error creating shift type'
            ]);
        }
    }

    /**
     * Display the speci fied resource.
     *
     * @param  \App\Shift_Type  $shift_Type
     * @return \Illuminate\Http\Response
     */
    public function show(Shift_Type $shift_Type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Shift_Type  $shift_Type
     * @return \Illuminate\Http\Response
     */
    public function edit(Shift_Type $shift_Type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shift_Type  $shift_Type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shift_Type $shift_Type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shift_Type  $shift_Type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shift_Type $shift_Type)
    {
        //
    }
}
