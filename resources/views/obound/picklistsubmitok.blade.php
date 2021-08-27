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
        O庫領料單添加 Success!
        <br><br>
        <a type="submit" class="btn btn-lg btn-primary"  href = "{{ route('obound.index') }}">O庫</a>
    </body>
</html>
@endsection
