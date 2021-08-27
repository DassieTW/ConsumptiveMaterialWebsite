@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('templateWords.outbound') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('outboundpageLang.backrecord') !!}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form action="{{ route('outbound.backrecordsearch') }}" method="POST">
                            @csrf
                            <div class="d-flex w-100 h-100">
                                <div class="mb-3">
                                    <label class="form-label">{!! __('outboundpageLang.client') !!}</label>
                                    <select class="form-control form-control-lg" id = "client" name="client">
                                    <option style="display: none" disabled selected>{!! __('outboundpageLang.enterclient') !!}</option>
                                    @foreach($client as $client)
                                    <option>{{  $client->客戶 }}</option>
                                    @endforeach
                                    </select>

                                    <label class="form-label">{!! __('outboundpageLang.process') !!}</label>
                                    <select class="form-control form-control-lg" id = "production" name="production">
                                    <option style="display: none" disabled selected>{!! __('outboundpageLang.enterprocess') !!}</option>
                                    @foreach($production as $production)
                                    <option>{{  $production->製程 }}</option>
                                    @endforeach
                                    </select>

                                    <label class="form-label">{!! __('outboundpageLang.isn') !!}</label>
                                    <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number">
                                    @error('number')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <label class="form-label">{!! __('outboundpageLang.senddep') !!}</label>
                                    <select class="form-control form-control-lg" id = "send" name="send">
                                    <option style="display: none" disabled selected>{!! __('outboundpageLang.entersenddep') !!}</option>
                                    <option>IE備品室</option>
                                    <option>ME備品室</option>
                                    <option>設備備品室</option>
                                    <option>備品室</option>
                                    </select>

                                    <br>
                                    <input class ="basic" type="checkbox" id="date" name="date" style="width:20px;height:20px;" >
                                    <label class="form-label">{!! __('outboundpageLang.begindate') !!}</label>
                                    <input type="date" id = "begin" name = "begin" value="<?php echo date('Y-m-d'); ?>" />

                                    <label class="form-label">{!! __('outboundpageLang.enddate') !!}</label>
                                    <input type="date" id = "end" name = "end" value="<?php echo date('Y-m-d'); ?>" />
                                </div>
                            </div>
                            <input type = "submit" id = "search" name = "search" class="btn btn-lg btn-primary" value="{!! __('outboundpageLang.search') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.index')}}'">{!! __('outboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
