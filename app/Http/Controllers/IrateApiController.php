<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IrateData; // 仮設注入機連携の注入率（injection_rate）
use App\Models\IrateLog; // 上記テーブルのAPI参照および更新ログ（injection_rate_log）

class IrateApiController extends Controller
{
    //
    public function apiIrate(){

      // 注入率データ
      $irate = IrateData::first();

      $injection_rate = $irate->injection_rate;
      $injection_volume = $irate->injection_volume;
      $ryunyu = $irate->ryunyu;
      $is_automatic = $irate->is_automatic;

      // アクセスログを記録
      $log = new IrateLog();
      $log->create([
        'log' => 'API',
        'injection_rate' => $injection_rate,
        'injection_volume' => $injection_volume,
        'ryunyu' => $ryunyu,
        'is_automatic' => $is_automatic,
      ]);

      return $irate;
    }
}
