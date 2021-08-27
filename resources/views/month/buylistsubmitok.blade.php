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
        請購單新增 Success!
        <br><br>
        <a type="submit" class="btn btn-lg btn-primary"  href = "{{ route('month.index') }}">月請購</a>
    </body>
</html>
@endsection
