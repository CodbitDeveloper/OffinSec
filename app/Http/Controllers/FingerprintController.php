<?php

namespace App\Http\Controllers;

use App\Fingerprint;
use Illuminate\Http\Request;
use App\Utils;
use App\Guard;

class FingerprintController extends Controller
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
        //
		
		$test = Fingerprint::where("guard_id", $request->guard_id)->first();
		
		if($test != null ){
			
			return response()->json([
				"error" => true,
				"message" => "This guard already has a fingerprint saved" 
			]);
			
		}
		
        $fingerprint = new Fingerprint();
        $fingerprint->RTB64 = $request->RTB64;
        $fingerprint->LTB64 = $request->RTB64;
        $fingerprint->RTISO = $request->RTB64;
        $fingerprint->LTISO = $request->RTB64;
        $fingerprint->guard_id = $request->guard_id;

        if($fingerprint->save()){
			return response()->json([
				"error" => false,
				"message" => "Fingerprint saved";
			]);
		}else{
			return response()->json([
				"error" => true,
				"message" => "Could not save fingerprint";
			]);
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fingerprint  $fingerprint
     * @return \Illuminate\Http\Response
     */
    public function show(Fingerprint $fingerprint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fingerprint  $fingerprint
     * @return \Illuminate\Http\Response
     */
    public function edit(Fingerprint $fingerprint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fingerprint  $fingerprint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fingerprint $fingerprint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fingerprint  $fingerprint
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fingerprint $fingerprint)
    {
        //
    }

    public function saveImage(Request $request){
		$request->validate([
			"guard_id" => "required",
			"image" => "required"
		]);
		
        $guard = Guard::where("id", $request->guard_id)->first();
		
		if($guard != null){
			$fileName = Utils::saveBase64Image($request->image, microtime().'-'.$guard->firstname, 'assets/images/guards/');
			$guard->photo = $fileName;
			$guard->save();
			return response()->json(["error" => false]);
		}
		
		return response()->json(["error" => true]);
    }
}
