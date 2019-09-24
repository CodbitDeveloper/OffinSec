<?php

namespace App\Http\Controllers;
use App\Client;

use App\Salary;
use App\Guard;

use Illuminate\Http\Request;

class SalaryController extends Controller
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
            'guard_id' => 'required',
            'amount' => 'required',
            'bank_name' => 'required',
            'bank_branch' => 'required',
            'total_deductions' => 'required', 
            'month' => 'required'
        ]);

        $salary = new Salary();

        $salary->guard_id = $request->guard_id;
        $salary->amount = $request->amount;
        $salary->bank_name = $request->bank_name;
        $salary->bank_branch = $request->bank_branch;
        $salary->total_deductions = $request->total_deductions;
        $salary->month = date('Y-m-d', strtotime($request->month));

        if($salary->save()){
            return response()->json([
                'error' => false,
                'message' => 'Salary Added Successfully!'
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'Could not add salary'
        ]);
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show(Salary $salary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function edit(Salary $salary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salary $salary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salary $salary)
    {
        //
    }

    /**
     * -----------------------------
     * Display all clients
     * -----------------------------
     * 
     * @return view
     */
    public function all(){
        $clients = Client::orderBy('name', 'ASC')->get();
        return view('salaries', compact('clients'));
    }

    /**
     * ----------------------------------------------------
     * Get salaries for all guards for a particular month
     * ----------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request){
        $request->validate([
            'date' => 'required'
        ]);
        
        $month = date('m', strtotime($request->date));
        $year = date('Y', strtotime($request->date));
        $salaries = Salary::with('guard_salary')->whereYear('month', $year)->whereMonth('month', $month)->get();
        return response()->json([
            'salaries' => $salaries,
            'date' => $request->date
        ]);
    }

    /**
     * -------------------------------------------------------
     * Generate salaries for a particular month for all guards
     * --------------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request){ 
        
        $request->validate([
            'date' => 'required'
        ]);
        
        $month = date('m', strtotime($request->date));
        $year = date('Y', strtotime($request->date));

        $guards = Guard::whereHas('client_salary', function($q) use ($month, $year){
            $q->whereYear('created_at', '<=', $year)->whereMonth('created_at', '<=', $month)->where('active', 1);
        })->with(['client_salary' => function($query){
            $query->orderBy('created_at', 'DESC');
        }])->with(['deductions' => function($stmt) use ($month, $year){
            $stmt->whereYear('date', $year)->whereMonth('date', $month);
        }])->get();

        $salaries = array();

        foreach($guards as $guard){
            
            $total_deduction = 0;

            foreach($guard->deductions as $deduction){
                $total_deduction+= $deduction->pivot->amount;
            }

            $salary = array(
                "guard_id" => $guard->id,
                "amount" => $guard->client_salary[0]->amount,
                "bank_name" => $guard->bank_name,
                "bank_branch" => $guard->bank_branch,
                "account_number" => $guard->account_number,
                "total_deductions" => $total_deduction,
                "month" => date('Y-m-d', strtotime($request->date)),
                "created_at" => date('Y-m-d'),
                "status" => 0
            );

            array_push($salaries, $salary);
        }

        if(Salary::insert($salaries)){
            return response()->json([
                'error' => false,
                'message' => 'Salaries generated'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Could not generate salaries list'
            ]);
        }
    }
}
