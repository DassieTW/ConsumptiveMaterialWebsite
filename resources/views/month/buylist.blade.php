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
            <h3>{!! __('monthlyPRpageLang.PR') !!}</h3>
        </div>
        <div class="card-body">
            <form id="seaechForm" class="row d-flex w-100 m-0 p-0" action="{{ route('month.buylistmake') }}" method="POST">
                @csrf
                <div class="col col-9 m-0 p-0">
                    <div class="row justify-content-center">
                        <div class="w-100 d-flex container">
                            <div class="row w-100 justify-content-center mb-3">
                                <label class="col col-auto form-label m-0 p-0">{!! __('monthlyPRpageLang.rate') !!}</label>
                                <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-6">
                                    <select
                                        class="form-select form-select-lg col col-auto @error('money') is-invalid @enderror"
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
                                
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <label class="col col-auto form-label m-0 p-0">{!! __('monthlyPRpageLang.senddep') !!}</label>
                                <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <select class="form-select form-select-lg col col-auto" id="send" name="send">
                                        <option style="display: none" disabled selected>{!! __('monthlyPRpageLang.entersenddep') !!}</option>
                                        @foreach ($send as $send_dep)
                                            <option>{{ $send_dep->發料部門 }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col col-auto">
                                    <a href="http://eip.tw.pegatroncorp.com/ExchangeRate"
                                        target="_blank">{!! __('monthlyPRpageLang.exchangeratesearch') !!}</a>
                                </div>
                                <div class="w-100" style="height: 5ch;"></div><!-- </div>breaks cols to a new line-->

                                <div class="col col-auto">
                                    <input type="submit" id="make" name="make" class="btn btn-lg btn-primary"
                                        value="{!! __('monthlyPRpageLang.generatePR') !!}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-3 m-0 p-0">
                    <div class="row justify-content-center">
                        <label class="col col-auto form-label m-0 p-0">TWD</label>
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input class="form-control form-control-lg @error('twd') is-invalid @enderror"
                                    type="number" id="twd" name="twd" step="0.000001"
                                    oninput="if(value.length>8)value=value.slice(0,8)" min="0">
                                @error('twd')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label m-0 p-0">RMB</label>
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input class="form-control form-control-lg @error('rmb') is-invalid @enderror"
                                    type="number" id="rmb" name="rmb" step="0.000001"
                                    oninput="if(value.length>8)value=value.slice(0,8)" min="0">
                                @error('rmb')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label m-0 p-0">VND</label>
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input class="form-control form-control-lg @error('vnd') is-invalid @enderror"
                                    type="number" id="vnd" name="vnd" step="0.000001"
                                    oninput="if(value.length>8)value=value.slice(0,8)" min="0">
                                @error('vnd')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label m-0 p-0">USD</label>
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input class="form-control form-control-lg @error('usd') is-invalid @enderror"
                                    type="number" id="usd" name="usd" step="0.000001"
                                    oninput="if(value.length>8)value=value.slice(0,8)" min="0">
                                @error('usd')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label m-0 p-0">JPY</label>
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input class="form-control form-control-lg @error('jpy') is-invalid @enderror"
                                    type="number" id="jpy" name="jpy" step="0.000001"
                                    oninput="if(value.length>8)value=value.slice(0,8)" min="0">
                                @error('jpy')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label m-0 p-0">IDR</label>
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input class="form-control form-control-lg @error('idr') is-invalid @enderror"
                                    type="number" id="idr" name="idr" step="0.000001"
                                    oninput="if(value.length>8)value=value.slice(0,8)" min="0">
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
        </div>
    </div>
@endsection
