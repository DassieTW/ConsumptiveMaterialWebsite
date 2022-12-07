@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
@endsection
@section('content')
    {!! __('templateWords.inbound') !!}
    <hr />

    <?php
    $username = Session::get('username');
    echo __('templateWords.nowuser') . ' ' . $username;
    ?>
    <br>
    <br>

    <div class="mb-4 col-md-4">
        <button type="submit" class="btn btn-lg btn-primary"
            onclick="location.href='{{ route('inbound.add') }}'">{!! __('inboundpageLang.new') !!}</button>
    </div>
    <div class="mb-4 col-md-4">
        <button type="submit" class="btn btn-lg btn-primary"
            onclick="location.href='{{ route('inbound.search') }}'">{!! __('inboundpageLang.search') !!}</button>
    </div>
    <div class="mb-4 col-md-4">
        <button type="submit" class="btn btn-lg btn-primary"
            onclick="location.href='{{ route('inbound.searchstock') }}'">{!! __('inboundpageLang.searchstock') !!}</button>
    </div>
    <div class="mb-4 col-md-4">
        <button type="submit" class="btn btn-lg btn-primary"
            onclick="location.href='{{ route('inbound.positionchange') }}'">{!! __('inboundpageLang.locationchange') !!}</button>
    </div>
    <div class="mb-4 col-md-4">
        <button type="submit" class="btn btn-lg btn-primary"
            onclick="location.href='{{ route('inbound.upload') }}'">{!! __('inboundpageLang.stockupload') !!}</button>
    </div>
@endsection
