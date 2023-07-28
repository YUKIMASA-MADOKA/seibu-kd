@extends('layouts.app')
@section('content')

   <h1>仮設注入機の制御（手動）</h1>
   <h5>※現在制作中：仮設注入機に連携する値を入力（連携はWebAPI）。</h5>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

   <h3>■注入率の設定と現在の設定値</h3>
   <table>
      <thead>
         <tr><th>・注入率の設定</th><th>・現在の設定値</th></tr>
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
                  <input type="number" name="irate" id="irate" min="0" max="100" step="5" value={{ $aaa }} />
               </div>
               5を単位に増減する0から100までの値で設定してください。
            <div><input type="submit" class="btn" value="この値で設定する"></div>
            </form>
         </td>

         <!-- 現在の設定率 -->
         <td>
            <canvas id="graph-area1" height="200" width="300"></canvas>
            <p style="font-size:large;text-align:center">{{$aaa}} %</p>
            </td>
         </tr>
      </tbody>
   </table>
   <h3>■（参考）連携WebAPIは次のような形式で</h3>
   http://hamakaze:8081/api/irate

@endsection('content')

@section('script')
<script type="text/javascript">
   var aaa = {{$aaa}};
   var bbb = {{$bbb}};
   // ▼グラフの中身
   var pieData = [
      {
         value: aaa,            // 値
         color:"royalblue",       // 色
         highlight: "steelblue",  // マウスが載った際の色
         label: "設定値"        // ラベル
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

   }
</script>
@endsection('script')

