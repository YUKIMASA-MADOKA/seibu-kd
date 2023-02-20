<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\KadecData;

class KadecDataController extends Controller
{
    //
    public function index()
    {
        // 本日のデータを取得（取得できないときにはエラー表示）
        $test = KadecData::where('day','2023/01/06')->get();
        // echo $test;

        //        $hms_log = array_column($test,'hms');
//        echo $hms_log;
        $hms_log = "";
        foreach($test as $value){
        //    echo $value["hms"];
        //    $hms_log = array_push($hms_log,$value["hms"]);  
            $hms_log = $hms_log . "\"" . $value["hms"] . "\",";  
        }
        echo $hms_log;
        //var_dump $hms_log;

        // 最終（最新）データを取得
        $test1 = $test[count($test)-1];
        echo $test1;

        $day = $test1["day"];
        $hms = $test1["hms"];
        $uvb = $test1["uvb"];
        $insolation = $test1["insolation"];
        $temperature = $test1["temperature"];
        $humidity = $test1["humidity"];
        $windspeed = $test1["avg_windspeed"];

        // 計算値
        $aaa = $insolation * 100;
        $bbb = 100 - $aaa;

        //$labels = ["1400","1410","1420","1430","1440","1450","1500","1510","1520","1530"];
        //return view('test4',compact('day','hms','uvb','insolation','temperature','humidity','windspeed','labels'));
        //return view('test4',compact('day','hms','uvb','insolation','temperature','humidity','windspeed'));
        return view('test2',[
            "label" => ["0000","0010","0020","0030","0040","0050","0100","0110","0120","0130","0140","0150","0200","0210","0220","0230","0240","0250","0300","0310","0320","0330","0340","0350","0400","0410","0420","0430","0440","0450","0500","0510","0520","0530","0540","0550","0600","0610","0620","0630","0640","0650","0700","0710","0720","0730","0740","0750","0800","0810","0820","0830","0840","0850","0900","0910","0920","0930","0940","0950","1000","1010","1020","1030","1040","1050","1100","1110","1120","1130","1140","1150","1200","1210","1220","1230","1240","1250","1300","1310","1320","1330","1340","1350","1400","1410","1420","1430","1440","1450","1500","1510","1520","1530","1540"],
            "green_log" => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0.02,0.03,0.04,0.06,0.08,0.11,0.15,0.17,0.2,0.22,0.23,0.26,0.3,0.31,0.31,0.36,0.44,0.47,0.49,0.52,0.49,0.4,0.43,0.47,0.57,0.59,0.57,0.54,0.59,0.6,0.6,0.58,0.6,0.62,0.54,0.45,0.51,0.53,0.45,0.61,0.61,0.54,0.49,0.45,0.41,0.39,0.37,0.34,0.23,0.18,0.18,0.14],
            "blue_log" => [10,10,10,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0.02,0.03,0.04,0.06,0.08,0.11,0.15,0.17,0.2,0.22,0.23,0.26,0.3,0.31,0.31,0.36,0.44,0.47,0.49,0.52,0.49,0.4,0.43,0.47,0.57,0.59,0.57,0.54,0.59,0.6,0.6,0.58,0.6,0.62,0.54,0.45,0.51,0.53,0.45,0.61,0.61,0.54,0.49,0.45,0.41,0.39,0.37,0.34,0.23,0.18,0.18,0.14],
            "aaa" => $aaa,
            "bbb" => $bbb,
            "day" => $day,
            "hms" => $hms,
            "uvb" => $uvb,
            "insolation" => $insolation,
            "temperature" => $temperature,
            "humidity" => $humidity,
            "windspeed" => $windspeed,
        ]);
 
 
    }
}
