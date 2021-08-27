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
        刪除 Success!
        <br><br>
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.index')}}'">返回</button>
    </body>
</html>
@endsection
