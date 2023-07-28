<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>可能性検討(試作版) v1.2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="/css/vendor/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js" type="text/javascript"></script>


</head>

<body>
<div>
<ul>
    <li style="display:inline;">
      <a href="test">■KADECの現在値から消費量を推測</a>
    </li>
    <li style="display:inline;">
      <a href="poss">■過去の投入値と推測値の比較</a>
    </li>
    <li style="display:inline;">
      <a href="pred">■未来24時間の予測値</a>
    </li>
    <li style="display:inline;">
      <a href="irate">■仮説注入機の制御</a>
    </li>
<ul>
</div>
@yield('content')
</body>
@yield('script')

</html>