<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePatrolRequest;
use App\Patrol;

class PatrolController extends Controller
{
    //
    public function store(StorePatrolRequest $request)
    {
        $patrol = Patrol::create($request->all());
        $patrol->scans()->attach($request->scans);

        if($request->has("images") && count($request->images) > 0){
            $arrayToAttach = array();

            foreach($request->images as $image){
                $arrayToAttach[] = [
                    "url" => $image
                ];
            }

            $patrol->images()->createMany($arrayToAttach);
        }

        return response()->json([
            "data" => $patrol
        ]);
    }
}
