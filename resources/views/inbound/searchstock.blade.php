@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/inbound/searchstock.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
    </head>
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">

            <h2 class="col-auto">{!! __('templateWords.inbound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="card w-75">
                <div class="card-header">
                    <h3>{!! __('inboundpageLang.searchstock') !!}</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="card-body">
                        <div class="d-flex w-100">
                            <form id="form1" method="POST">
                                @csrf
                                <div class="row w-100 justify-content-center mb-3">
                                    <label class="col col-auto form-label m-0 p-0">{!! __('inboundpageLang.isn') !!}</label>
                                    <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="col-lg-6  col-md-12 col-sm-12">
                                        <input class="form-control form-control-lg @error('number') is-invalid @enderror"
                                            type="text" id="number" name="number"
                                            placeholder="{!! __('inboundpageLang.enterisn') !!}"
                                            >
                                        @error('number')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                    <label class="col col-auto form-label m-0 p-0">{!! __('inboundpageLang.loc') !!}</label>
                                    <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="col-lg-6  col-md-12 col-sm-12">
                                        <input id="position" name="position" type="text" list="position_datalist"
                                            class="form-control form-control-lg" placeholder="{!! __('inboundpageLang.enterloc') !!}">
                                        <datalist id="position_datalist">
                                            @foreach ($position as $position)
                                                <option>{{ $position->儲存位置 }}</option>
                                            @endforeach
                                        </datalist>
                                    </div>

                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                    <label class="col col-auto form-label m-0 p-0">{!! __('inboundpageLang.senddep') !!}</label>
                                    <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="col-lg-6  col-md-12 col-sm-12">
                                        <select class="form-select form-select-lg" id="send" name="send">
                                            <option style="display: none" disabled selected>{!! __('inboundpageLang.entersenddep') !!}</option>
                                            @foreach ($senddep as $senddep)
                                                <option>{{ $senddep->發料部門 }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-100" style="height: 2ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="row justify-content-center">
                                        <div class="form-check col col-auto">
                                            <input class="basic form-check-input" type="radio" name="stock"
                                                id="stock" value="0" checked>
                                            <label class="form-check-label" for="stock">
                                                {!! __('inboundpageLang.nowstock') !!}
                                            </label>
                                        </div>
                                        <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                                        <div class="form-check col col-auto">
                                            <input class="basic form-check-input" type="radio" name="month"
                                                id="month" value="1">
                                            <label class="form-check-label" for="month">
                                                {!! __('inboundpageLang.stockmonth') !!}
                                            </label>
                                        </div>
                                        <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                                        <div class="form-check col col-auto">
                                            <input class="basic form-check-input" type="radio" name="nogood"
                                                id="nogood" value="2">
                                            <label class="form-check-label" for="nogood">
                                                {!! __('inboundpageLang.nogood') !!}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="w-100" style="height: 2ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="row w-100 justify-content-center">
                                        <div class="col col-auto">
                                            <input type="submit" id="search" name="search"
                                                class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.search') !!}">
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </html>
@endsection
