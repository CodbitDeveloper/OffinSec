<?php

namespace App\Http\Controllers;

use App\Duty_Roster;
use App\Guard;
use App\Site;
use App\Shift_Type;
use App\ClientSalary;
use App\Client;
use App\Salary;

use DB;

use Illuminate\Http\Request;

class DutyRosterController extends Controller
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
        return view('add-duty_roster');
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
            'name' => 'required'
        ]);

        $duty_roster = new Duty_Roster();

        $duty_roster->site_id = $request->site_id;
        $duty_roster->name = $request->name;

        if($duty_roster->save()){
            //convert array from request to php array

            //create array from days
            /*$guard_roster_days = array();

            foreach($request->guard_roster as $roster){
                foreach($roster->days as $day){
                    //create a new array from the guard roster array
                    $temp = array('guard_id' => $roster->guard_id, 
                    'shift_type_id' => $roster->shift_type_id, 'duty_roster_id' => $roster->duty_roster_id,
                    'day' => $day);

                    array_push($guard_roster_days, $temp);
                }
            }

            //attach
            $duty_roster->guards()->attach($guard_roster_days);*/

            return \response()->json([
                'error' => false,
                'data' => $duty_roster,
                'message' => 'Duty Roster created successfully'
            ]);
        }else{
            return \response()->json([
                'error' => true,
                'message' => 'Error creating duty roster'
            ]); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Duty_Roster  $duty_Roster
     * @return \Illuminate\Http\Response
     */
    public function show(Duty_Roster $duty_Roster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Duty_Roster  $duty_Roster
     * @return \Illuminate\Http\Response
     */
    public function edit(Duty_Roster $duty_roster)
    {
        $duty_roster = new Duty_Roster();

        return view('edit-duty_roster')->with('duty_Roster', $duty_roster);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Duty_Roster  $duty_Roster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Duty_Roster $duty_roster)
    {
        $request->validate([
            'site_id' => 'required',
            'name' => 'required'
        ]);

        $duty_roster->site_id = $request->site_id;
        $duty_roster->name = $request->name;

        if($duty_roster->update()){
            return response()->json([
                'error' => false,
                'data' => $duty_roster,
                'message' => 'Duty Roster Updated Successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error updating duty roster'
            ]);
        }


   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Duty_Roster  $duty_Roster
     * @return \Illuminate\Http\Response
     */
    public function destroy(Duty_Roster $duty_Roster)
    {
        //
    }

    /**
     * --------------------------------------------------------
     * Display duty roster for all guards for a particular site
     * --------------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return view
     */
    public function view(Request $request){
        $site = Site::where('id', $request->id)->first();
        $guards = Guard::whereDoesntHave('duty_rosters')
                         ->orWhereHas('duty_rosters', function($d){
                             $d->whereHas('site', function($s){
                                 $s->whereHas('client', function($c){
                                     $c->where('end_date', '<', date('Y-m-d'));
                                 });
                             });
                         })->get();
        $roster = Duty_Roster::where('site_id', $request->id)->where('active', 1)->with('guards')->first();
        $shift_types = Shift_Type::all();

        if($roster != null){
            foreach($roster->guards as $guard){
                $guard->day = $guard->pivot->day;
            }

            $temp = $roster->guards->groupBy('day');

            $roster->sorted = $temp;
            $site->roster = $roster;
        }       

        return view('roster')->with('guards', $guards)->with('site', $site)->with('shift_types', $shift_types);

        /*return response()->json([
            'site' => $site->roster->sorted['Monday']
        ]);*/
    }

    /**
     * ----------------------------
     * Add guard to a duty roster
     * ----------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_guard(Request $request){
        $request->validate([
            'roster_id' => 'required',
            'guard_id' => 'required',
            'days' => 'required',
            'shift_type_id' => 'required'
        ]);
        $duty_roster = Duty_Roster::where('id', $request->roster_id)->first();
        $days = json_decode($request->days);
        
        foreach($days as $day){
            $duty_roster->guards()->attach($request->guard_id, [
                'duty_roster_id' => $request->roster_id,
                'day' => $day,
                'shift_type_id' => $request->shift_type_id
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Guard added to roster'
        ]);
    }

    /**
     * --------------------------------
     * Remove guard from a duty roster
     * --------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeRoster(Request $request)
    {
        $request->validate([
            'guard_id' => 'required',
            'site_id' => 'required',
            'day' => 'required',
            'shift_type_id' => 'required',
            'complete_delete' => 'required'
         ]);



        $duty_roster = Duty_Roster::where('site_id', $request->site_id)->first();
        
        
        if($request->is_fired === "true"){
            $year = date('Y');
            $month = date('m');

            $guard = Guard::with(['deductions' => function($stmt) use ($month, $year){
                $stmt->whereYear('date', $year)->whereMonth('date', $month);
            }])->where('id', $request->guard_id)->first();
            
            $total_deduction = 0;
            
            foreach($guard->deductions as $deduction){
                $total_deduction+= $deduction->pivot->amount;
            }
            
            $s_salary = new Salary();
            $s_salary->guard_id = $guard->id;
            $s_salary->amount = $request->salary;
            $s_salary->month = date('Y-m');
            $s_salary->total_deductions = $total_deduction;
            $s_salary->bank_name = $guard->bank_name;
            $s_salary->bank_branch = $guard->bank_branch;
            $s_salary->account_number = $guard->account_number;
            $s_salary->status = 0;

            $s_salary->save();
                
                
            $client = Client::whereHas('sites', function($q) use ($request){
                $q->where('id', $request->site_id); 
            })->first();
 
            $salary = ClientSalary::where('client_id', $client->id)->where('guard_id', $request->guard_id)->update(['active' => 0]);
             
        }else{
            $guard = Guard::findOrFail($request->guard_id);
        }

        if($request->complete_delete === "false"){
            $guard->duty_rosters()->newPivotStatement()
            ->where('guard_id', $request->guard_id)
            ->where('duty_roster_id', $duty_roster->id)
            ->where('day', $request->day)
            ->where('shift_type_id', $request->shift_type_id)->delete();
        }else{
            $guard->duty_rosters()->newPivotStatement()
            ->where('guard_id', $request->guard_id)
            ->where('duty_roster_id', $duty_roster->id)->delete();
        }

        
        
        return response()->json([
            'status' => $guard,
            'complete_delete' => $request->complete_delete,
            'message' => $guard ? 'Guard removed from shift sucessfully' : 'Error removing guard from shift'
        ]);
    }

    /**
     * ---------------------------------------------------------
     * View all guards that can swap shifts with selected guard
     * ---------------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSwappers(Request $request){
        $request->validate([
            'guard' => 'required',
            'roster' => 'required'
        ]);

        $guard = $request->guard;
        $roster = $request->roster;
        

        $guards = DB::select(DB::raw("SELECT guard_id from guard_roster where shift_type_id <> (SELECT DISTINCT(shift_type_id) from guard_roster where guard_id = '$guard') and duty_roster_id = '$roster'"));
        $temp = array();

        foreach($guards as $guard){
            array_push($temp, $guard->guard_id);
        }

        $guards = Guard::find($temp);

        return response()->json([
            'error' => false,
            'guards' => $guards
        ]);
    }

    /**
     * --------------------------
     * swap shifts of two guards 
     * --------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function swap(Request $request){
        $request->validate([
            'swap_with' => 'required',
            'guard_id' => 'required',
            'roster_id' => 'required'
        ]);

        $swap_with = $request->swap_with;
        $guard = $request->guard_id;
        $roster_id = $request->roster_id;

        $query = DB::statement("UPDATE guard_roster SET guard_id = (case when guard_id = '$swap_with' then '$guard' else '$swap_with' end) where guard_id in ('$guard', '$swap_with') and duty_roster_id='$roster_id'");

        return response()->json([
            'error' => false,
            'message' => 'Shifts swapped'
        ]);
    }
}
