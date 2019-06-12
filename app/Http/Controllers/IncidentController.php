<?php

namespace App\Http\Controllers;

use App\Incident;
use App\Client;

use Illuminate\Http\Request;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $incidents = Incident::all();
        $clients = Client::with('sites')->get();
        return view('incidents', compact('incidents', 'clients'));
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
            'incident' => 'required',
            'action_taken' => 'required',
            'site_id' => 'required'
        ]);

        $incident = new Incident();

        $incident->incident = $request->incident;
        $incident->action_taken = $request->action_taken;
        $incident->site_id = $request->site_id;

        if($incident->save()){
            return response()->json([
                'data' => $incident,
                'error' => false,
                'message' => 'Incident Saved Successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error Saving Incident. Try Again!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function show(Incident $incident)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function edit(Incident $incident)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Incident $incident)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function destroy(Incident $incident)
    {
        //
    }
}
