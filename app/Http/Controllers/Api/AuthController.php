<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthenticateRequest;
use App\Site;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    public function login(Request $request)
    {
        $request->validate([
            "username" => ["required", "string"],
            "password" => ["required", "string"]
        ]);


        $credentials = $request->only("username", "password");

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
 	
 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->validate([
            "token" => "required"
        ]);

        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function user()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->load("sites", "sites.scannable_areas");

        return response()->json([
            "data" => $user
        ]);
    }
}
