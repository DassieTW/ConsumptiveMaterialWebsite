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
<hr/>

<?php
    $username = Session::get('username');
    echo __('templateWords.nowuser') .' '. $username;
?>
<br>
<br>
<a class="btn btn-lg btn-primary" href="{{asset('download/OboundStockExample.xlsx')}}" download>{!! __('oboundpageLang.exampleExcel') !!}</a>
<br>
<br>
    <form method="post" enctype="multipart/form-data" action = "{{ route('obound.uploadinventory') }}">
        @csrf
        <div class="col-6 col-sm-3">
            <label>{!! __('oboundpageLang.plz_upload') !!}</label>
            <input  class="form-control @error('select_file') is-invalid @enderror"  type="file" name="select_file" />
            @error('select_file')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
            <br>
            <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.upload') !!}">
        </div>
    </form>

<br>
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.index')}}'">{!! __('oboundpageLang.return') !!}</button>
@endsection
