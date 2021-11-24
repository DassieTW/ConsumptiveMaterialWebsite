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
<h2>{!! __('templateWords.monthly') !!}</h2>
<div class="row justify-content-center">
    <div class="card w-50">
        <div class="card-header">
            <h3>{!! __('monthlyPRpageLang.PR') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <div class="d-flex w-100">
                    <form action="{{ route('month.buylistmake') }}" method="POST">
                        @csrf
                        <div class="row w-100 justify-content-center mb-3">
                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.client') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg col col-auto" id="client" name="client"
                                    required>
                                    <option style="display: none" disabled selected value="">{!!
                                        __('monthlyPRpageLang.enterclient') !!}</option>
                                    @foreach($client as $client)
                                    <option>{{ $client->客戶 }}</option>
                                    @endforeach
                                    <option>{!! __('monthlyPRpageLang.allclient') !!}</option>
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.money') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg col col-auto" id="money" name="money"
                                    required>
                                    <option style="display: none" disabled selected value="">{!!
                                        __('monthlyPRpageLang.entermoney') !!}</option>
                                    <option>RMB</option>
                                    <option>USD</option>
                                    <option>JPY</option>
                                    <option>TWD</option>
                                    <option>VND</option>
                                    <option>IDR</option>
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.senddep') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg col col-auto" id="send" name="send">
                                    <option style="display: none" disabled selected>{!!
                                        __('monthlyPRpageLang.entersenddep') !!}</option>
                                    @foreach($send as $send)
                                    <option>{{ $send->發料部門 }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <a href="http://eip.tw.pegatroncorp.com/ExchangeRate.aspx" target="_blank">{!!
                                    __('monthlyPRpageLang.exchangeratesearch') !!}</a>
                            </div>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input type="submit" id="make" name="make" class="btn btn-lg btn-primary"
                                    value="{!! __('monthlyPRpageLang.generatePR') !!}">
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
    <div class="card w-25">
        <div class="row justify-content-center">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <label class="col col-auto form-label">TWD</label>
            <div class="row w-100 justify-content-center">
                <div class="col col-auto">
                    <input class="form-control form-control-lg @error('twd') is-invalid @enderror" type="number"
                        id="twd" name="twd" step = "0.000001" oninput="if(value.length>8)value=value.slice(0,8)">
                    @error('twd')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <label class="col col-auto form-label">RMB</label>
            <div class="row w-100 justify-content-center">
                <div class="col col-auto">
                    <input class="form-control form-control-lg @error('rmb') is-invalid @enderror" type="number"
                        id="rmb" name="rmb" step = "0.000001" oninput="if(value.length>8)value=value.slice(0,8)">
                    @error('rmb')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <label class="col col-auto form-label">VND</label>
            <div class="row w-100 justify-content-center">
                <div class="col col-auto">
                    <input class="form-control form-control-lg @error('vnd') is-invalid @enderror" type="number"
                        id="vnd" name="vnd" step = "0.000001" oninput="if(value.length>8)value=value.slice(0,8)">
                    @error('vnd')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <label class="col col-auto form-label">USD</label>
            <div class="row w-100 justify-content-center">
                <div class="col col-auto">
                    <input class="form-control form-control-lg @error('usd') is-invalid @enderror" type="number"
                        id="usd" name="usd" step = "0.000001" oninput="if(value.length>8)value=value.slice(0,8)">
                    @error('usd')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <label class="col col-auto form-label">JPY</label>
            <div class="row w-100 justify-content-center">
                <div class="col col-auto">
                    <input class="form-control form-control-lg @error('jpy') is-invalid @enderror" type="number"
                        id="jpy" name="jpy" step = "0.000001" oninput="if(value.length>8)value=value.slice(0,8)">
                    @error('jpy')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <label class="col col-auto form-label">IDR</label>
            <div class="row w-100 justify-content-center">
                <div class="col col-auto">
                    <input class="form-control form-control-lg @error('idr') is-invalid @enderror" type="number"
                        id="idr" name="idr" step = "0.000001" oninput="if(value.length>8)value=value.slice(0,8)">
                    @error('idr')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        </div>
    </div>
    </form>

</html>
@endsection
