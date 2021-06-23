@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('/admin/js/app.js') }}"></script>
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
        Search ok!
        <br>
        <h3>儲存位置: , {{ $position }}</h3>
        <h3>料號: , {{ $material }}</h3>
        <h3>現有庫存: , {{ $stock }}</h3>
    </body>
    <br>
    <a type="submit" class="btn btn-lg btn-primary"  href = "{{ route('basic.searchposition') }}">返回</a>
    <br>
</html>
@endsection
