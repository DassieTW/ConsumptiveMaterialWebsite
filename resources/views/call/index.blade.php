@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
@endsection
@section('content')
{!! __('callpageLang.callsys') !!}
<hr />

<?php
    $username = Session::get('username');
    echo __('templateWords.nowuser') .' '. $username;
?>
<br>
<br>
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('call.safe')}}'">{!! __('callpageLang.safealert') !!}</button>
&nbsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('call.day')}}'">{!! __('callpageLang.dayalert') !!}</button>
&nbsp;
@endsection
