<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IrateData;

class IrateApiController extends Controller
{
    //
    public function apiIrate(){

    //    return response()->json(
    //        [
    //           'Good morning' => 'おはよう',
    //            'Hello' => 'こんにちは',
    //        ]
    //    );

        $IrateData = IrateData::all();
        return $IrateData;
    }
}
