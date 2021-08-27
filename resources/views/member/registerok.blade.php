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
        Sign up ok!!
        <br>
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('member.login')}}'">Login</button>
    </body>
</html>
@endsection
