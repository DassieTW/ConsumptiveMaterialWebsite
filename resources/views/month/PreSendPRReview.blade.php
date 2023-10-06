@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
    </div>
    <div class="card row w-100 justify-content-center">
        <div class="card-header">
            <h3>{!! __('monthlyPRpageLang.SendPRReview') !!}</h3>
        </div>
        <div class="card-body">
            <form id="seaechForm" class="row w-100 m-0 p-0 justify-content-center align-items-center"
                action="{{ route('month.SendPRReview') }}" method="POST">
                @csrf
                <label class="col col-auto form-label">{!! __('monthlyPRpageLang.rate') !!}</label>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="input-group w-75">
                    <label class="input-group-text">1</label>
                    <select class="form-select form-select-lg text-center @error('money') is-invalid @enderror"
                        id="money" name="money">
                        <option selected>RMB</option>
                        <option>USD</option>
                        <option>JPY</option>
                        <option>TWD</option>
                        <option>VND</option>
                        <option>IDR</option>
                    </select>
                    @error('money')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{!! __('monthlyPRpageLang.entermoney') !!}</strong>
                        </span>
                    @enderror
                    <label class="input-group-text">to</label>
                    <input
                        class="form-control form-control-lg text-center @error('rate_input_missing') is-invalid @enderror"
                        type="number" id="rate_to" name="rate_to" step="0.000001"
                        oninput="if(value.length>8)value=value.slice(0,8)" min="0">
                    @error('rate_input_missing')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <select class="col col-2 form-select form-select-lg text-center @error('money') is-invalid @enderror"
                        id="money" name="money">
                        <option style="display: none" disabled selected value="">
                            {!! __('monthlyPRpageLang.entermoney') !!}</option>
                        <option>RMB</option>
                        <option>USD</option>
                        <option>JPY</option>
                        <option>TWD</option>
                        <option>VND</option>
                        <option>IDR</option>
                    </select>
                    @error('money')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{!! __('monthlyPRpageLang.entermoney') !!}</strong>
                        </span>
                    @enderror
                </div>
                <div class="w-100" style="height: 3ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="col col-auto">
                    <a href="http://eip.tw.pegatroncorp.com/ExchangeRate" target="_blank">{!! __('monthlyPRpageLang.exchangeratesearch') !!}</a>
                </div>

                <div class="w-100" style="height: 5ch;"></div><!-- </div>breaks cols to a new line-->

                <div class="col col-auto">
                    <input type="submit" id="make" name="make" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.generatePR') !!}">
                </div>
            </form>
        </div>
    </div>
@endsection
