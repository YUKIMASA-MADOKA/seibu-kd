<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\IrateData;

class IrateDataController extends Controller
{

    // 仮設注入機制御用の手動・自動モードの設定(injection_rate)
    public function auto(Request $request)
    {
        // 画面入力値を得る
        $is_auto = $request->input('is_auto');

        // データ（injection_rateの自動フラグ）を更新
        if(!empty($is_auto)) {
            $irate = IrateData::first();
            if($is_auto == 'auto'){
                $irate->is_automatic = 1; // 自動
            }else{
                $irate->is_automatic = 0; // 手動
            }
            $irate->save();
        } else {
        }
        return redirect('irate');
    }

    // 仮設注入機用に注入率の設定(injection_rate)
    public function index(Request $request)
    {
        // 初期値設定
        $is_automatic = 0;  // 手動・自動フラグ（1:自動）
        $injection_rate = 0;    // 注入率

        // 現在の注入率をデータベースから取得    
        $irate = IrateData::first();
        if(!empty($irate)) {
            // 存在する
            $is_automatic = $irate->is_automatic;
            $injection_rate = $irate->injection_rate;
        }else{
            // 存在しない（レコードを追加）
            $irate = new IrateData();
            $irate->create([
                'is_automatic' => $is_automatic,
                'injection_rate' => $injection_rate,
            ]);
        }
        
        // 手動・自動のラジオボタンを設定 
        if($is_automatic == 1){
            $is_auto = 'auto';
        }else{
            $is_auto = 'manual';
        }

        // 注入率を画面から取得
        $i_rate = $request->input('i_rate');

        // 画面入力がある場合はデータベースに保存（無い場合はデータベース値を採用）
        if(!empty($i_rate)) {
            $irate = IrateData::first();
            $irate->injection_rate = $i_rate;
            $irate->save();
        } else {
            $i_rate = $injection_rate;
        }
        
        // 注入率の円グラフ表示用にグレーの値（余り）を計算
        $i_rem = 100 - $i_rate;
        $is_auto1 = 1;
        // 
        return view('irate',[
            "i_rate" => $i_rate, // 注入率
            "i_rem" => $i_rem, // 余り（100 - 注入率）、グレーの部分
            "is_auto" => $is_auto, // 手動・自動（ラジオボタン用の文字列）
            "is_automatic" => $is_automatic, // 手動・自動（scriptで判定用の数値型）
        ]);
 
    }
}
