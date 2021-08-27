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
    echo "now login user name : " . $username;
?>
<br>
<br>
<a class="btn btn-lg btn-primary" href="{{asset('download/StockExample.xlsx')}}" download>{!! __('inboundpageLang.exampleExcel') !!}</a>
<br>
<br>
    @if(count($errors) > 0)
    <div class="alert alert-danger">
        Upload Validation Error<br><br>
        <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif

    @if($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
    </div>
    @endif
    <form method="post" enctype="multipart/form-data" action = "{{ route('inbound.uploadinventory') }}">
        @csrf
        <div class="col-6 col-sm-3">
            <label>{!! __('inboundpageLang.plz_upload') !!}</label>
            <input  class="form-control"  type="file" name="select_file" />
            <br>
            <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.upload') !!}">
        </div>
    </form>

<br>
<button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.index')}}'">{!! __('inboundpageLang.return') !!}</button>
@endsection
