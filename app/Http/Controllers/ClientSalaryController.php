<?php

namespace App\Http\Controllers;
use App\Guard;

use App\ClientSalary;
use Illuminate\Http\Request;

class ClientSalaryController extends Controller
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
            'client_id' => 'required',
            'guard_id' => 'required',
            'amount' => 'required' 
        ]);
        
        $client = ClientSalary::where('client_id', $request->client_id)->where('guard_id', $request->guard_id)->update(['active' => 0]);

        $client_salary = new ClientSalary();

        $client_salary->client_id = $request->client_id;
        $client_salary->guard_id = $request->guard_id;
        $client_salary->amount = $request->amount;
        $client_salary->active = 1;

        if($client_salary->save()){
            return response()->json([
                'error' => false,
                'message' => "Salary successfully recorded"
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => "Could not update salary records. Try again."
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ClientSalary  $clientSalary
     * @return \Illuminate\Http\Response
     */
    public function show(ClientSalary $clientSalary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ClientSalary  $clientSalary
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientSalary $clientSalary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClientSalary  $clientSalary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientSalary $clientSalary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClientSalary  $clientSalary
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientSalary $clientSalary)
    {
        //
    }

    /**
     * ----------------------------------------------
     * Update client salary records for single guard
     * ----------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSalary(Request $request)
    {
        $request->validate([
            'guard_id' => 'required',
            'client_id' => 'required',
            'amount' => 'required'
        ]);

        $client_salary = ClientSalary::where('client_id', $request->client_id)->where('guard_id', $request->guard_id)->first();
        $client_salary->amount = $request->amount;

        if($client_salary->update()){
            return response()->json([
                'error' => false,
                'Client Salary' => $client_salary,
                'message' => 'Salary successfully recorded'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Could not update salary records. Try again.'
            ]);
        }
    }

    /**
     * ----------------------------------------------------------
     * Update guard salary for a particular client based on role
     * ----------------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salaryByRole(Request $request)
    {
        /*$guard_salary = ClientSalary::whereHas('guard_clientSalary', function($q) use ($request){
            $q->where('occupation', $request->role);
        })->where('client_id', $request->client_id)->update(['amount' => $request->amount]);*/

        ClientSalary::whereHas('guard_clientSalary', function($q) use ($request){
            $q->where('occupation', $request->role);
        })->where('client_id', $request->client_id)->update(['active' => 0]);

        $guards = Guard::where('occupation', $request->role)->whereHas('duty_rosters', function($q) use ($request){
            $q->whereHas('site', function($query) use ($request){
                $query->where('client_id', $request->client_id);
            });
        })->get();
        
        $salaries = array();
        
        foreach($guards as $guard){
            $guard_salary = array("guard_id" => $guard->id, "amount" => $request->amount, "client_id" => $request->client_id, "active" => 1, "created_at" => now());
         
            array_push($salaries, $guard_salary);
        }

        if(ClientSalary::insert($salaries)){
            return response()->json([
                'error' => false,
                'message' =>'Salaries updated'
            ]);
        }

        return response()->json([
            'error' => true, 
            'message' => 'Could not update guard salaries'
        ]);
    }

    /**
     * ---------------------------------------------------
     * Update salaries for guards for a particular client
     * ---------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salaryByClient(Request $request){

        ClientSalary::where('client_id', $request->client_id)->forceDelete();

        $guards = Guard::whereHas('duty_rosters', function($q) use ($request){
            $q->whereHas('site', function($query) use ($request){
                $query->where('client_id', $request->client_id);
            });
        })->get();
        $salaries = array();
        
        foreach($guards as $guard){
            $guard_salary = array("guard_id" => $guard->id, "amount" => $request->amount, "client_id" => $request->client_id, "active" => 1, "created_at" => now());
            
            array_push($salaries, $guard_salary);
        }

        $clients = ClientSalary::insert($salaries);

        return response()->json([
            'error' => false,
            'message' =>'Salaries updated'
        ]);
    }

    /**
     * ----------------------------------------------------------------------
     * Check if salary has been generated for a guard for a particular month
     * ----------------------------------------------------------------------
     */
    public function runUpdate(Request $request){
        $request->validate([
            "client_id" => "required",
            "guard_id" => "required",
            "amount" => "required"
        ]);

        $client_salary = ClientSalary::where([['client_id', $request->client_id], ['guard_id', $request->guard_id], ['active', 1]])->first();

        if($client_salary !== null){
            return $this->updateSalary($request);
        }else{
            return $this->store($request);
        }

    }

    /**
     * -----------------------------------------------------------------------------------------
     * Checks how to apply salary to guards for a particular month whether by role or by client
     * -----------------------------------------------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function applyToMultiple(Request $request){
        $request->validate([
            "client_id" => "required",
            "amount" => "required",
            "is_entire" => "required"
        ]);

        if($request->is_entire === "true" || $request->is_entire === true){
            return $this->salaryByClient($request);
        }else{
            return $this->salaryByRole($request);
        }
    }

    /**
     * -------------------------------------------------------
     * Resets salaries for all guards for a particular client
     * -------------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request){
        $request->validate([
            'client_id' => 'required'
        ]);

        $reset = ClientSalary::where([['client_id', $request->client_id], ['active', 1]])->update(['amount' => 0]);

        return response()->json([
            'error' => false,
            'message' => 'Salaries reset'
        ]);
    }
}
