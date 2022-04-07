@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
@endsection
@section('content')

<hr />

<?php
use Carbon\Carbon;
    $username = session('username');
    $database = session('database');
    echo __('templateWords.nowuser') .' '. $username.'<br>'.$database;
?>

<div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

{{-- <div class="row">
    <div class="col col-auto">
        <a href="http://eip.tw.pegatroncorp.com/" target="_blank">{!!
            __('templateWords.taipei') !!}</a>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        <a href="http://eip.tw.pegatroncorp.com/DeptSiteMap.aspx" target="_blank">{!!
            __('templateWords.dep') !!}</a>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        <a href="http://project.eip.tw.pegatroncorp.com/default.aspx" target="_blank">{!!
            __('templateWords.project') !!}</a>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        <a href="http://eip.sh.pegatroncorp.com/" target="_blank">{!!
            __('templateWords.east') !!}</a>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        <a href="http://eip.sz.pegatroncorp.com/eip/" target="_blank">{!!
            __('templateWords.middle') !!}</a>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        <a href="http://eip.cq.pegatroncorp.com/Home.aspx" target="_blank">{!!
            __('templateWords.west') !!}</a>
    </div>
</div> --}}

@endsection
