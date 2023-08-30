@extends('layouts.app')
@section('content')

   <h1>職員設定値の入力</h1>
   <h5>※AI算出の注入率と比較表示するための職員設定値を入力してください。</h5>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

   <h3>■設定日を入力（日ごとに設定）</h3>
   <form action="{{ route('index_craft') }}" method="GET">
      @csrf
      <div style="display:inline;">
         <input type="date" name="date_from" value="{{ $startday }}">
         <input type="submit" class="btn" value="検索">
      </div>
   </form>
   <p></p>
   <h3>■注入率の設定</h3>
   <form action="{{ route('update_craft') }}" method="POST">
      @csrf
      <input type="hidden" name="idarray" id="idarray" value={{ $idarray }}/>
      
      <div style="display:inline;">0.05を単位に増減する0.00から2.50までの値で注入率を設定してください。<input type="submit" class="btn" value="この値で設定する"></div>
      <table class="table">
      <thead>
         <tr>
            <!--th></th-->
            <th>年月日 時刻</th>
            <th>注入率</th>
         </tr>
      </thead>
      <tbody>
      @foreach( $crafts as $craft )
      <tr >
         <!--td>{{ $craft->id }}<input type="hidden" name="id" id="id" value={{ $craft->id }} /></td-->
         <td>{{ $craft->day }} {{ $craft->hms }}<input type="hidden" name="day" id="day" value={{ $craft->day }} />
         <input type="hidden" name="hms" id="hms" value={{ $craft->hms }} /></td>
         <td><input type="number" name="rate{{ $craft->id }}" id="rate{{ $craft->id }}" style="text-align: right" min="0.00" max="2.50" step="0.05" value={{ $craft->injection_rate }} /></td>
      </tr>
      @endforeach
      </tbody>
      </table>
      <div style="display:inline;">0.05を単位に増減する0.00から2.50までの値で注入率を設定してください。<input type="submit" class="btn" value="この値で設定する"></div>

   </form>

@endsection('content')

@section('script')
<script type="text/javascript">
   // ▼上記のグラフを描画するための記述
</script>
@endsection('script')

