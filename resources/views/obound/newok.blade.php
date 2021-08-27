@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!-- <script src="{{ asset('js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        New material ok!!
        <br><br>
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.index')}}'">O庫</button>
    </body>
</html>
@endsection
