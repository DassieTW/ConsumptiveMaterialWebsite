@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
@endsection
@section('content')
{!! __('templateWords.inbound') !!}
<hr />

<?php
    $username = Session::get('username');
    echo __('templateWords.nowuser') .' '. $username;
?>
<br>
<br>
<a class="btn btn-lg btn-primary" href="{{asset('download/StockExample.xlsx')}}" download>{!! __('inboundpageLang.exampleExcel') !!}</a>
<br>
<br>
    <form method="post" enctype="multipart/form-data" action = "{{ route('inbound.uploadinventory') }}">
        @csrf
        <div class="col-6 col-sm-3">
            <label>{!! __('inboundpageLang.plz_upload') !!}</label>
            <input  class="form-control @error('select_file') is-invalid @enderror"  type="file" name="select_file" />
            @error('select_file')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <br>
            <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.upload') !!}">
        </div>
    </form>

<br>
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.index')}}'">{!! __('inboundpageLang.return') !!}</button>
@endsection
