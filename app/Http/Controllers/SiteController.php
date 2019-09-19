<?php

namespace App\Http\Controllers;

use App\Site;
use App\Guard;

use DB;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = Site::all();

        return response()->json($sites, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('add-site');
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
            'name' => 'required',
            'location' => 'required',
            'phone_number' => 'required',
            'guard_id' => 'required',
        ]);
        
        if(Site::where([['name', $request->name], ['client_id', $request->client_id]])->get()->count() > 0){
            return response()->json([
                'error' => true,
                'message' => 'Site name already exists in this client'
            ]);
        }

        $site = new Site();

        $site->client_id = $request->client_id;
        $site->name = $request->name;
        $site->location = $request->location;

        $site->phone_number = $request->phone_number;
        $site->guard_id = $request->guard_id;
        $code = md5(microtime().$request->name);
        $code = substr($code, 0, 6);
        $site->access_code = $code;
        
        if($site->save()){
            return response()->json([
                'data' => $site,
                'error' => false,
                'message' => 'Site created successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error creating site'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        $site = new Site();

        return view('edit-site', \compact('site'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'client_id' => 'required',
            'name' => 'required',
            'location' => 'required',
            'phone_number' => 'required', 
            'guard_id' => 'required',
        ]);
        
        $site = Site::where('id', $request->id)->first();
        $site->client_id = $request->client_id;
        $site->name = $request->name;
        $site->location = $request->location;
        $site->phone_number = $request->phone_number;
        $site->guard_id = $request->guard_id;

        if($site->update()){
            return response()->json([
                'error' => false,
                'data' => $site,
                'message' => 'Site updated successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error updating site'
            ]); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        //
    }

    public function setupApp(Request $request){
        $request->validate([
            'site' => 'required'
        ]);
        
        $site_id = $request->site;
        $site = Site::where('access_code', $site_id)->first();

        if($site == null){
            return response()->json([
                'error' => true,
                'message' => 'No site with the specified access code'
            ]);
        }

        
        $site_id = $site->id;

        $filter = function($q) use($site_id){
            $q->where('site_id', $site_id);
        };

        $guards = DB::select(DB::raw("SELECT guards.*, fingerprints.rtb64 FROM guards INNER JOIN (duty_rosters, sites, guard_roster) ON guard_roster.guard_id = guards.id and duty_rosters.site_id = sites.id and guard_roster.duty_roster_id = duty_rosters.id and sites.id = '$site_id' LEFT OUTER JOIN fingerprints ON guards.id = fingerprints.guard_id WHERE guards.deleted_at IS NULL"));
		
		/*$guards = Guard::whereHas("duty_rosters", function($q) use($site_id){
            $q->where('site_id', $site_id);
        })->with("fingerprints")*/

        $guards = collect($guards);
        $guards = $guards->unique('id')->values()->toArray();
        
        return response()->json([
            'error' => false,
            'message' => 'Site retrieved',
            'site' => $site,
            'guards' => $guards
        ]);
    }

    /* public function getGuards(Request $request){
        $request->validate([
            'site' => 'required'
        ]);

        $site = Site::where('id', $request->site)->with('duty_roster')->with(['duty_roster.guards' => function($q){
            $q->groupBy('guard_id');
        }])->first();

        return response()->json([
            'data' => $site
        ]);
    } */

    public function viewSite(Request $request)
    {
        $site = Site::where('id', $request->id)->with('client', 'supervisor', 'contacts')->with('duty_roster')->with(['duty_roster.guards' => function($q){
            $q->groupBy('guard_id');
        }])->first();

        $guards = Guard::all();

        return view('site-details')->with('site', $site)->with('guards', $guards);
        /* return response()->json([
            'site' => $site
        ]); */
    }
}
