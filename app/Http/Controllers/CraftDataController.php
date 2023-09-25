<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\CraftData; // 職員設定の注入率(craftman_rate)
use DateTime;

class CraftDataController extends Controller
{

    // 職員設定の注入率の設定(craftman_rate)
    public function update(Request $request)
    {
        // 画面入力値（注入率）を取得
        $str = $request->input('idarray'); // 明細表示データのid（キー）
        $cnt = (int) $request->input('idcnt'); // 明細表示件数
        $arr = explode(':', $str);

        // 明細行数分繰り返し（値がある場合に更新）
        for($i = 0; $i < $cnt; $i++){
            $ix = $arr[$i];
            $rate = $request->input('rate'.$ix);
    
            if(!empty($rate)) {
                $CraftData = CraftData::where('id','=',$ix)->first();
                $CraftData->injection_rate = $rate;
                $CraftData->save();
            }
        }
        return redirect('craft');
    }

    // 職員設定の注入率の設定(craftman_rate)
    public function index(Request $request)
    {
        // 設定の初期値は現在時刻からの24時間
        $now = new DateTime();
        $startday = $now->format('Y-m-d');
        $starttime = $now->format('H:00');

        $endday = $now->modify('+1 days')->format('Y-m-d');     // 翌日同時刻までが初期値
        $enddayx = $now->modify('+4 days')->format('Y-m-d');    // 設定可能な日は最大5日まで
        $endtime = $now->format('H:00');

        // フォームに入力された設定日を取得
        $date_from = $request->input('date_from');
        $time_from = $request->input('time_from');
        $date_to = $request->input('date_to');
        $time_to = $request->input('time_to');
        if (!empty($date_from)){
            $startday = $date_from;
        }
        if (!empty($time_from)){
            $starttime = $time_from;
        }
        if (!empty($date_to)){
            $endday = $date_to;
        }
        if (!empty($time_to)){
            $endtime = $time_to;
        }
        $startday_hms = $startday.' '.$starttime;   // 日付と時刻の文字列結合
        $endday_hms = $endday.' '.$endtime;   // 日付と時刻の文字列結合

        // デバック用の変数（画面表示用）
        // $debug = '';startday

        // 時間の配列
        $timearray = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');

        // 指定期間内のデータを確認し、存在しない場合は初期値を設定
        $refday = $startday;
        $x=1;
        while($x <= 10){
            if ($refday > $enddayx){
                break;
            }
            if ($refday > $endday){
                break;
            }
            // 指定日のレコードが無い場合は、注入率を初期値=0の状態で作成
            for ($i = 0; $i < 24; $i++){
                $existsOrNot = CraftData::where('day','=',$refday)->where('hms','=',$timearray[$i] . ':00:00')->exists();
                if ($existsOrNot) {
                    // 存在する
                } else {
                    // 存在しない
                    $craft = new CraftData();
                    $craft->create([
                    'day' => $refday,
                    'hms' => $timearray[$i] . ':00:00',
                    'day_hms' => $refday . ' ' . $timearray[$i] . ':00:00',
                    'injection_rate' => 0,
                    ]);
                }
            }
            $refday = date('Y-m-d', strtotime($refday . '+1 day'));
            $x++;
        }

        // 指定日の注入率をデータベースから取得 （開始日時から終了日時まで、ただし最大設定可能期間は5日間）   
        $crafts = CraftData::where('day_hms','>=',$startday_hms)->where('day_hms','<=',$endday_hms)->where('day','<=',$enddayx)->orderByRaw("day asc, hms asc")->get();
//        $crafts = CraftData::where('day','=',$startday)->orderByRaw("day asc, hms asc")->get();

        // form(blade)にセットするデータのキー（id）をhideenでセットするため（':'を区切り文字にした文字列作成）
        $idarray = '';
        $idcnt = 0;
        foreach($crafts as $value){
            $idarray = $idarray . $value['id'] . ':';
            $idcnt = $idcnt +1;
        }

        // 画面にパラメタを渡す
        return view('craft',[
//            "debug" => $endday_hms,
            "startday" => $startday,
            "starttime" => $starttime,
            "endday" => $endday,
            "endtime" => $endtime,
            "idarray" => $idarray,
            "idcnt" => $idcnt,
            "crafts" => $crafts,
        //    "debug" => $debug,
        ]);
 
    }
}
