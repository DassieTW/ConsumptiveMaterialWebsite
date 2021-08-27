@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<<<<<<< HEAD
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('/admin/js/app.js') }}"></script>
<!-- <script src="{{ asset('js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
@endsection
@section('content')
功能
=======
<!--for this page's sepcified js -->
@endsection
@section('content')

>>>>>>> 0827tony
<hr />

<?php
    $username = Session::get('username');
<<<<<<< HEAD
    echo "now login user name : " . $username;
?>
<br>
<br>
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('member.change')}}'">更改密碼</button>
<br>
<br>
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('member.new')}}'">新增人員</button>
<br>
<br>
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('member.search')}}'">工號查詢</button>
<br>
<br>
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.index')}}'">基礎信息</button>
<br>
<br>
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('member.logout')}}'">Logout</button>

=======
    echo __('templateWords.nowuser') .' '. $username;
?>
>>>>>>> 0827tony


@endsection
