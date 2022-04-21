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

    public function update(Request $request, ScannableArea $scannableArea)
    {
        $scannableArea->update($request->all());
        return redirect(url()->previous())->with("success", "Scannable area updated");
    }
}
