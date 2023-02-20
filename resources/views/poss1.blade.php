@extends('layouts.app')
@section('content')
<body>
<h1>太田川：次亜塩素酸ナトリウム注入：前塩注入量</h1>
<h5>※計算方法は検討中のため仮の計算式を使っています。</h5>

<!-- //* 検索機能ここから *// -->
<h3>■表示範囲（From To）の指定</h3>
  <div class="search">
        <form action="{{ route('index_poss') }}" method="GET">
            @csrf

            <div class="form-group">
            <div>
                    <label for="" style="display:inline;">開始日
                    <div  style="display:inline;">
                    @if(empty($date_from))
                    <input type="date" name="date_from" value="{{ $_startday }}">
                    @else
                    <input type="date" name="date_from" value="{{ $date_from }}">
                    @endif
                    </div>
                    </label>
                </div>
                <div>
                    <label for="" style="display:inline;">終了日
                    <div  style="display:inline;">
                        <input type="date" name="date_to" value="{{ $date_to }}">
                    </div>
                    <input type="submit" class="btn" value="検索">
                    </label>
                </div>

                <!--div>
                    <input type="submit" class="btn" value="検索">
                </div-->
            </div>
        </form>
    </div>
<!-- //* 検索機能ここまで *// ->

  <p>POSSデータの比較</p>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

   <h3>■注入量のログ（緑：投入量　青：計算値　橙：沈殿池入口残留塩素）</h3>
   <canvas id="graph-area1" height="450" width="1200"></canvas>

   <h3>■水温と気温のログ（青：太田川着水井の水温　黄：気象庁の水温　赤：KADECの気温）</h3>
   <canvas id="graph-area2" height="450" width="1200"></canvas>

   <h3>■日射量のログ（黄：気象庁　赤：KADEC日射量　青：KADECのUVB）</h3>
   <canvas id="graph-area3" height="450" width="1200"></canvas>

</body>
<script type="text/javascript">
   //ラベル
   var labels = @json($label);
   //緑ログ
   var green_log = @json($green_log);
   //青ログ
   var blue_log = @json($blue_log);
   //橙ログ
   var orange_log = @json($orange_log);

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
         },
         {
            label: "橙データ",
            fillColor : "rgba(215,215,0,0.2)", // 線から下端までを塗りつぶす色
            strokeColor : "rgba(215,215,0,1)", // 折れ線の色
            pointColor : "rgba(215,215,0,1)",  // ドットの塗りつぶし色
            pointStrokeColor : "white",        // ドットの枠線色
            pointHighlightFill : "yellow",     // マウスが載った際のドットの塗りつぶし色
            pointHighlightStroke : "orange",    // マウスが載った際のドットの枠線色
            data : orange_log       // 各点の値
         }
      ]
   }

   // 水温と気温のログ
   //ラベル
   var labels = @json($label);
   //橙ログ
   var xxxx_log = @json($blue2_log);
   var orange_log = @json($orange2_log);
   var red_log = @json($red2_log);

   // ▼グラフの中身
   var lineChartData2 = {
      labels : labels,
      datasets : [
         {
            label: "青データ",
            fillColor : "rgba(151,187,205,0.2)",
            strokeColor : "rgba(151,187,205,1)",
            pointColor : "rgba(151,187,205,1)",
            pointStrokeColor : "white",
            pointHighlightFill : "yellow",
            pointHighlightStroke : "blue",
            data : xxxx_log
         },
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

   // 日射量のログ
   //ラベル
   var labels = @json($label);
   //橙ログ
   var orange_log = @json($orange3_log);
   var red_log = @json($red3_log);
   var blue_log = @json($blue3_log);

   // ▼グラフの中身
   var lineChartData3 = {
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


   // ▼上記のグラフを描画するための記述
   window.onload = function(){
      var ctx = document.getElementById("graph-area1").getContext("2d");
      window.myLine = new Chart(ctx).Line(lineChartData);
      var ctx = document.getElementById("graph-area2").getContext("2d");
      window.myLine = new Chart(ctx).Line(lineChartData2);
      var ctx = document.getElementById("graph-area3").getContext("2d");
      window.myLine = new Chart(ctx).Line(lineChartData3);
   }

</script>

@endsection('content')

