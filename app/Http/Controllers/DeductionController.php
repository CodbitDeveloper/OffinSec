<?php

namespace App\Http\Controllers;

use App\Deduction;
use App\Guard;
use Illuminate\Http\Request;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $deductions = Deduction::all();
        return view('deduction-types')->with('deductions', $deductions);
    }
	
	public function listAll()
	{
		$deductions = Deduction::all();
		return response()->json([
			'offences' => $deductions
		]);
	}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $deductions = Deduction::with('guards')->get();
        $guards = Guard::all();
        $offending_guards = array();

        foreach($deductions as $deduction){
            foreach($deduction->guards as $guard){
                $guard->offense = $deduction->name;
                array_push($offending_guards, $guard);
            }
        }

        $offending_guards = collect($offending_guards);
        $offending_guards->sortByDesc('created_at');

        return view('deductions')->with('deductions', $deductions)->with('guards', $guards)->with('offending_guards', $offending_guards);
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
            'description' => 'required',
            'penalty' => 'required'
        ]);

        $deduction = new Deduction();

        $deduction->name = $request->name;
        $deduction->description = $request->description;
        $deduction->penalty = $request->penalty;

        if($deduction->save()){
            return response()->json([
                'error' => false,
                'data' => $deduction,
                'message' => 'Deduction Created Successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error creating deduction'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function show(Deduction $deduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function edit(Deduction $deduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'penalty' => 'required',
            'description' => 'required'
        ]);

        $deduction = Deduction::where('id', $request->id)->first();
        $deduction->name = $request->name;
        $deduction->penalty = $request->penalty;
        $deduction->description = $request->description;
        
        if($deduction->update()){
            return response()->json([
                'error' => false,
                'data' => $deduction,
                'message' => 'Offence updated successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error updating offence'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deduction $deduction)
    {
        $status = $deduction->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Offence Deleted Successfully' : 'Error Deleting Offence'
        ]);
    }

    /**
     * ------------------------------------------
     * Record deductions for a particular guard 
     * ------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deductGuard(Request $request)
    {
        $request->validate([
            'guard_id' => 'required',
            'deduction_id' => 'required',
            'description' => 'required',
            'date' => 'required'
        ]);

        $deduction = Deduction::where('id', $request->deduction_id)->first();
        $date = date('Y-m-d', strtotime($request->date));

        $deduction->guards()->attach($request->guard_id, ['date' => $date, 'details' => $request->description, 'amount' => $deduction->penalty]);

        return response()->json([
            'error' => false,
            'message' => 'Deduction recorded'
        ]);
    }

    /**
     * ------------------------------
     * Display all guards deductions
     * -------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return view
     */
    public function guardDeductions(Request $request){
        return view('guard-deductions');
    }

    /**
     * -----------------------------------------------------
     * Get deductions for all guards for a particular month
     * -----------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function viewMonthly(Request $request){
        $request->validate([
            'date' => 'required'
        ]);

        $date = substr($request->date, 0, strpos($request->date, " 00:"));

        $guards = Guard::with('deductions')->whereHas('deductions', function($q) use ($date){
            $q->whereMonth('date', '=', date('m', strtotime($date)));
        })->get();

        foreach($guards as $guard){
            $sum = 0;
            $guard->offence_count = $guard->deductions->count();

            foreach($guard->deductions as $deduction){
                $sum += $deduction->pivot->amount;
            }

            $guard->offence_total = $sum;
        }

        return response()->json([
            'error' => false,
            'guards' => $guards
        ]);
    }
}
