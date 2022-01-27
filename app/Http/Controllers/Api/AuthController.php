<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthenticateRequest;
use App\Site;

class AuthController extends Controller
{
    //

    public function authenticate(AuthenticateRequest $request)
    {
        $site = Site::where("access_code", $request->access_code)->with("scannable_areas")->first();

        if(is_null($site)){
            return response()->json([
                "message" => "Invalid site ID"
            ], 401);
        }

        return response()->json([
            "data" => $site
        ]);
    }
}
