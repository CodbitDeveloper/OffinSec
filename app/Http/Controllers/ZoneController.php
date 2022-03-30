<?php

namespace App\Http\Controllers;

use App\User;
use App\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    //
    public function index()
    {
        $users = User::where("role", "zone-supervisor")->get();
        $zones = Zone::with("user")->get();;

        return view("zones", compact('users', 'zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "user_id" => "required|string"
        ]);

        $zone = Zone::create($request->all());

        if($zone){
            return response()->json([
                "error" => false,
                "data" => $zone,
                "message" => "Zone successfully created"
            ]);
        }else{
            return response()->json([
                "error" => true,
                "message" => "Could not create zone"
            ]);
        }
    }

    public function update(Request $request, Zone $zone)
    {
        $request->validate([
            "name" => "required|string",
            "user_id" => "required|string"
        ]);

        if($zone->update($request->all())){
            return response()->json([
                "error" => false,
                "data" => $zone,
                "message" => "Zone successfully updated"
            ]);
        }else{
            return response()->json([
                "error" => true,
                "message" => "Could not update zone"
            ]);
        }
    }

    public function delete(Request $request, Zone $zone)
    {
        if($zone->delete()){
            return response()->json([
                "error" => false,
                "message" => "Zone succesfully deleted"
            ]);
        }

        return response()->json([
            "error" => true,
            "message" => "Could not delete zone"
        ]);
    }
}
