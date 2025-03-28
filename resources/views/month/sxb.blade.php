@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/month/sxb.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.SXB_search') !!}</h3>
            </div>
            <div class="row justify-content-center">
                <div class="card-body">
                    <div class="d-flex w-100">
                        <form id="form1" class="w-100" method="POST">
                            @csrf
                            <div class="row justify-content-center mb-3">
                                <label class="col col-auto form-label p-0 m-0">{!! __('monthlyPRpageLang.isn') !!}</label>
                                <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <input class="form-control form-control-lg @error('number') is-invalid @enderror"
                                        type="text" id="number" name="number" placeholder="{!! __('inboundpageLang.enterisn') !!}"
                                        >
                                    @error('number')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="w-100" style="height: 2ch;"></div><!-- </div>breaks cols to a new line-->

                                <label class="col col-auto form-label p-0 m-0">{!! __('monthlyPRpageLang.senddep') !!}</label>
                                <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <select class="form-select form-select-lg" id="send" name="send">
                                        <option style="display: none" disabled selected>{!! __('monthlyPRpageLang.entersenddep') !!}</option>
                                        @foreach ($send as $send)
                                            <option>{{ $send->發料部門 }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-100" style="height: 2ch;"></div><!-- </div>breaks cols to a new line-->

                                <label class="col col-auto form-label m-0 p-0">{!! __('monthlyPRpageLang.applydate') !!}</label>
                                <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <div class="input-group">
                                        <input class="form-control form-control-lg" type="date" id="begin"
                                            name="begin" value="<?php echo date('Y-m-d'); ?>" />
                                        <span class="input-group-text">~</span>
                                        <input class="form-control form-control-lg" type="date" id="end"
                                            name="end" value="<?php echo date('Y-m-d'); ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="w-100" style="height: 2ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="row justify-content-center">
                                <div class="col col-auto">
                                    <input type="submit" id="search" name="search" class="btn btn-lg btn-primary"
                                        value="{!! __('monthlyPRpageLang.search') !!}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
