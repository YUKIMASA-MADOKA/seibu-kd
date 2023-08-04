<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\CraftData;
use DateTime;

class CraftDataController extends Controller
{

    // 職員設定の注入率の設定(craftman_rate)
    public function auto(Request $request)
    {
        return redirect('craft');
    }

    // 職員設定の注入率の設定(craftman_rate)
    public function update(Request $request)
    {
        // 画面入力値（注入率）を取得
        $str = $request->input('idarray');
        $arr = explode(':', $str);

        for($i = 0; $i < 24; $i++){
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
        // 設定日の初期値は本日
        $now = new DateTime();
        $startday = $now->format('Y-m-d');

        // フォームに入力された設定日を取得
        $date_from = $request->input('date_from');
        if (!empty($date_from)){
            $startday = $date_from;
        }

        // デバック用の変数（画面表示用）
        $debug = '';

        // 時間の配列
        $timearray = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
        // 指定日のレコードが無い場合は、注入率を初期値=0の状態で作成
        for ($i = 0; $i < 24; $i++){
            $existsOrNot = CraftData::where('day','=',$startday)->where('hms','=',$timearray[$i] . ':00:00')->exists();
            if ($existsOrNot) {
                // 存在する
            } else {
                // 存在しない
                $craft = new CraftData();
                $craft->create([
                  'day' => $startday,
                  'hms' => $timearray[$i] . ':00:00',
                  'injection_rate' => 0,
                ]);
            }
        }

        // 指定日の注入率をデータベースから取得    
        $crafts = CraftData::where('day','=',$startday)->orderByRaw("day asc, hms asc")->get();

        // form(blade)にセットするデータのキー（id）をhideenでセットするため（':'を区切り文字にした文字列作成）
        $idarray = '';
        foreach($crafts as $value){
            $idarray = $idarray . $value['id'] . ':';
        }

        // 画面にパラメタを渡す
        return view('craft',[
            "startday" => $startday,
            "idarray" => $idarray,
            "crafts" => $crafts,
            "debug" => $debug,
        ]);
 
    }
}
