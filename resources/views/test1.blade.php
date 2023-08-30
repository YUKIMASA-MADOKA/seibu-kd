@extends('layouts.app')
@section('content')

   <h1>気象観測データ(KADEC21)の現在値等から塩素消費量を推定</h1>
   <h5>※計算方法は検討中のため仮の計算式を使っています。</h5>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

   <h3>■現在値・推定消費量・天気予報</h3>
   <table>
   <tr>
   @if($elapsed_time > 120)
      <th>KADEC測定値<span style="color:#FF0000;">　通信切れの可能性あり</span></th><th>塩素消費量（推定指標）</th><th>天気予報</th>
   @else
      <th>KADEC測定値</th><th>塩素消費量（推定指標）</th><th>天気予報</th>
   @endif
   </tr>
   <tr>
   <td>
   <div>
   <p><dt style="display:inline;">時刻：</dt><dd style="display:inline; ">{{$day}} {{$hms}} 現在 ({{$elapsed_time}}分経過)</dd></p>
   <p><dt style="display:inline;">UVB：</dt><dd style="display:inline;"><meter value={{$uvb}} high={{$high_uvb}} min={{$min_uvb}} max={{$max_uvb}}></meter> {{($uvb)}}　（min{{$min_uvb}}　max{{$max_uvb}}）</dd></p>
   <p><dt style="display:inline;">日照：</dt><dd style="display:inline;"><meter value={{$insolation}} high={{$high_insolation}} min={{$min_insolation}} max={{$max_insolation}}></meter> {{$insolation}}　（min{{$min_insolation}}　max{{$max_insolation}}）</dd></p>
   <p><dt style="display:inline;">気温：</dt><dd style="display:inline;"><meter value={{$temperature}} high={{$high_temperature}} min={{$min_temperature}} max={{$max_temperature}}></meter> {{$temperature}}°　（min{{$min_temperature}}　max{{$max_temperature}}）</dd></p>
   <p><dt style="display:inline;">湿度：</dt><dd style="display:inline;"><meter value={{$humidity}} high={{$high_humidity}} min={{$min_humidity}} max={{$max_humidity}}></meter> {{$humidity}}％　（min{{$min_humidity}}　max{{$max_humidity}}）</dd></p>
   <p><dt style="display:inline;">風速：</dt><dd style="display:inline;"><meter value={{$windspeed}} high={{$high_windspeed}} min={{$min_windspeed}} max={{$max_windspeed}}></meter> {{$windspeed}}　（min{{$min_windspeed}}　max{{$max_windspeed}}）</dd></p>
   </div>
   </td>

   <td>
   <canvas id="graph-area1" height="200" width="300"></canvas>
   <p style="font-size:large;text-align:center">{{$aaa_val}}</p>
   <p style="font-size:large;text-align:center">({{$aaa}})</p>
   </td>

   <td>
   <div id="ww_47bedf087800e" v='1.3' loc='id' a='{"t":"horizontal","lang":"ja","sl_lpl":1,"ids":["wl6951"],"font":"Arial","sl_ics":"one_a","sl_sot":"celsius","cl_bkg":"image","cl_font":"#FFFFFF","cl_cloud":"#FFFFFF","cl_persp":"#81D4FA","cl_sun":"#FFC107","cl_moon":"#FFC107","cl_thund":"#FF5722"}'>Weather for the Following Location: <a href="https://2ua.org/jpn/iwata/map/" id="ww_47bedf087800e_u" target="_blank">Iwata map, Japan</a></div><script async src="https://app1.weatherwidget.org/js/?id=ww_47bedf087800e"></script>
   </td>

   </tr>
   </table>

   <h3>■ログ（緑：UVB　青：日射量）</h3>
   <canvas id="graph-area2" height="450" width="1200"></canvas>

   <h3>■ログ（橙：気温　赤：湿度）</h3>
   <canvas id="graph-area3" height="200" width="1200"></canvas>
@endsection('content')

@section('script')
<script type="text/javascript">
   //ラベル
   var labels = @json($label);
   //橙ログ
   var orange_log = @json($orange_log);
   //赤ログ
   var red_log = @json($red_log);

   // ▼グラフの中身
   var lineChartData2 = {
      labels : labels,
      datasets : [
         {
            label: "橙データ",
            fillColor : "rgba(215,215,0,0.2)", // 線から下端までを塗りつぶす色
            strokeColor : "rgba(215,215,0,1)", // 折れ線の色
            pointColor : "rgba(215,215,0,1)",  // ドットの塗りつぶし色
            pointStrokeColor : "white",        // ドットの枠線色
            pointHighlightFill : "yellow",     // マウスが載った際のドットの塗りつぶし色
            pointHighlightStroke : "orange",    // マウスが載った際のドットの枠線色
            data : orange_log       // 各点の値
         },
         {
            label: "赤データ",
            fillColor : "rgba(255,160,122,0.2)",
            strokeColor : "rgba(255,160,122,1)",
            pointColor : "rgba(255,160,122,1)",
            pointStrokeColor : "white",
            pointHighlightFill : "yellow",
            pointHighlightStroke : "red",
            data : red_log
         }
      ]

   }

   //ラベル
   var labels = @json($label);
   //緑ログ
   var green_log = @json($green_log);
   //青ログ
   var blue_log = @json($blue_log);

   // ▼グラフの中身
   var lineChartData = {
      labels : labels,
      datasets : [
         {
            label: "緑データ",
            fillColor : "rgba(92,220,92,0.2)", // 線から下端までを塗りつぶす色
            strokeColor : "rgba(92,220,92,1)", // 折れ線の色
            pointColor : "rgba(92,220,92,1)",  // ドットの塗りつぶし色
            pointStrokeColor : "white",        // ドットの枠線色
            pointHighlightFill : "yellow",     // マウスが載った際のドットの塗りつぶし色
            pointHighlightStroke : "green",    // マウスが載った際のドットの枠線色
            data : green_log       // 各点の値
         },
         {
            label: "青データ",
            fillColor : "rgba(151,187,205,0.2)",
            strokeColor : "rgba(151,187,205,1)",
            pointColor : "rgba(151,187,205,1)",
            pointStrokeColor : "white",
            pointHighlightFill : "yellow",
            pointHighlightStroke : "blue",
            data : blue_log
         }
      ]

   }

   var aaa = {{$aaa}};
   var bbb = {{$bbb}};
   // ▼グラフの中身
   var pieData = [
      {
         value: aaa,            // 値
         color:"royalblue",       // 色
         highlight: "steelblue",  // マウスが載った際の色
         label: "計算値"        // ラベル
      },
      {
         value: bbb,
         color: "gray",
      },

   ];

   // ▼上記のグラフを描画するための記述
   window.onload = function(){
      var ctx = document.getElementById("graph-area1").getContext("2d");
      window.myPie = new Chart(ctx).Pie(pieData);

      var ctx = document.getElementById("graph-area2").getContext("2d");
      window.myLine = new Chart(ctx).Line(lineChartData);
      var ctx = document.getElementById("graph-area3").getContext("2d");
      window.myLine = new Chart(ctx).Line(lineChartData2);
   }

</script>
<script>
// 現在表示されているページをリロードする
//location.reload();
// 例: 60秒に一回リロード
// 実際には10分に1回でかつ再読み込みアイコンを配置したほうがいい
//setTimeout("location.reload()",60000);
// 上の行wのコメントを外せばリロードされる
// ※日付指定にした場合は、

</script>
@endsection('script')

