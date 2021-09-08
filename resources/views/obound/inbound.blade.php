@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<script src="{{ asset('js/obound/inbound.js') }}"></script>
<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('templateWords.obound') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('oboundpageLang.inbound') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "test" >
                    @csrf
                    <div class="d-flex w-100 h-100">
                        <div class="mb-3">

                            <label class="form-label">{!! __('oboundpageLang.client') !!}</label>
                            <select class="form-control form-control-lg" id = "client" name="client" required>
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterclient') !!}</option>
                            @foreach($client as $client)
                            <option>{{  $client->客戶 }}</option>
                            @endforeach
                            </select>
                            <br>
                            <label class="form-label">{!! __('oboundpageLang.inreason') !!}</label>
                            <select class="form-control form-control-lg " id = "inreason" name="inreason" required>
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterinreason') !!}</option>
                            @foreach($inreason as $inreason)
                            <option>{{  $inreason->入庫原因 }}</option>
                            @endforeach
                            </select>
                            <br>
                            <label class="form-label">{!! __('oboundpageLang.oisn') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="number" name="number" required>
                            <div id = "numbererror" style="display:none; color:red;">{!! __('oboundpageLang.noisn') !!}</div>
                        </div>
                    </div>
                    <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.add') !!}">

                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.index')}}'">{!! __('oboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
