@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<!-- <script src="{{ asset('js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
@endsection
@section('content')
{!! __('templateWords.obound') !!}
<hr />

<?php
    $username = Session::get('username');
    echo __('templateWords.nowuser') .' '. $username;
?>
<br>
<br>
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.new')}}'">{!! __('oboundpageLang.newMats') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.material')}}'">{!! __('oboundpageLang.matsInfo') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.inbound')}}'">{!! __('oboundpageLang.inbound') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.inboundsearch')}}'">{!! __('oboundpageLang.inboundsearch') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.upload')}}'">{!! __('oboundpageLang.stockupload') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.searchstock')}}'">{!! __('oboundpageLang.searchstock') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.pick')}}'">{!! __('oboundpageLang.pick') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.picklistpage')}}'">{!! __('oboundpageLang.picklist') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.pickrecord')}}'">{!! __('oboundpageLang.pickrecord') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.back')}}'">{!! __('oboundpageLang.back') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.backlistpage')}}'">{!! __('oboundpageLang.backlist') !!}</button>
&emsp;
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.backrecord')}}'">{!! __('oboundpageLang.backrecord') !!}</button>
@endsection
