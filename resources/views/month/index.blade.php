@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
@endsection
@section('content')
{!! __('templateWords.monthly') !!}
<hr />

<?php
    $username = Session::get('username');
    echo __('templateWords.nowuser') .' '. $username;
?>
<br>
<br>
<div class="row">
<div class="mb-4 col-md-2">
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('month.consumeadd')}}'">{!! __('monthlyPRpageLang.isnConsumeAdd') !!}</button>
</div>
<div class="mb-4 col-md-2">
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('month.consume')}}'">{!! __('monthlyPRpageLang.isnConsumeUpdate') !!}</button>
</div>
<div class="mb-4 col-md-2">
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('month.standadd')}}'">{!! __('monthlyPRpageLang.standAdd') !!}</button>
</div>
<div class="mb-4 col-md-2">
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('month.stand')}}'">{!! __('monthlyPRpageLang.standUpdate') !!}</button>
</div>
<div class="mb-4 col-md-2">
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('month.importnotmonth')}}'">{!! __('monthlyPRpageLang.importNonMonthlyData') !!}</button>
</div>
<div class="mb-4 col-md-2">
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('month.importmonth')}}'">{!! __('monthlyPRpageLang.importMonthlyData') !!}</button>
</div>
<div class="mb-4 col-md-2">
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('month.buylist')}}'">{!! __('monthlyPRpageLang.PR') !!}</button>
</div>
<div class="mb-4 col-md-2">
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('month.srm')}}'">{!! __('monthlyPRpageLang.SRM') !!}</button>
</div>
<div class="mb-4 col-md-2">
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('month.sxb')}}'">{!! __('monthlyPRpageLang.SXB_search') !!}</button>
</div>
<div class="mb-4 col-md-2">
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('month.transit')}}'">{!! __('monthlyPRpageLang.on_the_way_search') !!}</button>
</div>
</div>
@endsection
