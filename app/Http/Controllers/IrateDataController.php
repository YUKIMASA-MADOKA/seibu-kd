<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\IrateData;

class IrateDataController extends Controller
{
    // 仮設注入機用に注入率の設定(injection_rate)
    public function index(Request $request)
    {
        // 現在の注入率をデータベースから取得    
        $IrateData = IrateData::first();
    
        // 画面入力値も取得
        $injection_rate = $request->input('irate');

        // 画面入力がある場合はデータベースに保存（無い場合はデータベース値を採用）
        if(!empty($injection_rate)) {
            $IrateData = IrateData::first();
            $IrateData->injection_rate = $injection_rate;
            $IrateData->save();
        } else {
            $injection_rate = $IrateData->injection_rate;
        }
        
        // 注入率の円グラフ表示用
        $aaa = $injection_rate;
        $bbb = 100 - $aaa;
        // 

        return view('irate',[
            "aaa" => $aaa,
            "bbb" => $bbb,
        ]);
 
    }
}
