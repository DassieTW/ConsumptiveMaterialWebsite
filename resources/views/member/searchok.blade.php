@extends('layouts.adminTemplate')

@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Success</title>
    </head>
    <body>
        Search ok!
        <br>
        <h3>工號: , {{ $number }}</h3>
        <h3>姓名: , {{ $name }}</h3>
        <h3>部門: , {{ $department }}</h3>
    </body>
    <br><a type="submit" class="btn btn-lg btn-primary"  href = "{{ route('member.changenumber') }}">修改</a>
</html>
@endsection
