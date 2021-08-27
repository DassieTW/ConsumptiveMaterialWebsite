@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        上傳 ok!!
        <br><br>
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('month.index')}}'">月請購</button>
    </body>
</html>
@endsection
