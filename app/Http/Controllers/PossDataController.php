<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\PossData;

class PossDataController extends Controller
{
    // 太田川：次亜塩素酸ナトリウム注入：前塩注入量を実測値と計算値で比較する
    // 参照データはviewで実測値と計算値はview上で計算済
    public function index(Request $request)
    {

        // 起点はuvb計測が始まる'2022/08/14'、終点はデータの最新日付
        // Viewの作成で制約すれば要らないかも
        $_startday = '2022-08-14';
        $_endday = PossData::where('day','>',$_startday)->max('day');
        $_today = date("Y/m/d");

        $_hms = substr(date("Y/m/d H:i:s"),11,5);

        // 検索フォームに入力された値を取得
        // （初期値は、最終データの３日前から最終データの日まで）
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        if (empty($date_from)){
            $date_from = date('Y-m-d', strtotime('-3 day', strtotime($_endday)));
        }
        if (empty($date_to)){
            $date_to = $_endday;
        }
        
        // 最小・最大データ（uvb記録が始まる22/08/14を起点に）
        $min_insolation = PossData::where('day','>',$_startday)->min('jma_insolation');
        $max_insolation = PossData::where('day','>',$_startday)->max('jma_insolation');
        $min_suion = PossData::where('day','>',$_startday)->min('suion');
        $max_suion = PossData::where('day','>',$_startday)->max('suion');

        // （meterの色を替えるため）maxの8割をhighのしきいに
        $high_insolation = $max_insolation * 0.8;
        $high_suion = $max_suion * 0.8;

        // From Toの間のデータを取得
        $test = PossData::where('day','>=',$date_from)->where('day','<=',$date_to)->get();

        // ロググラフ用のデータ配列
        $ymh_log = [];
        $x1_log = [];       // 太田川：次亜塩素酸ナトリウム注入：前塩注入量
        $x2_log = [];       // 計算値
        $enso_log = [];     // 太田川：沈殿池入口：残留塩素
        $suion_log = [];    // 太田川：着水井：水温
        $temperature_log = [];  // 気温（気象庁）
        $temperature_kadec_log = [];  // 気温（KADEC）
        $insolation_log = [];   // 日射量（気象庁）
        $insolation_kadec_log = []; // 日射量（KADEC）
        $uvb_kadec_log = []; // UVB（KADEC）
        foreach($test as $value){
            $ymh_log[] = substr($value["ymh"],3,9);  
            $x1_log[] = $value["x1"];  
            $x2_log[] = $value["x2"]; 
            $enso_log[] = $value["enso"] * 10; 
       
            $suion_log[] = $value["suion"]; 
            $temperature_log[] = $value["jma_temperature"]; 
            $temperature_kadec_log[] = $value["kadec_temperature"]; 
        
            $insolation_log[] = $value["jma_insolation"]; 
            $insolation_kadec_log[] = $value["kadec_insolation"]; 
            $uvb_kadec_log[] = $value["kadec_uvb"]; 
        }

        // 
        $label = $ymh_log;
        $green_log = $x1_log;
        $blue_log = $x2_log;
        $orange_log = $enso_log;

        $blue2_log = $suion_log;
        $orange2_log = $temperature_log;
        $red2_log = $temperature_kadec_log;

        $orange3_log = $insolation_log;
        $red3_log = $insolation_kadec_log;
        $blue3_log = $uvb_kadec_log;

        return view('poss1',[
            "label" => $label,
            "green_log" => $green_log,
            "blue_log" => $blue_log,
            "orange_log" => $orange_log,

            "blue2_log" => $blue2_log,
            "orange2_log" => $orange2_log,
            "red2_log" => $red2_log,

            "orange3_log" => $orange3_log,
            "red3_log" => $red3_log,
            "blue3_log" => $blue3_log,

            "min_insolation" => $min_insolation,
            "max_insolation" => $max_insolation,
            "high_insolation" => $high_insolation,
            "min_suion" => $min_suion,
            "max_suion" => $max_suion,
            "high_suion" => $high_suion,
            "date_from" => $date_from,
            "date_to" => $date_to,
            "_startday" => $_startday,
        ]);
 
 
    }
}
