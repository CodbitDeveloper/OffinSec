<?php

namespace App\Http\Controllers;

use App\ScannableArea;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ScannableAreaController extends Controller
{
    //

    public function store(Request $request)
    {
        $scannableArea = ScannableArea::make($request->all());
        $scannableArea->location_code = Str::uuid();
        $scannableArea->save();

        return response()->json([
            "error" => false,
            "data" => $scannableArea
        ]);
    }
}
