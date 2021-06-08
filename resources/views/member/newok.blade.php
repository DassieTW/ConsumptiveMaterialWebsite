@extends('layouts.adminTemplate')

@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Success</title>
    </head>
    <body>
        new people ok!!
        <br>
        <form action="{{ route('member.login') }}" method="GET">
            <input type="submit" value="Login">
        </form>
    </body>
</html>
@endsection
