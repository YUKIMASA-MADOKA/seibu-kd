@extends('layouts.app')
@section('content')

   <h1>仮設注入機の制御（※現在制作中）</h1>
   <h5>連携する注入率：
   {{ old('is_auto', $is_auto) == "manual" ? '手動モードです。下のフォームから注入率を設定できます。' : '現在自動運転中です。' }} 
   （連携はWebAPI）
   </h5>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <p>DEBUG ---- </p>
    　is_auto:{{ $is_auto }}
    　i_rate:{{ $i_rate }}
    　i_rem:{{ $i_rem }}
    <p>DEBUG ---- </p>

    <h3>■手動・自動の切替</h3>
   <form action="{{ route('auto_irate') }}" method="GET">
      @csrf
      <div style="display:inline;">
         <input type="radio" name="is_auto" id="is_auto" value="manual"
          {{ old('is_auto', $is_auto) == "manual" ? 'checked' : '' }} 
         />手動
         <input type="radio" name="is_auto" id="is_auto" value="auto" 
          {{ old('is_auto', $is_auto) == "auto" ? 'checked' : '' }} 
         />自動
         <input type="submit" class="btn" value="設定変更" />
      </div>
   </form>

   <p></p>
   <h3>■注入率の設定と現在の設定値</h3>
   <table>
      <thead>
         <tr><th>
         {{ old('is_auto', $is_auto) == "manual" ? '・注入率を設定できます。' : '・自動運転中のため設定できません。' }} 
         </th><th>・現在の設定値</th></tr>
      </thead>
      <tbody>
         <tr>
         <!-- 注入率入力 -->
         <td>
            <form action="{{ route('index_irate') }}" method="GET">
               @csrf
            <div style="font-size:xx-large">
               <label>
               注入率：<span></span>
               </label>
                  <input type="number" name="i_rate" id="i_rate" min="0" max="100" step="5" value={{ $i_rate }}
                  {{ old('is_auto', $is_auto) == "manual" ? '' : 'disabled' }} 
                  />
               </div>
               5を単位に増減する0から100までの値で設定してください。
            <div><input type="submit" class="btn" value="この値で設定する"
            {{ old('is_auto', $is_auto) == "manual" ? '' : 'disabled' }} 
            /></div>
            </form>
         </td>

         <!-- 現在の設定率 -->
         <td>
            <canvas id="graph-area1" height="200" width="300"></canvas>
            <p style="font-size:large;text-align:center">{{$i_rate}} %</p>
            </td>
         </tr>
      </tbody>
   </table>
   <h3>■（参考）連携WebAPIは次のような形式で</h3>
   http://hamakaze:8081/api/irate

@endsection('content')

@section('script')
<script type="text/javascript">
   var i_rate = {{$i_rate}};
   var i_rem = {{$i_rem}};
   // ▼グラフの中身
   var pieData = [
      {
         value: i_rate,            // 値
         color:"royalblue",       // 色
         highlight: "steelblue",  // マウスが載った際の色
         label: "設定値"        // ラベル
      },
      {
         value: i_rem,
         color: "gray",
      },
   ];

   // 自動・手動のフラグ（1:自動、変数が数値型になるように念のため除算で型変換）
   var is_automatic = {{$is_automatic}} - 0;

   // 自動の時に定期的に画面をリフレッシュするために設定
   // 値は、1/1000秒単位なので、60000は60秒
   // 手動の場合は、setTimeoutの最大値(約596時間、約24日)にすることで実質リフレッシュされない   
   if (is_automatic > 0) {
      var interval = 60000; // 60000は60秒
   }else{
      var interval = 2147483647; // 最大値
   }
   
   // ▼上記のグラフを描画するための記述
   window.onload = function(){
      var ctx = document.getElementById("graph-area1").getContext("2d");
      window.myPie = new Chart(ctx).Pie(pieData);

      setTimeout("location.reload()",interval);
   }
</script>

@endsection('script')

