<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\PredData;
use App\Models\KadecData;
use App\Models\MlconditionData;
use App\Models\T24Data;

class PredDataController extends Controller
{
    //
    public function index(Request $request)
    {
        // kadecの起点はuvb計測が始まる'2022/08/14' とする
        $_startday = '2022/08/14';

        // 学習モデルの選択値をフォームから取得
        $mlcond_id = $request->input('mlcond');

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

        //
        if(!empty($mlcond_id)) {
            $t24 = T24Data::first();
            $t24->mlconditionID = $mlcond_id;
            $t24->save();
        }

        // 学習モデルを取得
        $mlconds = MlconditionData::where('favorite','=',1)->get();

        // kadexデータを取得
        $kadecs = KadecData::where('day','>',$_startday)->get();

        // 未来24時間の予測データを取得
        $preds = PredData::get();

        // 未来24時間の予測グラフ用のデータ配列
        $hms_log = [];
        $uvb_log = [];              // UVB
        $insolation_log = [];       // 日射量
        $temperature_log = [];      // 気温
        $y2_log = [];               // 注入率予想値
        foreach($preds as $value){
            $hms_log[] = $value["hour"];  
            $uvb_log[] = $value["uvb"];  
            $insolation_log[] = $value["insolation"];    
            $temperature_log[] = $value["temperature"];    
            $humidity_log[] = $value["humidity"];    
            $y2_log[] = $value["y2"];    
        }

        // 最終（最新）データを取得（KADEC現在値）
        $tail = $kadecs[count($kadecs)-1];
        // もし現在時刻との差異があれば、通信が途絶えていると判断し警告表示する
        $day = $tail["day"];
        $hms = $tail["hms"];
        $Time1 = $day . " " . $hms;
        $Time2 = date("Y/m/d H:i:s");
        
        //$ElapsedTime = (strtotime($Time2) - strtotime($Time1)) / 3600;
        $elapsed_time = (strtotime($Time2) - strtotime($Time1)) / 60;

        $uvb = $tail["uvb"];
        $insolation = $tail["insolation"];
        $temperature = $tail["temperature"];
        $humidity = $tail["humidity"];
        $windspeed = $tail["avg_windspeed"];

        // 計算値
        // 非常に多い　多い　やや多い　やや少ない　少ない　非常に少ない
        //$aaa = $insolation * 100;
        $aaa = $uvb / $max_uvb * 100;
        $bbb = 100 - $aaa;
        switch ($aaa){
            case $aaa > 70:
                $aaa_val = '非常に多い';
                break;
            case $aaa > 60:
                $aaa_val = '多い';
                break;
            case $aaa > 50:
                $aaa_val = 'やや多い';
                break;
            case $aaa > 40:
                $aaa_val = 'やや少ない';
                break;
            case $aaa > 30:
                $aaa_val = '少ない';
                break;
            default:
                $aaa_val = '非常に少ない';
                break;
        }

        // 
        $label = $hms_log;
        $green_log = $uvb_log;
        $blue_log = $insolation_log;
        $orange_log = $temperature_log;
        $red_log = $humidity_log;
        $yyyy_log = $y2_log;

        return view('pred',[
            "mlconds" => $mlconds,
            "mlcond_id" => $mlcond_id,
            "label" => $label,
            "green_log" => $green_log,
            "blue_log" => $blue_log,
            "orange_log" => $orange_log,
            "red_log" => $red_log,
            "yyyy_log" => $yyyy_log,
            "aaa" => $aaa,
            "aaa_val" => $aaa_val,
            "bbb" => $bbb,
            "day" => $day,
            "hms" => $hms,
            "elapsed_time" => $elapsed_time,
            "uvb" => $uvb,
            "insolation" => $insolation,
            "temperature" => $temperature,
            "humidity" => $humidity,
            "windspeed" => $windspeed,
            "min_uvb" => $min_uvb,
            "max_uvb" => $max_uvb,
            "high_uvb" => $high_uvb,
            "min_insolation" => $min_insolation,
            "max_insolation" => $max_insolation,
            "high_insolation" => $high_insolation,
            "min_temperature" => $min_temperature,
            "max_temperature" => $max_temperature,
            "high_temperature" => $high_temperature,
            "min_humidity" => $min_humidity,
            "max_humidity" => $max_humidity,
            "high_humidity" => $high_humidity,
            "min_windspeed" => $min_windspeed,
            "max_windspeed" => $max_windspeed,
            "high_windspeed" => $high_windspeed,
        ]);
 
    }

    public function executePython(Request $request) {
        ini_set("max_execution_time",120);
        $current = "\\DLEnso2\\bin\\";
        $path = $current . "future_data_create.bat " . $current;
    //    $command = "python " . $path;
        $command = $path;
        exec($command, $output);
        return redirect('pred');
    }

}
