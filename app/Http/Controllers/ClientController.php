<?php

namespace App\Http\Controllers;

use App\Client;
use App\Guard;
use App\Site;
use App\Access_Code;
use App\Role;

use DB;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::with('sites', 'sites.duty_roster.guards')->get();

        foreach($clients as $client){
            $assigned_guards = array();

            foreach($client->sites as $site){
                if($site->duty_roster != null){
                    foreach($site->duty_roster->guards as $guard){
                        array_push($assigned_guards, $guard);
                    }
                }
            }

            $client->guards = collect($assigned_guards)->unique('id');
        }


        return view('clients')->with('clients', $clients);
    }

    /**
     * ------------------------
     * Get client with sites
     * ------------------------
     * 
     * @return \Illuminate\Http\Response
     */
    public function getClientSites()
    {
        $client = Client::with('sites')->get();

        return response()->json($client, 200);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('add-client');
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
            'name' => 'required|string',
            'phone_number' => 'required',
            'email' => 'required',
            'contact_person_name' => 'required',
            'number_of_guards' => 'required',
            'description' => 'required',
            'start' => 'required',
            'end' => 'required'
        ]);

        $client = new Client();
        
        $client->name = $request->name;
        $client->phone_number = $request->phone_number;
        $client->email = $request->email;
        $client->contact_person_name = $request->contact_person_name;
        $client->number_of_guards = $request->number_of_guards;
        $client_id = md5(microtime().$client->name.'OFFINCLIENT');
        $client_id = substr($client_id, 0, 12);
        $client->id = $client_id;
        $client->description = $request->description;
        $client->start_date = date('Y-m-d', strtotime($request->start));
        $client->end_date = date('Y-m-d', strtotime($request->end));

        if(Client::onlyTrashed()->where('email', $request->email)->get()->count() > 0){
            $client_temp = Client::onlyTrashed()->where('email', $request->email)->first();

            $client_temp->email = 'DELETED ==> '.$client_temp->email.'___'.$client_temp->id;
            
            $client_temp->save();
        }

        if($client->save()){
            return response()->json([
                "error" => false, 
                'data' => $client,
                'message' => 'Client created successfully'
            ]);
        }else{
           return response()->json([
               'error' => true,
               'message' => 'Error creating client'
           ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $client = new Client();

        return view('edit-client', \compact('cleint'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone_number' => 'required',
            'email' => 'required',
            'contact_person_name' => 'required',
            'number_of_guards' => 'required',
            'id' => 'required'
        ]);
        
        $client = Client::where('id', $request->id)->first();
        $client->name = $request->name;
        $client->phone_number = $request->phone_number;
        $client->email = $request->email;
        $client->contact_person_name = $request->contact_person_name;
        $client->number_of_guards = $request->number_of_guards;
        
        if($client->update()){
            return response()->json([
                'error' => false,
                'data' => $client,
                'message' => 'Client Updated Successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error updating client'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $status = $client->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Client Deleted' : 'Error Deleting Client'
        ]);
    }

    /**
     * -------------------------------------
     * Display client with sites and guards
     * -------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return view
     */
    public function view(Request $request){
        $client = Client::with('sites', 'sites.duty_roster.guards')
        ->where('id', $request->id)->first();
        
        $assigned_guards = array();
        
        foreach($client->sites as $site){
            if($site->duty_roster != null){
                foreach($site->duty_roster->guards as $guard){
                    array_push($assigned_guards, $guard);
                }
            }
        }

        $client->guards = collect($assigned_guards)->unique('id');
        
        $guards = Guard::all();

        return view('client-details')->with('client', $client)->with('guards', $guards);
    }

    /**
     * -----------------------------
     * Generate report for client 
     * -----------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request){
        $request->validate([
            'site' => 'required',
            'client' => 'required',
            'date' => 'required'
        ]);

        $date = date('Y-m-d', strtotime($request->date));
        $client = $request->client;
        $site = $request->site;
        
        $sites = DB::select(DB::raw("SELECT count(id) as total, DAYOFWEEK(date_time) as day from attendances where WEEK(date_time) = WEEK('$date') and site_id = '$site' GROUP BY  day"));
        $site = Site::where('id', $site)->first();

        return response()->json([
            'error' => false,
            'sites' => $sites,
            'site' => $site
        ]);
    }

    /**
     * ------------------------------------------
     * Change or update contract duration (date)
     * -------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changeDate(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        
        $client = Client::where('id', $request->id)->first();

        $client->start_date = date('Y-m-d', strtotime($request->start_date));
        $client->end_date = date('Y-m-d', strtotime($request->end_date));

        if($client->update()){
            return response()->json([
                'error' => false,
                'message' => 'Contract duration has been updated'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error updating contract duration'
            ]);
        }

    }

    /**
     * ------------------------------
     * Display client access page
     * ------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return view 
     */
    public function clientAccess(Request $request)
    {
        $date = date('Y-m-d');

        $client = Access_Code::whereRaw("access_code='$request->token' AND DATE(expires_at) >= '$date'")->first();

        if($client == null){
            return abort(403);
        }

        $client = Client::where('id', $client->client_id)->with('sites', 'sites.duty_roster.guards')->first();
        $assigned_guards = array();

        foreach($client->sites as $site){
            if($site->duty_roster != null){
                foreach($site->duty_roster->guards as $guard){
                    array_push($assigned_guards, $guard);
                }
            }
        }

        $client->guards = collect($assigned_guards)->unique('id');

        return view('client-access')->with('client', $client);
    }

    /**
     * -------------------------
     * Manage client salaries
     * -------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return view
     */
    public function manageSalaries(Request $request){
        $client = $request->id;
        $client = Client::where('id', $client)->with('sites', 'sites.duty_roster')->with(['sites.duty_roster.guards' => function($q) use ($client){
            $q->with(['client_salary' => function($query) use ($client){
                $query->where([['client_id', $client], ['active', 1]]);
            }])->groupBy('guard_id');
        }])->first();

        $ranks = Role::all();

        return view('client-salaries')->with('client', $client)->with('ranks', $ranks);
    }
}
