<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\PredData; // 未来24時間分の気象・水質・水量等予測データ（future_data）
use App\Models\KadecData; // KADEC21データ（trn_kadec_data）
use App\Models\MlconditionData; // 学習モデル（trn_mlcondition）
use App\Models\T24Data; // 可能性検討アプリが選択した学習モデル（t24_mlcondition）
use App\Models\CraftData; // 職員設定の注入率(craftman_rate)
use App\Models\IrateData; // 仮設注入機連携の注入率（injection_rate） ※自動・手動区分もあり
use App\Models\VpilData; // pi収集の最新データ（view_pi_latest）

class Pred1DataController extends Controller
{
    //
    public function index(Request $request)
    {
        // kadecの起点はuvb計測が始まる'2022/08/14' とする
        $_startday = '2022/08/14';
        // 学習モデル一覧（選択肢）を取得（お気に入り登録分のみ）
#        $mlconds = MlconditionData::where('favorite','=',1)->get();
        $mlconds = MlconditionData::where('favorite','=',1)->orderByRaw("id desc")->get();

        // 選択した予測用モデルはT24Dataに保存（1件のみ）その値をデータベースから取得
        $t24 = T24Data::first();

        // 学習モデルの選択値をフォームから取得
        $mlcond_id = $request->input('mlcond');

        // 学習モデルの選択値をデータベースに保存
        if(!empty($mlcond_id)) {
            $t24 = T24Data::first();
            $t24->mlconditionID = $mlcond_id;
            $t24->save();
        }

        // kadecの最小・最大データ（起点から現在までの）
        $min_uvb = KadecData::where('day','>',$_startday)->min('uvb');
        $max_uvb = KadecData::where('day','>',$_startday)->max('uvb');
        $min_insolation = KadecData::where('day','>',$_startday)->min('insolation');
        $max_insolation = KadecData::where('day','>',$_startday)->max('insolation');
        $min_temperature = KadecData::where('day','>',$_startday)->min('temperature');
        $max_temperature = KadecData::where('day','>',$_startday)->max('temperature');
        $min_humidity = KadecData::where('day','>',$_startday)->min('humidity');
        $max_humidity = KadecData::where('day','>',$_startday)->max('humidity');
        $min_windspeed = KadecData::where('day','>',$_startday)->min('avg_windspeed');
        $max_windspeed = KadecData::where('day','>',$_startday)->max('avg_windspeed');

        // （meterの色を替えるため）maxの8割をhighのしきいに
        $high_uvb = $max_uvb * 0.8;
        $high_insolation = $max_insolation * 0.8;
        $high_temperature = $max_temperature * 0.8;
        $high_humidity = $max_humidity * 0.8;
        $high_windspeed = $max_windspeed * 0.8;

        // kadexデータを取得
        $kadecs = KadecData::where('day','>',$_startday)->get();

        // 注入率データ
        $irate = IrateData::first();
        $injection_rate = $irate->injection_rate; // 注入率
        $injection_volume = $irate->injection_volume; // 注入量
        $ryunyu = $irate->ryunyu; // 流入量
        $is_automatic = $irate->is_automatic;

        // pi収集最新データを取得
        $pi1_val = VpilData::where('id','=',1)->value('pi'); // pi1の最新値
        $pi1_date = VpilData::where('id','=',1)->value('d'); // pi1の最新値の収集日時
        $pi2_val = VpilData::where('id','=',2)->value('pi'); // pi2の最新値
        $pi2_date = VpilData::where('id','=',2)->value('d'); // pi2の最新値の収集日時
        $pi3_val = VpilData::where('id','=',3)->value('pi'); // pi3の最新値
        $pi3_date = VpilData::where('id','=',3)->value('d'); // pi3の最新値の収集日時

        // 未来24時間の予測データを取得
        $preds = PredData::get();

        // 未来24時間の予測グラフ用のデータ配列
        $hms_log = [];
        $uvb_log = [];              // UVB
        $insolation_log = [];       // 日射量
        $temperature_log = [];      // 気温
        $y2_log = [];               // 注入率予想値
        $z2_log = [];               // 職員予想値
        foreach($preds as $value){
            $hms_log[] = $value["hour"];  
            $uvb_log[] = $value["uvb"];  
            $insolation_log[] = $value["insolation"];    
            $temperature_log[] = $value["temperature"];    
            $humidity_log[] = $value["humidity"];    
//            $y2_log[] = $value["y2"];    
            $y2_log[] = round($value["y2"] * 110 / $ryunyu,2); // 注入率に換算    

            if ($value["hour"] > 0){
                $day = $value["ymd"];
                $hms = sprintf('%02d',$value["hour"] -1) . ':00:00';
            }else{    
                $day = $value["ymd"] -1;
                $hms = '00:00:00';
            }
            $val = CraftData::where('day','=',$day)->where('hms','=',$hms)->value('injection_rate');;
            if ($val) {
                // 存在する
                $z2_log[] = $val;
            } else {
                // 存在しない
                $z2_log[] = 0;
            }
        }

        // 最終（最新）データを取得（KADEC現在値）
        $tail = $kadecs[count($kadecs)-1];
        // もし現在時刻との差異があれば、通信が途絶えていると判断し警告表示する
        $day = $tail["day"];
        $hms = $tail["hms"];
        $Time1 = $day . " " . $hms;
        $Time2 = date("Y/m/d H:i:s");
        
        $elapsed_time = round((strtotime($Time2) - strtotime($Time1)) / 60,1);

        $uvb = $tail["uvb"];
        $insolation = $tail["insolation"];
        $temperature = $tail["temperature"];
        $humidity = $tail["humidity"];
        $windspeed = $tail["avg_windspeed"];

        $mlconditionID = $t24->mlconditionID;
        // 
        $label = $hms_log;
        $green_log = $uvb_log;
        $blue_log = $insolation_log;
        $yyyy_log = $y2_log;
        $zzzz_log = $z2_log;

        return view('pred1',[
            "mlconditionID" => $mlconditionID,
            "mlconds" => $mlconds,
            "mlcond_id" => $mlcond_id,
            "label" => $label, // x軸のラベル
            "is_automatic" => $is_automatic, // 手動・自動の区分（1:自動）
            "green_log" => $green_log, // 緑のログ（UVB)
            "blue_log" => $blue_log, // 青のログ（日射量）
            "yyyy_log" => $yyyy_log, // AI予測の注入率
            "zzzz_log" => $zzzz_log, // 職員予測の注入率
            "day" => $day, // 今日の日付
            "hms" => $hms, // 現在の時刻
            "elapsed_time" => $elapsed_time, // KADECの最終データと現在時刻の差
            "uvb" => $uvb, // KADEC UVB
            "insolation" => $insolation, // KADEC 日射量
            "temperature" => $temperature, // KADEC 気温
            "humidity" => $humidity, // KADEC 湿度
            "windspeed" => $windspeed, // KADEC 風速
            "min_uvb" => $min_uvb, // KADEC UVB(最小、最大、橙にするしきい値)
            "max_uvb" => $max_uvb,
            "high_uvb" => $high_uvb,
            "min_insolation" => $min_insolation, // KADEC 日射量(最小、最大、橙にするしきい値) 
            "max_insolation" => $max_insolation,
            "high_insolation" => $high_insolation,
            "min_temperature" => $min_temperature, // KADEC 気温(最小、最大、橙にするしきい値)
            "max_temperature" => $max_temperature,
            "high_temperature" => $high_temperature,
            "min_humidity" => $min_humidity, // KADEC 湿度(最小、最大、橙にするしきい値)
            "max_humidity" => $max_humidity,
            "high_humidity" => $high_humidity,
            "min_windspeed" => $min_windspeed, // KADEC 風速(最小、最大、橙にするしきい値)
            "max_windspeed" => $max_windspeed,
            "high_windspeed" => $high_windspeed,
            "pi1_val" => $pi1_val, // pi1(残留塩素濃度)の最新データとその収集日時
            "pi1_date" => $pi1_date,
            "pi2_val" => $pi2_val, // pi2(伝導率)の最新データとその収集日時
            "pi2_date" => $pi2_date,
            "pi3_val" => $pi3_val, // pi3(流入量)の最新データとその収集日時
            "pi3_date" => $pi3_date,
            "ryunyu" => $ryunyu, // 流入量（injection_rateにバッチ処理で設定される）
        ]);
 
    }
}
