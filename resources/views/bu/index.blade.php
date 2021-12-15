@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->

@endsection
@section('content')
{!! __('bupagelang.bu') !!}
<hr />

<?php
    $username = Session::get('username');
    echo __('templateWords.nowuser') .' '. $username;
?>
<br>
<br>
<div class="mb-4 col-md-4">
    <button type="submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.sluggish')}}'">{!!
        __('bupagelang.sluggish') !!}</button>
</div>
<div class="mb-4 col-md-4">
    <button type="submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.material')}}'">{!!
        __('bupagelang.factorychange') !!}</button>
</div>
<div class="mb-4 col-md-4">
    <button type="submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.searchlist')}}'">{!!
        __('bupagelang.searchlist') !!}</button>
</div>
<div class="mb-4 col-md-4">
    <button type="submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.outlistpage')}}'">{!!
        __('bupagelang.outlist') !!}</button>
</div>
<div class="mb-4 col-md-4">
    <button type="submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.picklistpage')}}'">{!!
        __('bupagelang.picklist') !!}</button>
</div>
<div class="mb-4 col-md-4">
    <button type="submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.searchdetail')}}'">{!!
        __('bupagelang.searchdetail') !!}</button>
</div>
@endsection