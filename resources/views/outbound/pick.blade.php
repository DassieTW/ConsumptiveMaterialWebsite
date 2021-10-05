@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/outbound/pick.js') }}"></script>
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
                <h3>{!! __('outboundpageLang.pick') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "pick" >
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

                            <label class="form-label">{!! __('outboundpageLang.usereason') !!}</label>
                            <select class="form-control form-control-lg " id = "usereason" name="usereason" required>
                            <option style="display: none" disabled selected value = "">{!! __('outboundpageLang.enterusereason') !!}</option>
                            @foreach($usereason as $usereason)
                            <option>{{  $usereason->領用原因 }}</option>
                            @endforeach
                            <option>{!! __('outboundpageLang.other') !!}</option>
                            </select>
                            <br>
                            <input style="display:none;" class="form-control form-control-lg " type="text" id ="reason" name="reason" placeholder="{!! __('outboundpageLang.inputusereason') !!}">

                            <label class="form-label">{!! __('outboundpageLang.isn') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="number" name="number" required>
                            <div id = "numbererror" style="display:none; color:red;">{!! __('outboundpageLang.isnlength') !!}</div>
                            <div id = "numbererror1" style="display:none; color:red;">{!! __('outboundpageLang.noisn') !!}</div>

                            <div id = "nostock" style="display:none; color:red;">{!! __('outboundpageLang.nostock') !!}</div>
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
