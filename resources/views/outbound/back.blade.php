@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/outbound/back.js') }}"></script>
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
                <h3>{!! __('outboundpageLang.back') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "back" >
                    @csrf
                    <div class="d-flex w-100 h-100">
                        <div class="mb-3">
                            <label class="form-label">{!! __('outboundpageLang.client') !!}</label>
                            <select class="form-control form-control-lg" id = "client" name="client" required>
                            <option style="display: none" disabled selected value = "">{!! __('outboundpageLang.enterclient') !!}</option>
                            @foreach($client as $client)
                            <option>{{  $client->客戶 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('outboundpageLang.machine') !!}</label>
                            <select class="form-control form-control-lg" id = "machine" name="machine" required>
                            <option style="display: none" disabled selected value = "">{!! __('outboundpageLang.entermachine') !!}</option>
                            @foreach($machine as $machine)
                            <option>{{  $machine->機種 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('outboundpageLang.process') !!}</label>
                            <select class="form-control form-control-lg " id = "production" name="production" required>
                            <option style="display: none" disabled selected value = "">{!! __('outboundpageLang.enterprocess') !!}</option>
                            @foreach($production as $production)
                            <option>{{  $production->製程 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('outboundpageLang.line') !!}</label>
                            <select class="form-control form-control-lg " id = "line" name="line" required>
                            <option style="display: none" disabled selected value = "">{!! __('outboundpageLang.enterline') !!}</option>
                            @foreach($line as $line)
                            <option>{{  $line->線別 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('outboundpageLang.backreason') !!}</label>
                            <select class="form-control form-control-lg " id = "backreason" name="backreason" required>
                            <option style="display: none" disabled selected value = "">{!! __('outboundpageLang.enterbackreason') !!}</option>
                            @foreach($backreason as $backreason)
                            <option>{{  $backreason->退回原因 }}</option>
                            @endforeach
                            <option>其他</option>
                            </select>
                            <br>
                            <input class="form-control form-control-lg " type="text" id ="reason" name="reason" placeholder="{!! __('outboundpageLang.inputbackreason') !!}">


                            <label class="form-label">{!! __('outboundpageLang.isn') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="number" name="number" required>
                            <div id = "numbererror">{!! __('outboundpageLang.isnlength') !!}</div>
                            <div id = "numbererror1">{!! __('outboundpageLang.noisn') !!}</div>
                        </div>
                    </div>
                    <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('outboundpageLang.submit') !!}">
                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.index')}}'">{!! __('outboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
