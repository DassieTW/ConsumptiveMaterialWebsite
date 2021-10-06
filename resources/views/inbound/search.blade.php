@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/inbound/search.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('templateWords.inbound') !!}</h2>
        <div class="row justify-content-center">
            <div class="card w-75">
                <div class="card-header">
                <h3>{!! __('inboundpageLang.search') !!}</h3>
            </div>
            <div class="row justify-content-center">
                <div class="card-body">
                    <div class="d-flex w-100">
                        <form  action="{{ route('inbound.inquire') }}" method="POST">
                            @csrf
                            <div class="row w-100 justify-content-center mb-3">

                                    <label class="col col-auto form-label">{!! __('inboundpageLang.client') !!}</label>
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                    <select class="form-select form-select-lg" id = "client" name="client">
                                    <option style="display: none" disabled selected value = "">{!! __('inboundpageLang.enterclient') !!}</option>
                                    @foreach($client as $client)
                                    <option>{{  $client->客戶 }}</option>
                                    @endforeach
                                    </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                    <label class="col col-auto form-label">{!! __('inboundpageLang.isn') !!}</label>
                                    <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number">
                                    @error('number')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>

                                    <label class="form-label">{!! __('inboundpageLang.inlist') !!}</label>
                                    <input class="form-control form-control-lg @error('innumber') is-invalid @enderror" type="text" id ="innumber" name="innumber">
                                    @error('innumber')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror


                                    <br>
                                    <input class ="basic" type="checkbox" id="date" name="date" style="width:20px;height:20px;" >
                                    <label class="form-label">{!! __('inboundpageLang.begindate') !!}</label>
                                    <input type="date" id = "begin" name = "begin" value="<?php echo date('Y-m-d'); ?>" />

                                    <label class="form-label">{!! __('inboundpageLang.enddate') !!}</label>
                                    <input type="date" id = "end" name = "end" value="<?php echo date('Y-m-d'); ?>" />
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
