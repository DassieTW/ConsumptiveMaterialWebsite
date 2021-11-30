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
<div class="row justify-content-center">
    <div class="card w-75">
        <div class="card-header">
            <h3>{!! __('outboundpageLang.back') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <form id="back">
                    @csrf
                    <div class="row w-100 justify-content-center mb-3">

                        <label class="col col-auto form-label">{!! __('outboundpageLang.client') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg" id="client" name="client" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('outboundpageLang.enterclient') !!}</option>
                                @foreach($client as $client)
                                <option>{{ $client->客戶 }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('outboundpageLang.machine') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg" id="machine" name="machine" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('outboundpageLang.entermachine') !!}</option>
                                @foreach($machine as $machine)
                                <option>{{ $machine->機種 }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('outboundpageLang.process') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg " id="production" name="production" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('outboundpageLang.enterprocess') !!}</option>
                                @foreach($production as $production)
                                <option>{{ $production->制程 }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('outboundpageLang.line') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg " id="line" name="line" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('outboundpageLang.enterline') !!}</option>
                                @foreach($line as $line)
                                <option>{{ $line->線別 }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('outboundpageLang.backreason') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg " id="backreason" name="backreason" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('outboundpageLang.enterbackreason') !!}</option>
                                @foreach($backreason as $backreason)
                                <option>{{ $backreason->退回原因 }}</option>
                                @endforeach
                                <option>{!! __('outboundpageLang.other') !!}</option>
                            </select>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <input style="display:none;" class="form-control form-control-lg " type="text" id="reason"
                                name="reason" placeholder="{!! __('outboundpageLang.inputbackreason') !!}">
                        </div>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('outboundpageLang.isn') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">

                            <input class="form-control form-control-lg " type="text" id="number" name="number" required
                                placeholder="{!! __('outboundpageLang.enterisn') !!}">
                            <div id="numbererror" style="display:none; color:red;">{!! __('outboundpageLang.isnlength')
                                !!}
                            </div>
                            <div id="numbererror1" style="display:none; color:red;">{!! __('outboundpageLang.noisn') !!}
                            </div>
                        </div>
                    </div>

                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                                value="{!! __('outboundpageLang.submit') !!}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</html>
@endsection
