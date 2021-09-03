@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/inbound/add.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('templateWords.inbound') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('inboundpageLang.new') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "add" >
                    @csrf
                    <div class="d-flex w-100 h-100">
                        <div class="mb-3">

                            <label class="form-label">{!! __('inboundpageLang.client') !!}</label>
                            <select class="form-control form-control-lg" id = "client" name="client" required>
                            <option style="display: none" disabled selected value = "">{!! __('inboundpageLang.enterclient') !!}</option>
                            @foreach($client as $client)
                            <option>{{  $client->客戶 }}</option>
                            @endforeach
                            </select>
                            <br>
                            <label class="form-label">{!! __('inboundpageLang.inreason') !!}</label>
                            <select class="form-control form-control-lg " id = "inreason" name="inreason" required>
                            <option style="display: none" disabled selected value = "">{!! __('inboundpageLang.enterinreason') !!}</option>
                            @foreach($inreason as $inreason)
                            <option>{{  $inreason->入庫原因 }}</option>
                            @endforeach
                            <option>{!! __('inboundpageLang.other') !!}</option>
                            </select>
                            <br>
                            <input class="form-control form-control-lg " style="display:none; color:red;" type="text" id ="reason" name="reason" placeholder="{!! __('inboundpageLang.inputinreason') !!}">


                            <label class="form-label">{!! __('inboundpageLang.isn') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="number" name="number">
                            <div id = "numbererror" style="display:none; color:red;">{!! __('inboundpageLang.isnlength') !!}</div>
                            <div id = "numbererror1" style="display:none; color:red;">{!! __('inboundpageLang.noisn') !!}</div>

                            <div id = "notransit" style="display:none; color:red;">{!! __('inboundpageLang.notransit') !!}</div>

                        </div>
                    </div>
                    <input type = "submit" onclick="buttonIndex=0;" id = "addto" name = "addto" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.add') !!}">
                    <br><br>
                    <input type = "submit" onclick="buttonIndex=1;" id = "addclient" name = "addclient" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.addclient') !!}">
                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.index')}}'">{!! __('inboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
