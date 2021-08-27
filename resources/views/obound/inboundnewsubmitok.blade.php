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

            @if($opentime ?? '' !== null)

               <script>alert('添加成功，入庫單號 : ' + {{$opentime ?? ''}};)</script>
            @endif
        入庫新增 Success!
        <br><br>
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.index')}}'">返回</button>
    </body>
</html>
@endsection
