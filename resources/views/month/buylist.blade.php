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
        <div class="card">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.PR') !!}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form action="{{ route('month.buylistmake') }}" method="POST">
                            @csrf
                            <div class="d-flex w-100 h-100">
                                <div class="mb-3">
                                    <label class="form-label">{!! __('monthlyPRpageLang.client') !!}</label>
                                    <select class="form-control form-control-lg" id = "client" name="client" required>
                                    <option style="display: none" disabled selected value = "">{!! __('monthlyPRpageLang.enterclient') !!}</option>
                                    @foreach($client as $client)
                                    <option>{{  $client->客戶 }}</option>
                                    @endforeach
                                    <option>所有客戶</option>
                                    </select>
                                    <label class="form-label">{!! __('monthlyPRpageLang.money') !!}</label>
                                    <select class="form-control form-control-lg" id = "money" name="money" required>
                                    <option style="display: none" disabled selected value = "">{!! __('monthlyPRpageLang.entermoney') !!}</option>
                                    <option>RMB</option>
                                    <option>USD</option>
                                    <option>JPY</option>
                                    <option>TWD</option>
                                    <option>VND</option>
                                    </select>

                                    <label class="form-label">{!! __('monthlyPRpageLang.senddep') !!}</label>
                                    <select class="form-control form-control-lg" id = "send" name="send">
                                    <option style="display: none" disabled selected>{!! __('monthlyPRpageLang.entersenddep') !!}</option>
                                    <option>IE備品室</option>
                                    <option>ME備品室</option>
                                    <option>設備備品室</option>
                                    <option>備品室</option>
                                    </select>


                                </div>
                                &emsp;&emsp;
                                <div class="mb-3">
                                    <label class="form-label">TWD</label>
                                    <input class="form-control form-control-lg @error('twd') is-invalid @enderror" type="number" id ="twd" name="twd">
                                    @error('twd')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <label class="form-label">RMB</label>
                                    <input class="form-control form-control-lg @error('rmb') is-invalid @enderror" type="number" id ="rmb" name="rmb">
                                    @error('rmb')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <label class="form-label">VND</label>
                                    <input class="form-control form-control-lg @error('vnd') is-invalid @enderror" type="number" id ="vnd" name="vnd">
                                    @error('vnd')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <label class="form-label">USD</label>
                                    <input class="form-control form-control-lg @error('usd') is-invalid @enderror" type="number" id ="usd" name="usd">
                                    @error('usd')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <label class="form-label">JPY</label>
                                    <input class="form-control form-control-lg @error('jpy') is-invalid @enderror" type="number" id ="jpy" name="jpy">
                                    @error('jpy')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <a href="http://eip.tw.pegatroncorp.com/ExchangeRate.aspx" target="_blank">{!! __('monthlyPRpageLang.exchangeratesearch') !!}</a>
                            <br><br>
                            <input type = "submit" id = "make" name = "make" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.generatePR') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.index')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
