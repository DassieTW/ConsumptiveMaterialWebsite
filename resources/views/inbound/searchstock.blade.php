@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/inbound/stock.js') }}"></script>
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
                <h3>{!! __('inboundpageLang.searchstock') !!}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form action="{{ route('inbound.searchstocksubmit') }}" method="POST">
                            @csrf
                            <div class="d-flex w-100 h-100">
                                <div class="mb-3">
                                    <label class="form-label">{!! __('inboundpageLang.client') !!}</label>
                                    <select class="form-control form-control-lg" id = "client" name="client">
                                    <option style="display: none" disabled selected>{!! __('inboundpageLang.enterclient') !!}</option>
                                    @foreach($client as $client)
                                    <option>{{  $client->客戶 }}</option>
                                    @endforeach
                                    </select>

                                    <label class="form-label">{!! __('inboundpageLang.loc') !!}</label>
                                    <select class="form-control form-control-lg" id = "position" name="position">
                                    <option style="display: none" disabled selected>{!! __('inboundpageLang.enterloc') !!}</option>
                                    @foreach($position as $position)
                                    <option>{{  $position->儲存位置 }}</option>
                                    @endforeach
                                    </select>

                                    <label class="form-label">{!! __('inboundpageLang.isn') !!}</label>
                                    <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number" >
                                    @error('number')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <label class="form-label">{!! __('inboundpageLang.senddep') !!}</label>
                                    <select class="form-control form-control-lg" id = "send" name="send">
                                    <option style="display: none" disabled selected>{!! __('inboundpageLang.entersenddep') !!}</option>
                                    <option>IE備品室</option>
                                    <option>ME備品室</option>
                                    <option>設備備品室</option>
                                    <option>備品室</option>
                                    </select>

                                    <br>
                                    <input class ="basic" type="checkbox" id="month" name="month" style="width:20px;height:20px;" value="1">
                                    <label class="form-label">{!! __('inboundpageLang.stockmonth') !!}</label>
                                    <br>
                                    <input class ="basic" type="checkbox" id="nogood" name="nogood" style="width:20px;height:20px;" value="2">
                                    <label class="form-label">{!! __('inboundpageLang.nogood') !!}</label>

                                </div>
                            </div>
                            <input type = "submit" id = "search" name = "search" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.search') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.index')}}'">{!! __('inboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
