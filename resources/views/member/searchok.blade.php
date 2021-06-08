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
        <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" style="width:20px;height:20px;" >
        <br>
        <input type="text" id="fname" name="fname" value="{{ $number }}"><br><br>
    </body>
    <br><a type="submit" class="btn btn-lg btn-primary"  href = "{{ route('member.changenumber') }}">修改</a>
</html>
@endsection
