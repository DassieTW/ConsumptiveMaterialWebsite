@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
@endsection
@section('content')
{!! __('templateWords.outbound') !!}
<hr />

<?php
    $username = Session::get('username');
    echo __('templateWords.nowuser') .' '. $username;
?>
<br>
<br>
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.pick')}}'">{!! __('outboundpageLang.pick') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.picklistpage')}}'">{!! __('outboundpageLang.picklist') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.pickrecord')}}'">{!! __('outboundpageLang.pickrecord') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.back')}}'">{!! __('outboundpageLang.back') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.backlistpage')}}'">{!! __('outboundpageLang.backlist') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.backrecord')}}'">{!! __('outboundpageLang.backrecord') !!}</button>
&emsp;
@endsection
