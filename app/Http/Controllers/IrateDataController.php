<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\IrateData; // 仮設注入機連携の注入率（injection_rate）
use App\Models\IrateLog; // 上記テーブルのAPI参照および更新ログ（injection_rate_log）

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
                $is_automatic = 1; // ログ用の変数
            }else{
                $irate->is_automatic = 0; // 手動
                $is_automatic = 0; // ログ用の変数
            }
            $irate->save();

            // アクセス（更新）ログを記録
            $log = new IrateLog();
            $log->create([
                'log' => 'UPDATE is_automatic',
                'injection_rate' => 0,
                'is_automatic' => $is_automatic,
            ]);

        } else {
        }
        return redirect('irate');
    }

    // 仮設注入機に連携する注入率(injection_rate)の設定
    public function index(Request $request)
    {
        // 初期値設定
        $is_automatic = 0;  // 手動・自動フラグ（1:自動）
        $injection_rate = 0;    // 注入率
        $injection_volume = 0;    // 注入量　※画面入力なし（参考表示用）
        $ryunyu = 1;    // 流入量　※画面入力なし（参考表示用）

        // 現在の注入率(injection_rate)をデータベースから取得
        // ※仮設注入機がひとつの前提で（将来増える場合にはidで）    
        $irate = IrateData::first();
        if(!empty($irate)) {
            // 存在する
            $is_automatic = $irate->is_automatic;
            $injection_rate = $irate->injection_rate;
            $injection_volume = $irate->injection_volume;
            $ryunyu = $irate->ryunyu;
        }else{
            // 存在しない（レコードを追加）
            $irate = new IrateData();
            $irate->create([
                'is_automatic' => $is_automatic,
                'injection_rate' => $injection_rate,
                'injection_volume' => $injection_volume,
                'ryunyu' => $ryunyu,
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

        // 画面入力値がある場合はデータベースに保存（無い場合はデータベース値を採用）
        if(!empty($i_rate)) {
            $irate = IrateData::first();
            $irate->injection_rate = $i_rate;
            $irate->save();

            // アクセス（更新）ログを記録
            $log = new IrateLog();
            $log->create([
                'log' => 'UPDATE injection_rate',
                'injection_rate' => $i_rate,
            //    'is_automatic' => 0,
            //    'injection_volume' => 0,
            //    'ryunyu' => 0,
            ]);

        } else {
            $i_rate = $injection_rate;
        }
        
        // APIのアクセスログを取得（逆順に最新20件）
        $logs = IrateLog::where('log','=','API')->orderByRaw("id desc")->take(20)->get();

        // 注入率の円グラフ表示用にグレーの値（余り）を計算
        $i_rem = 2.50 - $i_rate;
        $is_auto1 = 1;
        // 
        return view('irate',[
            "i_rate" => $i_rate, // 注入率
            "i_rem" => $i_rem, // 余り（1.00 - 注入率）、グレーの部分
            "is_auto" => $is_auto, // 手動・自動（ラジオボタン用の文字列）
            "is_automatic" => $is_automatic, // 手動・自動（scriptで判定用の数値型）
            "injection_rate" => $injection_rate, // 注入率（自動運転時の参考表示用）
            "injection_volume" => $injection_volume, // 注入量（自動運転時の参考表示用）
            "ryunyu" => $ryunyu, // 流入量（自動運転時の参考表示用）
            "logs" => $logs, // APIのアクセスログ
        ]);
    }
}
